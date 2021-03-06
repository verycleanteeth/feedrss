<?php
//handles user registration
class Register{

    private $db_connection = null;
    public $errors = array(); //collection of error messages
    public $messages = array(); //collection of success/neutral messages

	//generates a random rss_key. Uses microtime to avoid collisions with existing keys.
	private function generateRandomString($length = 30) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

    //fire upon object creation
    public function __construct($db_connection){
    
    	$this->db_connection = $db_connection;
    	
        if (isset($_POST["register"])) {
            $this->registerNewUser();
        }
    }

    //handles the entire registration process. checks all error possibilities
    //and creates a new user with rss key in the database if everything is fine
    private function registerNewUser()
    {
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Empty Username";
        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
            $this->errors[] = "Empty Password";
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $this->errors[] = "Password and password repeat are not the same";
        } elseif (strlen($_POST['user_password_new']) < 6) {
            $this->errors[] = "Password has a minimum length of 6 characters";
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $this->errors[] = "Username cannot be shorter than 2 or longer than 64 characters";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            $this->errors[] = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
        } elseif (empty($_POST['user_email'])) {
            $this->errors[] = "Email cannot be empty";
        } elseif (strlen($_POST['user_email']) > 64) {
            $this->errors[] = "Email cannot be longer than 64 characters";
        } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Your email address is not in a valid email format";
        } elseif (!empty($_POST['user_name'])
            && strlen($_POST['user_name']) <= 64
            && strlen($_POST['user_name']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])
            && !empty($_POST['user_email'])
            && strlen($_POST['user_email']) <= 64
            && filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)
            && !empty($_POST['user_password_new'])
            && !empty($_POST['user_password_repeat'])
            && ($_POST['user_password_new'] === $_POST['user_password_repeat'])
        ) {


            // escaping, additionally removing everything that could be (html/javascript-) code
            $user_name = $_POST['user_name'];
            $user_email = $_POST['user_email'];
            $user_password = $_POST['user_password_new'];

            // crypt the user's password with PHP 5.5's password_hash() function, results in a 60 character
            // hash string. the PASSWORD_DEFAULT constant is defined by the PHP 5.5, or if you are using
            // PHP 5.3/5.4, by the password hashing compatibility library
            $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

			//get new rss key
			$rss_key = $this->generateRandomString();
			
            // check if user or email address already exists
            $sql = "SELECT * FROM users WHERE user_name = ? OR user_email = ? OR rss_key = ?";      
            $stmt = $this->db_connection->prepare($sql);
			$stmt->execute(array($user_name, $user_email, $rss_key));
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

			
			
			
            if (count($rows) >= 1) {
                $this->errors[] = "Sorry, that username / email address is already taken.";
            } else {
                // write new user's data into database
                $sql = "INSERT INTO users (user_name, user_password_hash, user_email, rss_key)
                        VALUES(?, ?, ?, ?);";
                $stmt = $this->db_connection->prepare($sql);
				$stmt->execute(array($user_name, $user_password_hash, $user_email, $rss_key));
                $rows = $stmt->rowCount();

                // if user has been added successfully
                if (count($rows) > 0) {
                    $this->messages[] = "Your account has been created successfully. You can now log in.";
                } else {
                    $this->errors[] = "Sorry, your registration failed. Please go back and try again.";
                }
            }
            
        } else {
            $this->errors[] = "An unknown error occurred.";
        }
    }
}
