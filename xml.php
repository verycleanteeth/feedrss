<?PHP
//Takes info from database and displays it as rss feed

//error_reporting(E_ALL & ~E_NOTICE);

//interpret as xml
header("Content-Type: application/xml; charset=ISO-8859-1");



require_once('config.php');
require_once('classes/db.class.php');

$db = new DB();

//get user ID from obfuscated url
$id = $_GET['id'];
$id = $db->decode($id, 'dasfa089jva');


?>
<?xml version="1.0"?>
<rss version="2.0">
	<channel>
		<title>Feed RSS</title>
		<description>Cool news for cool dudes</description>
		<link>http://www.egobomb.com/xml.php?id=<?PHP echo $id; ?></link>
		
<?PHP

//get items from database
$query = $db->connection->prepare("SELECT title, link, description FROM items WHERE user = :id ORDER BY time DESC");
$query->execute(array(':id' => $id));
$items = $query->fetchAll(PDO::FETCH_ASSOC);

//display
foreach ($items AS $item){

	echo '
	<item>
		<title>'.xml_entities($item['title']).'</title>
		<description>'.xml_entities($item['description']).'</description>
		<link>'.xml_entities($item['link']).'</link>
    </item>		
	';
}
?>
	</channel>
</rss>