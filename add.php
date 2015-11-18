<!DOCTYPE html>
<head>
	<title>Feed RSS</title>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
</head>
<body>

<?PHP
require('config.php');	

$db = new DB;

//submit a link for processing
if ($_POST['submit'] AND $_POST['link']){

	
	//extract info from link
	$content = file_get_contents($_POST['link']);
	
	$title = explode('<title>', $content);
	$title = explode('</title>', $title[1]);
	$title = $title[0];
	$description = "";
	$link = $_POST['link'];
        
    //add to feed
	$xml = '
		<item>
			<title>'.xml_entities($title).'</title>
			<description>'.xml_entities($description).'</description>
			<link>'.xml_entities($link).'</link>
	    </item>
    ';		
    
    if ($title AND $link){

 		$query = $db->connection->prepare("
    		INSERT INTO items (user, time, title, description, link)
            VALUES(:user, :time, :title, :description, :link);
        ")->execute(array(
 			':user' => $_SESSION['user_id'],
 			':time' => time(),
 			':title' => $title,
 			':description' => $description,
 			':link' => $link
 		));
 		
 		echo "<p>Added <b>$title</b></p>";
    }
    else{
    	echo '
    	<p class="error">
    		Could not get retrieve link. <br><br>
    		'.print_r($content, true).'
    	</p>';
    }
}

$code = $db->encode($user_id, 'dasfa089jva');
?>

<form method="post">
	<input type="text" name="link">
	<input type="submit" name="submit" value="submit">
</form>
<?PHP
	echo "<p>Your XML File: <div class=\"xmllink\">http://egobomb.com/rss/xml/$code</div></p>";
?>
</body>
</html>