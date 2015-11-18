<?php
//handles the user's login and logout process

class Login{

    private $db_connection = null;
    public $errors = array(); //collection of error messages
    public $messages = array(); //collection of success / neutral messages

	//construct function triggers on object creation
    public function __construct($db_connection){
        $this->db_connection = $db_connection;

        // check the possible login actions:
        if (isset($_GET["logout"])) {
            $this->doLogout();
        }
        elseif (isset($_POST["login"])) {
            $this->dologinWithPostData();
        }
    }

    //log in with post data
    private function dologinWithPostData()
    {
        // check login form contents
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Username field was empty.";
        } 
        elseif (empty($_POST['user_password'])) {
            $this->errors[] = "Password field was empty.";
        } 
        elseif (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

            $user_name = $_POST['user_name'];

            // get info for selected user (allows login via email address in the username field)
            $sql = "SELECT user_id, user_name, user_email, user_password_hash, rss_key
                    FROM users
                    WHERE user_name = ? OR user_email = ?";
            $stmt = $this->db_connection->prepare($sql);
			$stmt->execute(array($user_name, $user_name));
			$row = $stmt->fetchObject();

            // if this user exists
            if (count($row) >= 1) {

                //check if the provided password fits the hash of that user's password
                if (password_verify($_POST['user_password'], $row->user_password_hash)) {

                    // write user data into PHP SESSION
                    $_SESSION['user_id'] = $row->user_id;
                    $_SESSION['user_name'] = $row->user_name;
                    $_SESSION['user_email'] = $row->user_email;
                    $_SESSION['rss_key'] = $row->rss_key;
                    $_SESSION['user_login_status'] = 1;

                } 
                else {
                    $this->errors[] = "Wrong password. Try again.";
                }
            } 
            else {
                $this->errors[] = "This user does not exist.";
            }
           
        }
    }

    //logout
    public function doLogout(){
        $_SESSION = array();
        session_destroy();
        $this->messages[] = "You have been logged out.";
    }

    //return the current state of the user's login
    public function isUserLoggedIn(){
    
        if (isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1) {
            return true;
        }
   
        return false;
    }
}
