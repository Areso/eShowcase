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

$query = mysqli_query($conn, $query_line) or die("Query error while checking goods: ".mysqli_connect_error($conn).mysqli_errno($conn).mysqli_error($conn));
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

$query_line_cat = "select * from groups";
$query_cat = mysqli_query($conn, $query_line_cat) or die("Query error while checking categories: ".mysqli_connect_error($conn).mysqli_errno($conn).mysqli_error($conn));
$field_cat = mysqli_field_count($conn);

$id_groups      = [];
$name_groups    = [];

while($row = mysqli_fetch_array($query)) {
    for($i = 0; $i < $field; $i++) {
        if ($i==0) {
            $id_group = $row[mysqli_fetch_field_direct($query, $i)->name];
            array_push($id_groups, $id_group);
        }
        if ($i==1) {
            $name_group = $row[mysqli_fetch_field_direct($query, $i)->name];
            array_push($name_groups, $name_group);
        }
	}
}
$cat_len = sizeof($id_group);

echo "<html>";
echo "<head>";
echo "<title>";
echo "eShop";
echo "</title>";
echo "<meta charset='UTF-8'>";
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
//echo "<script src='order.js'></script>";
echo "<body>";
//echo "<div id='logo'>logotype.png</div>";
//echo "<div id='myheader'>eShop</div>";
//echo "<div id='menu' class='menu'>menu";
//echo "<div class='menuone'>group 1</div>";
//echo "<br>";
//echo "<div class='menuone'>group 2</div>";
//echo "</div>";

echo "<div class='tab'>";
echo "<button class='tablinks' onclick='openTab(event,'Main')'    id='tabCity'   >Main   </button>";
echo "<button class='tablinks' onclick='openTab(event,'Explore')' id='tabExplore'>Explore</button>";
echo "</div>";
echo "<div id='Main' class='tabcontent'>";
echo "</div>";
echo "<div id='Explore' class='tabcontent'>";
echo "</div>";
echo "<div class='goods'>";
echo "<table border='1'>";
echo "<tr>";
echo "<td>SKU</td>";
echo "<td>Название</td>";
echo "<td>Код производителя</td>";
echo "<td>Фото</td>";
echo "<td>Описание</td>";
echo "<td>Цена</td>";
echo "<td>Количество</td>";
echo "<td>Кол-во к покупке</td>";
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
    echo "<input type='text' id='input".$skus[$i]."' name='qtyToBuy' size='5' value='0'>";
	echo "</tr>";
}
echo "</table>";
echo "<button onclick='makeOrder()'>Сформировать заказ</button>";
echo "<br>";
echo "<br>";
echo "ИП Пермина К.И. ИНН 667116868763 ОГРНИП 315665800056070";
echo "</div>";
echo "</body>";
echo "</html>";
?>
