<?php
if (is_file('config.php')) {
        require_once('config.php');
}
//echo 'result is '.$DB_USERNAME;
//$subnet = getenv("REMOTE_ADDR");!!!!!!!!!
//set charset
ini_set("default_charset",'utf-8');//utf-8 windows-1251
ini_set('display_errors', 1);
error_reporting('E_ALL & E_STRICT');

$conn = mysqli_connect($DB_HOSTNAME, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE, $DB_PORT);

if (mysqli_connect_errno()) {
    die("Failed to connect to MySQL: " . mysqli_connect_error());
}
//echo $conn;
/* change character set to utf8 */

if (!mysqli_set_charset($conn, "utf8")) {
    //printf("Error loading character set utf8: %s\n", mysqli_error($conn));
    exit();
} else {
    //printf("Current character set: %s\n", mysqli_character_set_name($conn));
}

$query_line = "SELECT
 *
 FROM goods
 WHERE id_group = 1";

$query = mysqli_query($conn, $query_line) or die("Query error while checking EMAIL: ".mysqli_connect_error($conn).mysqli_errno($conn).mysqli_error($conn));
$field = mysqli_field_count($conn);

$skus     = [];
$names    = [];
$groups   = [];
while($row = mysqli_fetch_array($query)) {
	for($i = 0; $i < $field; $i++) {
		if ($i==1) {
			$sku = $row[mysqli_fetch_field_direct($query, $i)->name];
			array_push($skus, $sku);
		}
		if ($i==2) {
                        $item_name = $row[mysqli_fetch_field_direct($query, $i)->name];
                        array_push($names, $item_name);
                }
                if ($i==3) {
                        $group_name = $row[mysqli_fetch_field_direct($query, $i)->name];
                        array_push($groups, $group_name);
                }
                if ($i==4) {
                        $group_name = $row[mysqli_fetch_field_direct($query, $i)->name];
                        array_push($groups, $group_name);
                }
                if ($i==5) {
                        $group_name = $row[mysqli_fetch_field_direct($query, $i)->name];
                        array_push($groups, $group_name);
                }
                if ($i==6) {
                        $group_name = $row[mysqli_fetch_field_direct($query, $i)->name];
                        array_push($groups, $group_name);
                }
                if ($i==7) {
                        $group_name = $row[mysqli_fetch_field_direct($query, $i)->name];
                        array_push($groups, $group_name);
                }



	}
}


echo "<html>";
echo "<head>";
echo "<title>";
echo "eShop";
echo "</title>";
echo "<styles>";
echo "</styles>";
echo "</head>";
echo "<body>";
echo "logotype.png";
echo "eShop";
echo "menu";
echo "group 1";
echo "group 2";

?>
