<?php



require_once "php/core/Facade.php";
require_once "php/core/User.php";
require_once "php/core/Note.php";


// I SHOULD REFACTOR THIS STATEMACHINE INTO SOMETHING A BIT MORE..DECENTLY..

class Servlet
{

    private $facade;
    private $notes;
    private $note;
    private $redirect;
    private $notelinks;

    // We need to get rid of saying "Echo" in the servlet. ECHO == UI stuff; out with that demon here!
    private $errors = array(); // Logs the errors that occur -> each page loops over this array to see if it needs to display something
    private $notifications = array(); // Logs the notifications (They can be seen as 'successes' as opposed to 'errors'.

    public function __construct()
    {
        $this->facade = new Facade();
    }

    public function processRequest()
    {
        session_start();
        $this->redirect = true;
        // echo 'processing';
        $action = "gotologin";
        if (isset($_GET['action']))
        {
            $action = $_GET['action'];
        } elseif (isset($_POST['action']))
        {
            $action = $_POST['action'];
        }
        $nextPage = "";


        if ($action == "gotologin")
        {
            // Check the cookie
            // If there is a cookie with the correct username/password
            // Then go to the page with the logged in user information.

            if (isset($_COOKIE['remembercookie']))
            {
                $user = new User();
                $user->setID($_COOKIE['remembercookie']);
                $user->setAPIKey($this->facade->getUsersAPIKey($user->getID()));
                $_SESSION["user"] = $user;
                $this->notes = $this->facade->getNotes($user->getID());
                $nextPage = 'notes.php';
            } else
            {
                $nextPage = 'home.php';
            }

            // try some database stuff
        } elseif ($action == "login")
        {
            if (isset($_POST['username']) && isset($_POST['password']))
            {
                $username = $_POST['username'];
                $password = $_POST['password'];
                // check if they are correct
                $user = $this->facade->login($username, $password);
                $_SESSION["user"] = $user;
                if ($user != null)
                {
                    $this->notes = $this->facade->getNotes($user->getID());
                    $nextPage = 'notes.php';

                    // do we also need to remember  this user?
                    if (isset($_POST['rememberme']))
                    {
                        // if isset evaluates to true; the checkbox was ticked.
                        $memory = $_POST['rememberme'];
                        setcookie("remembercookie", $user->getID());
                    }
                } else
                {
                    array_push($this->errors, "Wrong username/password");
                    $nextPage = 'home.php';
                }

            }
        } elseif ($action == "opennote")
        {
            $noteID = $_GET['noteid'];
            $_POST['noteid'] = $noteID; // get it async using jquery and writing a new query, or just loop here?

            // does the note belong to this user?
            $user = $_SESSION["user"];

            $this->note = $this->facade->getNoteDetails($noteID);
            if ($this->note->getUserID() == $user->getID())
            {
                $this->notelinks = $this->facade->getLinks($noteID);
                $nextPage = "notepage.php";
            } else
            {
                $nextPage = "errorpage.php";
            }
        } elseif ($action == "savenote")
        {
            $noteID = $_POST['noteid'];
            $textData = $_POST['textData'];
            $titleData = $_POST['titleData'];
            $colour = $_POST['colour'];
            $this->facade->updateNote($noteID, $titleData, $textData, $colour);
            $this->redirect = false;
        } elseif ($action == "createnote")
        {
            $title = $_POST['newnotetitle'];
            $user = $_SESSION["user"];
            $this->note = $this->facade->addNote($user->getID(), $title);
            $this->notelinks = null;
            $nextPage = "notepage.php";
        } elseif ($action == "gotonotelist")
        {
            $user = $_SESSION["user"];
            $this->notes = $this->facade->getNotes($user->getID());
            $nextPage = "notes.php";
        } elseif ($action == "gotoregister")
        {
            $nextPage = "register.php";
        } elseif ($action == "register")
        {
            $username = $_POST['username'];
            $pass = $_POST['password'];
            $token = $_POST['token'];

            // We don't need to use the token anymore. Anyone can register now.
            $user = $this->facade->register($username, $pass);
            $_SESSION['user'] = $user;
            $nextPage = "notes.php";
            /*
            if ($token == "ISEEYOURGHOST")
            {
                $user = $this->facade->register($username, $pass);
                $_SESSION['user'] = $user;
                $nextPage = "notes.php";
            } else
            {
                array_push($this->errors, "Wrong token!");
                $nextPage = "register.php";
            }
            */
        } elseif ($action == "deletenote")
        {
            $noteID = $_GET['noteid'];
            $this->facade->deleteNote($noteID);
            $user = $_SESSION['user'];
            $this->notes = $this->facade->getNotes($user->getID());
            $nextPage = "notes.php";
        } elseif ($action == "savelink")
        {
            echo "saving";
            $linkurl = $_POST['linkUrl'];
            $linkname = $_POST['linkName'];
            $noteID = $_POST['noteid'];
            $this->facade->savelink($noteID, $linkurl, $linkname);
            $this->redirect = false;
        } elseif ($action == "deletelink")
        {
            $linkID = $_POST['linkid'];
            $this->facade->deleteLink($linkID);
            $this->redirect = false;
        } elseif ($action == "logout")
        {
            $nextPage = $this->logout();
        } elseif ($action == "gotoaccount")
        {
            $nextPage = $this->gotoAccount();
        } elseif ($action == "changePassword")
        {
            $nextPage = $this->changePassword();
        } elseif ($action == "gotoSharedNotes")
        {
            $nextPage = $this->gotoSharedNotes();
        }
        elseif($action == "gotochangepassword")
        {
            $nextPage = "changepassword.php";
        }
        elseif($action == "generateAPIkey")
        {
            $this->generateAPIKey();
            $this->redirect = false;
        }
        elseif($action == "isuniqueusername"){
            // Pass username back as a string? well if we have one, not unique.
            // echo back false/true? Maybe easiest.
            // But do this in XML

            $this->isUniqueUsername();
            $this->redirect = false;
        }
        else{
            $nextPage = "errorpage.php";
        }


        if ($this->redirect)
        {
            require_once("web/" . $nextPage);
        }
    }

    private function isUniqueUsername()
    {

        $username = $_POST['username'];
        if($this->facade->isUniqueUsername($username)){
            echo "true";
        }
        else{
            echo "false";
        }


    }


    private function generateAPIKey()
    {
        // generate a key for the current user. If he does not yet have one.
        $user = $_SESSION["user"];
        $key = $this->facade->generateAPIKey($user->getID()); // This method returns the key..
        $user->setAPIKey($key);
        echo $key;
    }

    // I should actually have split this up in functions so much earlier.
    public function logout()
    {
        setcookie("remembercookie", null, time() - 3600); // set the value to null and let it expire in the past (1 day ago, time now in minutes - 3600)
        return "home.php";
    }

    public function gotoAccount()
    {
        return "account.php";
    }

    public function changePassword()
    {
        $oldPassword = $_POST['oldpassword'];
        $newPassword = $_POST['newpassword'];
        $repeatPassword = $_POST['repeatpassword'];

        // check if old password matches first

        $user = $_SESSION["user"];

        if ($newPassword == $repeatPassword && $this->facade->verifyPassword($user->getID(), $oldPassword) == true)
        {
            $this->facade->changePassword($user->getID(), $newPassword);
            //echo "please log in with your new password";
            array_push($this->notifications, "Please log in with your new password.");
            return "home.php";
        } else
        {
            array_push($this->errors, "Old and/or repeated password was wrong!"); // check in further detail later.
            return "account.php";
        }

    }

    public function gotoSharedNotes()
    {
        return "sharednotes.php";
    }

}