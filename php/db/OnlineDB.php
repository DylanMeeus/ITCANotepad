<?php


require_once "php/db/IDatabase.php";
require_once "php/core/User.php";
require_once "php/core/Note.php";
require_once "php/core/Link.php";

class OnlineDB implements IDatabase
{
    // change this when using the production database.
    // Set up for the test database. (ghost)
    private $servername = "it-ca.net";
    private $username = "itca_Insanity";
    private $password = 't&Musk$StEJ[';
    private $con;

    public function __construct()
    {
    }

    private function closeConnection()
    {
        $this->con = null;
    }

    private function openConnection()
    {
        try
        {
            $this->con = new PDO("mysql:host=" . $this->servername . ";dbname=itca_ghostnotes", $this->username, $this->password);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex)
        {
            echo "Connection failed " . $ex->getMessage();
        }
    }

    /**
     * Returns a user if a user has been found, otherwise returns null.
     * @param $username
     * @param $password
     * @return User
     */
    public function login($username, $password)
    {
        $this->openConnection();
        $sql = "select users.id as userid, username, apikeys.apikey from users left join apikeys on users.apikey = apikeys.id where username= ? and password  =?";

        $statement = $this->con->prepare($sql);
        $statement->bindParam(1,$username);
        $statement->bindParam(2,$password);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();
        foreach ($result as $row)
        {
            $user = new User();
            $user->setID($row['userid']);
            $user->setUsername($row['username']);
            if($row['apikey'] != NULL)
            {
                $user->setAPIKey($row['apikey']);
            }
            $this->closeConnection();
            return $user;
        }
        $this->closeConnection();
        return null; // otherwise, null!

    }


    public function getNotes($userID)
    {
        $this->openConnection();

        $sql = "select * from notes n LEFT join sharednotes sh on n.noteID = sh.sharednoteID where sh.sharednoteID IS NULL and n.userID = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $userID);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $notes = array();
        foreach ($result as $row)
        {
            $note = new Note();
            $note->setID($row['noteID']);
            $note->setTitle($row['title']);
            $note->setText($row['notetext']);
            $note->setColour($row['colour']);
            array_push($notes, $note);
        }
        $this->closeConnection();
        return $notes;
    }

    public function getSharedNotes($userID)
    {
        $this->openConnection();

        $sql = "select sharednoteID, sh.userID as sharedID, rightID, title, notetext, colour, n.userID as ownerID from sharednotes sh JOIN notes n ON sharednoteID = noteID where sh.userID = ?";

        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $userID);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $sharednotes = array();

        foreach ($result as $row)
        {
            $note = new Note();
            $note->setID($row['sharednoteID']);
            $note->setTitle($row['title']);
            $note->setText($row['notetext']);
            $note->setColour($row['colour']);
            $note->setUserID($row['ownerID']);

            $users = array();
            $user = new User();
            $user->setID($row['ownerID']);
            $username = $this->getUserDetails($row['ownerID'])->getUsername();
            $user->setUsername($username);
            array_push($users, $user);
            $note->setSharedUsers($users);
            array_push($sharednotes, $note);
        }
        $this->closeConnection();
        return $sharednotes;
    }

    /*public function deleteSharedNote($noteID)
    {
        $this->deleteNote($noteID);
        $this->openConnection();
        $sql = "delete from sharednotes where sharednoteID = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $noteID);
        $statement->execute();
        $this->closeConnection();
    }*/

    public function deleteSharedUser($userID, $noteID)
    {
        $this->openConnection();
        $sql = "delete from sharednotes where sharednoteID = ? and userID = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $noteID);
        $statement->bindParam(2, $userID);
        $statement->execute();
        $this->closeConnection();
    }

    public function addSharedUsers($noteID, $users, $rightIDList)
    {
        $this->openConnection();
        for($i = 0; $i < sizeof($users);$i++){
            $sql = "insert into sharednotes(sharednoteID,userID,rightID) values(?,?,?)";
            $statement = $this->con->prepare($sql);
            $statement->bindParam(1, $noteID);
            $statement->bindParam(2, $users[$i]->getID());
            $statement->bindParam(3, $rightIDList[$i]);
            $statement->execute();
        }

        $this->closeConnection();
    }

    public function getNoteDetails($noteID)
    {
        $this->openConnection();
        $sql = "select * from notes where noteID = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $noteID);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $notedetails = new Note();
        foreach ($result as $row)
        {
            $notedetails->setID($noteID);
            $notedetails->setText($row['notetext']);
            $notedetails->setTitle($row['title']);
            $notedetails->setColour($row['colour']);
            $notedetails->setUserID($row['userID']);
            break; // there can't be more than one note tbh; really want to do an explicit goto here in asm? mh.
        }

        $this->closeConnection();
        return $notedetails;
    }

    public function getSharedNoteDetails($noteID){

        $notedetails = $this->getNoteDetails($noteID);
        $this->openConnection();
        $sql = "select * from sharednotes where sharednoteID = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $noteID);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $users = array();
        foreach ($result as $row)
        {
            $user = new User();
            $user->setID($row['userID']);
            $username = $this->getUserDetails($row['userID'])->getUsername();
            $user->setUsername($username);
            array_push($users, $user);
        }
        $notedetails->setSharedUsers($users);
        $notedetails->setSharedUsers($users);
        $this->closeConnection();
        return $notedetails;
    }

    public function updateNote($noteID, $noteTitle, $noteText, $colour)
    {
        $this->openConnection();

        $sql = "update notes set title=?,notetext=?, colour=? where noteID=?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $noteTitle);
        $statement->bindParam(2, $noteText);
        $statement->bindParam(3, $colour);
        $statement->bindParam(4, $noteID);
        $statement->execute();
        $this->closeConnection();
    }

    public function addNote($userID, $title)
    {
        $lastID = $this->getLastNoteID();
        $this->openConnection();

        $sql = "insert into notes(title,userID) values(?,?)";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $title);
        $statement->bindParam(2, $userID);
        $statement->execute();
        $newnote = new Note();
        $newnote->setID($lastID + 1);
        $newnote->setTitle($title);
        $this->closeConnection();
        return $newnote;
    }

    private function getLastUserID() // another helper function
    {
        $id = -1;
        $this->openConnection();
        $sql = "select max(id) as m from users";
        $statement = $this->con->prepare($sql);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        foreach ($result as $row)
        {
            $id = $row['m'];
            break;
        }
        $this->closeConnection();
        return $id;
    }

    private function getLastNoteID() // helper function, to return the new note when creating one - so we don't have to query the whole DB again?
    {
        $id = -1;
        $this->openConnection();
        $sql = "select max(noteID) as m from notes";
        $statement = $this->con->prepare($sql);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

            foreach ($result as $row)
            {
            $id = $row['m'];
            break;
        }
        $this->closeConnection();
        return $id;
    }


    public function register($username, $password, $mail)
    {
        $lastID = $this->getLastUserID();
        $this->openConnection();
        $sql = "insert into users(username,password, email) values(?,?,?)";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $username);
        $statement->bindParam(2, $password);
        $statement->bindParam(3, $mail);
        $statement->execute();
        $this->closeConnection();
        $user = new User();
        $user->setID($lastID + 1);
        $user->setUsername($username);
        $user->setEmail($mail);
        return $user;
    }


    public function getLinks($noteID)
    {
        // return links
        $this->openConnection();

        $sql = "select * from notelinks where noteID = ?";

        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $noteID);

        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $links = array();
        foreach ($result as $row)
        {
            $link = new Link();
            $link->setName($row['shortname']);
            $link->setUrl($row['url']);
            $link->setID($row['notelinkID']);
            array_push($links, $link);
        }


        $this->closeConnection();
        return $links;


    }

    public function getUserFromUsername($username)
    {
        // return user from username
        $this->openConnection();

        $sql = "select * from users where username = ?";

        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $username);

        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $user = new User();
        foreach ($result as $row)
        {
            $user->setID($row['id']);
            $user->setUsername($row['username']);
            $user->setAPIKey($row['apikey']);

            break; // only one user
        }
        $this->closeConnection();
        return $user;
    }

    public function saveLink($noteID, $linkurl, $linkname)
    {
        $this->openConnection();

        $sql = "insert into notelinks(url,shortname,noteID) values(?,?,?)";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $linkurl);
        $statement->bindParam(2, $linkname);
        $statement->bindParam(3, $noteID);
        $statement->execute();
        $this->closeConnection();

    }


    public function deleteLink($linkID)
    {
        $this->openConnection();

        $sql = "delete from notelinks where notelinkID=?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $linkID);
        $statement->execute();
        $this->closeConnection();
    }

    public function getNoteText($userID, $title)
    {
        // TODO: Implement getNoteText() method.
    }

    public function deleteNote($noteID)
    {
        $this->openConnection();
        $sql = "delete from notes where noteID = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $noteID);
        $statement->execute();
        $this->closeConnection();
    }

    public function verifyPassword($userID, $password)
    {
        $this->openConnection();

        $sql = "select * from users where id=? and password =?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $userID);
        $statement->bindParam(2, $password);

        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();
        $isValid = false;
        if (sizeof($result) == 1)
        {
            $isValid = true; // If a record is  found, it must be the right record. Trivial logic tbh - not sure why I comment it.
            // We can not 'short circuit' the method though because the connection needs to be closed!
            // Well we could, but it looks better this way
        }
        $this->closeConnection();
        return $isValid;
    }


    public function getUsersAPIKey($userID)
    {
        $this->openConnection();

        $sql = "select apikeys.apikey from users inner join apikeys on users.apikey = apikeys.id where users.id = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1,$userID);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $results = $statement->fetchAll();
        $myKey = "";
        foreach($results as $row)
        {
            $myKey = $row['apikey'];
        }
        $this->closeConnection();
        return $myKey;
    }

    // this adds an apikey in the apikeys table.
    public function addAPIKey($userID, $key)
    {
        $this->openConnection();

        // first we should ckeck for a collision

        $checksql = "select * from apikeys where apikey = ?";
        $checkstatement = $this->con->prepare($checksql);
        $checkstatement->bindParam(1, $key);
        $checkstatement->setFetchMode(PDO::FETCH_ASSOC);
        $checkresults = $checkstatement->fetchAll();
        $isValid = true;
        if (sizeof($checkresults) == 1)
        {
            $isValid = false;
            $this->closeConnection();
            return false;
        } else
        {
            $sql = "insert into apikeys(apikey) values(?)";
            $statement = $this->con->prepare($sql);
            $statement->bindParam(1, $key);
            $statement->execute();

            $lastKey = 0; // now we check what the last key was that got entered in the database.

            $keysql = "select max(id) as m from apikeys";
            $keystatement = $this->con->prepare($keysql);
            $keystatement->execute();
            $keystatement->setFetchMode(PDO::FETCH_ASSOC);
            $keyResults = $keystatement->fetchAll();
            foreach ($keyResults as $row)
            {
                $lastKey = $row['m'];
            }
            $this->closeConnection();
            $this->attachKeyToUser($userID, $lastKey);
        //    return true; // We can just fall through end return true.
        }

        return true; // if we got here without errors, we can return true.

    }

    // This adds the apikey to the user by altering the user table.
    private function attachKeyToUser($userID, $keyID)
    {
        $this->openConnection();
        $sql = "update users set apikey = ? where id = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $keyID);
        $statement->bindParam(2, $userID);
        $statement->execute();
        $this->closeConnection();
    }

    public function addSharedNote($userID, $users, $title, $rightIDList)
    {
        $this->addNote($userID, $title);

        $lastID = $this->getLastNoteID();
        $this->openConnection();
        $rightID = 1;
        $sql = "insert into sharednotes(sharednoteID,userID,rightID) values(?,?,?)";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $lastID);
        $statement->bindParam(2, $userID);
        $statement->bindParam(3, $rightID);
        $statement->execute();
        $newSharedNote = new Note();
        $newSharedNote->setID($lastID);
        $newSharedNote->setUserID($userID);
        $newSharedNote->setTitle($title);



        for($i = 0; $i < sizeof($users); $i++){
            $sql = "insert into sharednotes(sharednoteID,userID,rightID) values(?,?,?)";
            $statement = $this->con->prepare($sql);
            $statement->bindParam(1, $lastID);
            $statement->bindParam(2, $users[$i]->getID());
            $statement->bindParam(3, $rightIDList[$i]);
            $statement->execute();
        }
        $user = $this->getUserDetails($userID);
        array_unshift($users, $user);
        $newSharedNote->setSharedUsers($users);

        $this->closeConnection();
        return $newSharedNote;
    }

    public function deleteSharedNote($noteID)
    {
        $this->deleteNote($noteID);
        $this->openConnection();
        $sql = "delete from sharednotes where sharednoteID = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $noteID);
        $statement->execute();
        $this->closeConnection();
    }

    public function getUserDetails($userID)
    {
        $this->openConnection();
        $sql = "select * from users where id = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $userID);
        $statement->execute();
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $result = $statement->fetchAll();

        $user = new User();
        foreach ($result as $row)
        {
            $user->setID($row['id']);
            $user->setAPIKey($row['apikey']);
            $user->setUsername($row['username']);
           break; // there can't be more than one user tbh; really want to do an explicit goto here in asm? mh.
        }

        $this->closeConnection();
        return $user;
    }


    public function changepassword($userID, $newpassword) // it is a hashed new password but I'll improve this when I have time to be honest.
    {
        $this->openConnection();

        $sql = "update users set password = ? where id = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1, $newpassword);
        $statement->bindParam(2, $userID);
        $statement->execute();
        $this->closeConnection();
    }

    /**
     * Return true if the username does not yet appear in the database.
     * @param $username
     */
    public function isUniqueUsername($username)
    {
        $this->openConnection();

        $sql = "select * from users where username = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1,$username);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $statement->execute();
        $results = $statement->fetchAll();
        $unique = true;
        foreach ($results as $row)
        {
            // if we have a result, we know that it is not unique.
            $unique = false;
            break;
        }
        $this->closeConnection();
        return $unique;
    }

    public function createPasswordRecovery($mail, $recoveryString)
    {
        // First look for email; then create entry if it was found.
        $userID = $this->getIDFromMail($mail);
        if($userID==-1)
        {
            return false; // ?o user was found with this mail.
        }
        $recoveryString = $userID.'-'.$recoveryString; // add userID for uniqueness. the - was superfluous due to the ID being stored in the table though.
        $this->openConnection();
        $sql = "insert into passwordrecovery(userID, recoverystring) values (?,?)";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1,$userID);
        $statement->bindParam(2,$recoveryString);
        $statement->execute();
        $this->closeConnection();
        return $recoveryString;
    }

    public function resetPassword($password,$recoveryString)
    {
        // first we make sure that there is an entry in the password recovery table.
        // Make sure to clean up the database after the password was reset so the link can not be used twice.

        $userID = -1;

        $this->openConnection();

        $sql = "select * from passwordrecovery where recoverystring = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1,$recoveryString);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $statement->execute();
        $results = $statement->fetchAll();
        foreach($results as $row)
        {
            $userID = $row['userID'];
        }

        if($userID==-1)
        {
            return false;
        }
        $this->closeConnection(); // We have to close the connection here so the next method does not cause problems with the database.
        $this->changepassword($userID,$password);


        $this->openConnection();

        $sql = "delete from passwordrecovery where userID = ?"; // We use userID so all recovery attempts of this user are removed. We don't want them dangling around (security issues).
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1,$userID);
        $statement->execute();
        $this->closeConnection();
        // If we have a user we can now update his password AND remove this record from the database.
        return true;
    }

    private function getIDFromMail($mail)
    {
        $this->openConnection();

        $sql = "select * from users where email = ?";
        $statement = $this->con->prepare($sql);
        $statement->bindParam(1,$mail);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $statement->execute();
        $results = $statement->fetchAll();
        $id = -1;
        foreach($results as $row)
        {
            $id = $row['id'];
        }
        $this->closeConnection();
        return $id;
    }
}