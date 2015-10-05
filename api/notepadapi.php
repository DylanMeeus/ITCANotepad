<?php


// restfull service.
// we expose some methods that our api can use. But the method chaining might become an issue.


require_once "core/ApiFacade.php";
class notepadapi
{

    private $facade;
    public function processRequest()
    {

        // We get the data from the URL, and based on this we decide what to do.
        // every request has to be of the form: it-ca.net/noteapi?authkey='key'&action="...". The actions can then filter out the provided data.
        // POST for POST stuff, GET for GET stuff, y'know.

        // if (isset($_GET['action']))

        $this->facade = new ApiFacade();
        $authenticated = $this->authenticate();

        // if the API key is set, it should work. So we don't actually need a seperate login?
        if($authenticated != -1) // -1 indicates the database had no match and thus, if it is not -1, there was a match.
        {
            $action = "";
            if(isset($_GET['action']))
            {
                $action = isset($_GET['action']);
            }
            else if(isset($_POST['action']))
            {
                $action = isset($_POST['action']);
            }

            switch($action)
            {
                case "createnote": $this->createNote($authenticated); // authenticated stores the users ID at this point.
                    break;
            }
        }
        else{
            // return an error
        }


    }

    // creates and saves a note. Also saves the link by default.
    private function createNote($userid)
    {
        // get title from post data.
        $title = $_POST["titleData"];
        $text = $_POST["textData"];
        $this->facade->createNote($title,$text,$userid);
    }


    private function authenticate()
    {
        $authKey = "";
        if (isset($_GET['authkey']))
        {
            $authKey = $_GET['authkey'];
        }
        if (isset($_POST['authkey']))
        {
            $authKey = $_POST['authkey'];
        }

        // temp authkey = 1234;
        // Get it form the database.
       $userID = $this->facade->authenticateKey($authKey);
       return $userID;
    }


}




/*
 *             $testXML = <<<XML
<note>
    <user>
    Insanity
</user>
</note>
XML;
            $xml = new SimpleXMLElement($testXML);
            echo $xml->asXML();
 */


$notepadAPI = new notepadapi();
$notepadAPI->processRequest();

?>