<?PHP
//Configuration for: Database Connection
define("DB_HOST", "localhost");
define("DB_NAME", "");
define("DB_USER", "");
define("DB_PASS", "");

//replace special characters
function xml_entities($string) {
    return strtr(
        $string, 
        array(
            "<" => "&lt;",
            ">" => "&gt;",
            '"' => "&quot;",
            "'" => "&apos;",
            "&" => "&amp;",
        )
    );
}

?>