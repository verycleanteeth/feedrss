<?PHP

class DB{
	
	public $connection = null;
	public $errors = array();

	public function __construct(){
		
		//begin session
		session_start();
		
		//create connection using info from config.php
		$this->connection = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
	}
	
		
	public function encode($txt, $salt=''){
		return urlencode(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5('df9f7ha9e'.$salt), $txt, MCRYPT_MODE_CBC, md5(md5('df9f7ha9e'.$salt)))));
	}
	
	public function decode($txt, $salt=''){
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5('df9f7ha9e'.$salt), base64_decode($txt), MCRYPT_MODE_CBC, md5(md5('df9f7ha9e'.$salt))), "\0");   
	}
}



?>