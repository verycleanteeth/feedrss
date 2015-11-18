<?PHP
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");

require_once('config.php');
require_once('classes/db.class.php');
require_once('classes/login.class.php');


$db = new DB();
$login = new Login($db->connection);

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Feed RSS</title>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="style.css">

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="bootstrap/css/jumbotron-narrow.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
<body>
<div class="container">
	<div class="header" style="text-align:center"><h3><font color=white>Feed RSS</font></h3>
		<?PHP
			
			// ... ask if we are logged in here:
			if ($login->isUserLoggedIn() == true) {
				$user_id = $_SESSION['user_id'];
			    echo '<div class="login">';
			    include("views/logged_in.php");
			    echo '</div>';
			}
			
		?>
	
	</div>
	<div class="jumbotron">
<?PHP

	if ($login->isUserLoggedIn() == true) {
		include('add.php');
	}
	elseif ($_GET['do'] == 'register'){
		
		require_once("classes/register.class.php");
		$register = new Register($db->connection);
		include("views/register.php");	
	}
	else{
		include("views/not_logged_in.php");
	}

	
?>
	</div>
	<div class="footer"></div>
</div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>