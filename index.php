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
$photos   = [];
$descs    = [];
$prices   = [];
$qtys     = [];


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
              $photo = $row[mysqli_fetch_field_direct($query, $i)->name];
              array_push($photos, $photo);
        }
        if ($i==5) {
              $desc = $row[mysqli_fetch_field_direct($query, $i)->name];
              array_push($descs, $desc);
        }
        if ($i==6) {
              $price = $row[mysqli_fetch_field_direct($query, $i)->name];
              array_push($prices, $price);
        }
        if ($i==7) {
              $qty = $row[mysqli_fetch_field_direct($query, $i)->name];
              array_push($qtys, $qty);
        }
	}
}
$goodies_len = sizeof($skus);
echo "<html>";
echo "<head>";
echo "<title>";
echo "eShop";
echo "</title>";
echo "<style type='text/css'>";
echo ".menu {";
echo "  position:absolute;";
echo "  left: 20px;";
echo "}";
echo ".goods {";
echo "	position:absolute;";
echo "	left: 100px;";
echo "}";
echo "</style>";
echo "</head>";
echo "<script src='order.js'></script>";
echo "<body>";
echo "<div id='logo'>logotype.png</div>";
echo "<div id='myheader'>eShop</div>";
echo "<div id='menu' class='menu'>menu";
echo "<div class='menuone'>group 1</div>";
echo "<br>";
echo "<div class='menuone'>group 2</div>";
echo "</div>";
echo "<div class='goods'>";
echo "<table border='1'>";
echo "<tr>";
echo "<td>SKU</td>";
echo "<td>item name</td>";
echo "<td>group name</td>";
echo "<td>photo</td>";
echo "<td>desc</td>";
echo "<td>price</td>";
echo "<td>qty</td>";
echo "<td>qty to buy</td>";
echo "</tr>";
for ($i=0; $i<$goodies_len; $i++) {
	echo "<tr>";

	echo "<td>";
	echo $skus[$i];
	echo "</td>";

    echo "<td>";
    echo $names[$i];
    echo "</td>";

    echo "<td>";
    echo $groups[$i];
    echo "</td>";

    echo "<td>";
    echo "<img src='images/$photos[$i]'></img>";
    echo "</td>";

    echo "<td>";
    echo $descs[$i];
    echo "</td>";

    echo "<td>";
    echo $prices[$i];
    echo "</td>";

    echo "<td>";
    echo $qtys[$i];
    echo "</td>";

    echo "<td>";
    echo "<input type='text' id='input".$skus[$i]."' name='qtyToBuy' size='5'>";
	echo "</tr>";
}
echo "</table>";
echo "<button onclick='makeOrder()'>Сформировать заказ</button>";
echo "</div>";
echo "</body>";
echo "</html>";
?>
