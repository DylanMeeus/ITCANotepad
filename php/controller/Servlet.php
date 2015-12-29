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
    private $openednotes;
    private $redirect;
    private $notelinks;
    private $recoveryData;
    //true: shows extra partials needed for options for shared notes
    private $shared;
    private $right;
    // We need to get rid of saying "Echo" in the servlet. ECHO == UI stuff; out with that demon here!
    private $errors = array(); // Logs the errors that occur -> each page loops over this array to see if it needs to display something
    private $notifications = array(); // Logs the notifications (They can be seen as 'successes' as opposed to 'errors'.

    public function __construct()
    {
        $this->shared = false;
        $this->facade = new Facade();
        $this->openednotes = array();
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
        }
        elseif ($action == "opensharednote")
        {
            $this->shared = true;
            $noteID = $_GET['noteid'];
            $_POST['noteid'] = $noteID;

            $user = $_SESSION["user"];


            $this->note = $this->facade->getSharedNoteDetails($noteID);
            $foundID = false;
            if(!$this->note->isOpened()){
                $sharedusers = $this->note->getSharedUsers();
                for($i = 0; $i < sizeof($sharedusers); $i++){
                    if ($sharedusers[$i]->getID() == $user->getID())
                    {
                        $foundID = true;
                        $this->right = $this->note->getRights()[$i];
                        $this->notelinks = $this->facade->getLinks($noteID);
                        $this->facade->openSharedNote($noteID);
                       // array_push($this->openednotes, $this->note);
                        $nextPage = "notepage.php";
                        break;
                    }
                }
                if(!$foundID){
                    $nextPage = "errorpage.php";
                }
            } else{
                array_push($this->errors, "Somebody is working on that note, try again later");
                $nextPage = $this->gotoSharedNotes();
            }
        }elseif ($action == "savenote")
        {
            $this->facade->cipher("Hey, Hello World!");
            $noteID = $_POST['noteid'];
            $textData = $_POST['textData'];
            $titleData = $_POST['titleData'];
            $colour = $_POST['colour'];
            $this->facade->updateNote($noteID, $titleData, $textData, $colour);
            $this->redirect = false;
        } elseif ($action == "createnote")
        {
            $nextPage = "notepage.php";
            $title = $_POST['newnotetitle'];
            $user = $_SESSION["user"];
            if($title === ""){
                array_push($this->errors, "Title can't be empty");
                $nextPage = 'notes.php';
            }
            else if(!$this->facade->isUniqueNotetitleForUser($user->getID(),$title)){
                array_push($this->errors, "You already have created a note with that title");
                $this->notes = $this->facade->getNotes($user->getID());
                $nextPage = 'notes.php';
            }

            else {
                $this->note = $this->facade->addNote($user->getID(), $title);
                $this->notelinks = null;
            }
        }elseif ($action == "createsharednote") {
            $this->right = 1;
            $this->shared = true;
            $nextPage = "notepage.php";
            $title = $_POST['newnotetitle'];
            $user = $_SESSION["user"];
            if($title === ""){
                array_push($this->errors, "Title can't be empty");
                $nextPage = $this->gotoSharedNotes();
            }
            else if(!$this->facade->isUniqueNotetitleForUser($user->getID(),$title)){
                array_push($this->errors, "You already have a note with that title");
                $nextPage = $this->gotoSharedNotes();
            }

                $users = array();
                $rightIds = array();
                $lastuser = false;

                for ($i = 1; !$lastuser; $i++) {
                    if (isset($_POST['username' . $i])) {
                        $sharedusername = $_POST['username' . $i];
                        $shareduser = $this->facade->getUserFromUsername($sharedusername);
                        if($shareduser->getUsername() == null) {
                            array_push($this->errors, "User(s) not present in database");
                            $nextPage = $this->gotoSharedNotes();
                            break;
                        }
                        else if($this->checkForDuplicateSharedUsers($shareduser, $users)) {
                            array_push($this->errors, "Duplicate user forbidden");
                            $nextPage = $this->gotoSharedNotes();
                            break;
                        } else{
                            array_push($users, $shareduser);
                            $rightID = $_POST['rightID' . $i];
                            array_push($rightIds, $rightID);
                        }
                    } else {
                        $lastuser = true;
                    }
                }
                if(empty($this->errors)) {
                    $this->note = $this->facade->addSharedNote($user->getID(), $users, $title, $rightIds);
                    $this->notelinks = null;
                }

        }
        elseif($action == "makeshared"){
            $this->shared = true;
            $noteID = $_POST['noteID'];
            $user = $_SESSION['user'];
            $this->note = $this->facade->makeShared($noteID, $user->getID());
            $nextPage = "notepage.php";
        } elseif($action == "getUsers") {
            $users = $this->facade->getUsers();
            $usernames = array();
            $currentUser = $_SESSION["user"];
            foreach ($users as $user) {
                if($currentUser->getID() != $user->getID()){
                    array_push($usernames, $user->getUsername());
                }
            }
            echo json_encode($usernames);
            $this->redirect = false;
        }
        elseif ($action == "gotonotelist")
        {
            if(isset($_GET['sharednoteid'])){
                $sharednoteID = $_GET['sharednoteid'];
                $this->facade->closeSharedNote($sharednoteID);
            }
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
            $mail = "";
            if(isset($_POST['email']))
            {
                $mail = $_POST['email'];
            }




            // We don't need to use the token anymore. Anyone can register now.
            $user = $this->facade->register($username, $pass, $mail);
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
        } elseif ($action == "deletesharednote")
        {
            $noteID = $_GET['noteid'];
            $this->facade->deleteSharedNote($noteID);
            $user = $_SESSION['user'];
            $this->notes = $this->facade->getSharedNotes($user->getID());
            $nextPage = $this->gotoSharedNotes();
        } elseif($action == "addsharedusers"){
            $this->shared = 1;
            $noteID = $_POST['noteID'];
            $this->note = $this->facade->getSharedNoteDetails($noteID);
            $toAddusers = array();
            $alreadyAddedUsers = $this->note->getSharedUsers();
            $rightIds = array();
            $lastuser = false;
            for ($i = 1; !$lastuser; $i++) {
                if (isset($_POST['username' . $i])) {
                    $sharedusername = $_POST['username' . $i];
                    $shareduser = $this->facade->getUserFromUsername($sharedusername);
                    if($shareduser->getUsername() == null) {
                        array_push($this->errors, "User(s) not present in database");
                        break;
                    }
                    else if($this->checkForDuplicateSharedUsers($shareduser, $alreadyAddedUsers)){
                        array_push($this->errors, "Duplicate user forbidden");
                        break;
                    }
                    else{
                        array_push($toAddusers, $shareduser);
                        $rightID = $_POST['rightID' . $i];
                        array_push($rightIds, $rightID);
                    }
                }
                else {
                    $lastuser = true;
                }
            }
            if(empty($this->errors)) {
                $this->facade->addSharedUsers($noteID, $toAddusers, $rightIds);
                $this->note = $this->facade->getSharedNoteDetails($noteID);
            }
                $this->shared = true;
                $nextPage = "notepage.php";
        }elseif($action == "deleteuser"){
            $userID = $_GET['userid'];
            $noteID = $_GET['noteid'];
            $this->facade->deleteSharedUser($userID, $noteID);
            $this->note = $this->facade->getSharedNoteDetails($noteID);
            $this->shared = true;
            $nextPage = "notepage.php";
        }
        elseif ($action == "savelink")
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
            if(isset($_GET['sharednoteid'])){
                $sharednoteID = $_GET['sharednoteid'];
                $this->facade->closeSharedNote($sharednoteID);
            }
            $nextPage = $this->logout();
        } elseif ($action == "gotoaccount")
        {
            if(isset($_GET['sharednoteid'])){
                $sharednoteID = $_GET['sharednoteid'];
                $this->facade->closeSharedNote($sharednoteID);
            }
            $nextPage = $this->gotoAccount();
        } elseif ($action == "changePassword")
        {
            $nextPage = $this->changePassword();
        } elseif ($action == "gotoSharedNotes")
        {
            if(isset($_GET['sharednoteid'])){
                $sharednoteID = $_GET['sharednoteid'];
                $this->facade->closeSharedNote($sharednoteID);
            }
                $nextPage = $this->gotoSharedNotes();
        }elseif ($action == "notelookup")
        {
            $word = $_GET['word'];
            if($word != null && $word != "") {

                $lookupNotes = $this->lookupNote($word, $this->notes);
                $this->notes = $lookupNotes;
            } else{
                $user = $_SESSION["user"];
                $this->notes = $this->facade->getNotes($user->getID());
            }
            $this->redirect = false;
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
        elseif($action == "gotoforgotpassword")
        {
            $nextPage = "forgotpassword.php";
        }
        elseif($action == "startpasswordrecovery")
        {
            $nextPage = $this->startPasswordRecovery();
            $this->redirect=false;
        }
        elseif($action == "gotorecoverpassword")
        {
             $nextPage = $this->gotoRecoverPassword();
        }
        elseif($action == "resetPassword")
        {
            $nextPage = $this->resetPassword();
        }
        elseif($action == "cipher")
        {
            $nextPage = $this->cipher();
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
            // To have a better system for sending notifications, it might be good to check in the GET method for a standard param such as 'notif' or 'error'
            $this->populateErrors();
            $this->populateNotifications();
            require_once("web/" . $nextPage);
        }
    }

    private function populateNotifications()
    {
        if(isset($_GET['notif']))
        {
            $notificationMessage = $_GET['notif'];
            switch($notificationMessage)
            {
                case "recoverysend":
                    array_push($this->notifications, "Recovery mail send successfully!");
                    break;
            }
        }
    }

    private function checkForDuplicateSharedUsers($shareduser, $sharedusers){
        foreach($sharedusers as $user){
            if($user->getID() == $shareduser->getID()){
                return true;
            }
        }
        return false;
    }

    private function closeSharedNote($noteID){

            //we search the key in the array 'openednotes' that corresponds to the noteID.
            foreach($this->openednotes as $note){
                if($note->getID() == $noteID){
                    //if found, we remove the noteID from the array to show that the note is no longer being editted
                    $note->setOpened(false);
                }
            }
    }

    private function populateErrors()
    {

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

    private function checkIfNoteIsOpened($noteID){
        foreach($this->openednotes as $note){
            if($noteID == $note->getID()){
                return $note->isOpened();
                break;
            }
        }
        return false;
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
        $user = $_SESSION["user"];
        $this->notes = $this->facade->getSharedNotes($user->getID());
        return "sharednotes.php";
    }

    public function startPasswordRecovery()
    {

        $mail = $_GET['email'];
        $result = $this->facade->startPasswordRecovery($mail);
        if(!$result)
        {
            // print  the error
            array_push($this->errors, "Could not find an account with that email");
            echo "forgotpassword.php";
        }
       echo $result;
    }

    private function gotoRecoverPassword()
    {
        // We need to filter out some data fr
        $this->recoveryData = $_GET["recoveryid"];
        echo "Data " . $this->recoveryData;
        return "passwordrecovery.php";
    }



    private function resetPassword()
    {
        if(isset($_POST['inputPassword']))
        {
            $inputPassword = $_POST['inputPassword'];
            $repeatPassword = $_POST['repeatPassword'];
            $recoveryString = $_POST['recoverydata'];
            echo "input: " . $inputPassword . "repeat: " . $repeatPassword . " recovery: " . $recoveryString;
            if($inputPassword == $repeatPassword)
            {
                // Reset the users password. We can filter the recoveryString when we need to.
                if($this->facade->resetPassword($inputPassword,$recoveryString))
                {
                    array_push($this->notifications,"Password successfully reset.");
                }
                else
                {
                    array_push($this->errors, "It seems like your password recovery attempt failed. Please start a new one");
                }
                return "home.php";
            }
        }
        array_push($this->errors,"Password fields need to match and can not be empty.");
        return "passwordrecovery.php";
        // If we reached this, we have an error somewhere.
    }
}