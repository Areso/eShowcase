<?php
	include('site-backend/lock.php');
	$myAuth = include 'site-backend/menualterer.php';
	$myVers = include 'site-backend/versionsite.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title></title>
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<style type="text/css">
		.top-menu {
			text-align: right;
		}
		.header {
			width: 100%;
			height: 50px;
		}
		.footer {
			width: 100%;
			height: 50px;
			bottom: 0px;
    		position: fixed;
		}
		.link {
			padding: 10px;
		}
		#lbl-site-name {
			color: #0373B2;
		}
		 /* unvisited link */
		a:link {
		    color: blue;
		}
		/* visited link */
		a:visited {
		    color: blue;
		}
		/* mouse over link */
		a:hover {
		    color: blue;
		}
		.copyright {
			text-align: center;
		}
		.center {
			text-align: center;	
		}
	</style>
</head>
<body>
	<table id="header" border="0" width="100%" class="header">
		<tr>
			<td id="indent-left" width="5%"></td>
			<td id="lbl-site-name" width="40%"><h1><?php echo $sitename ?></h1></td>
			<td id="top-menu" class="top-menu" width="50%">
				<a href="index.php" accesskey="1" title="Главная" class="link">Главная</a>
				<?php 
				  if ($auth == 0) {
					  echo '<a href="login.php" accesskey="3" title="Вход/регистрация" class="link">Вход/регистрация</a>';
				  } else {
					  echo '<a href="goods.php" accesskey="3" title="Товары" class="link">Товары</a>';
					  echo '<a href="settings.php" accesskey="4" title="Настройки" class="link">Настройки</a>';
					  echo '<a href="site-backend/logout.php" accesskey="5" title="Выход" class="link">Выход</a>';
				  }
				?>
				<a href="contacts.php" accesskey="2" title="Контакты" class="link">Контакты</a>
			</td>
			<td id="indent-right" width="5%"></td>
		</tr>
	</table>

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

$query_line_cat = "select * from groups";
$query_cat = mysqli_query($conn, $query_line_cat) or die("Query error while checking categories: ".mysqli_connect_error($conn).mysqli_errno($conn).mysqli_error($conn));
$field_cat = mysqli_field_count($conn);
$id_groups      = [];
$name_groups    = [];

while($row = mysqli_fetch_array($query_cat)) {
    for($i = 0; $i < $field_cat; $i++) {
		
        if ($i==0) {
            $id_group = $row[mysqli_fetch_field_direct($query_cat, $i)->name];
            array_push($id_groups, $id_group);
        }
        if ($i==1) {
            $name_group = $row[mysqli_fetch_field_direct($query_cat, $i)->name];
            array_push($name_groups, $name_group);
        }
	}
}
$cat_len = sizeof($id_groups);
//echo "the size of categories is ".$cat_len;

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
echo "<script src='order.js'></script>";
echo "<body>";
echo "<div id='page'>";
//echo "<div id='logo'>logotype.png</div>";
//echo "<div id='myheader'>eShop</div>";


echo "<div class='tab'>";
echo "<button id='tabCity'      class='tablinks' onclick=\"openTab(event, 'Main')\"       >Главная</button>";
for($i = 0; $i < $cat_len; $i++) {
	$newCat = $i+1;

	$query_line_show = "select * from goods where id_group = $newCat";
	$query_show = mysqli_query($conn, $query_line_show) or die("Query error while checking number of goods in categories: ".mysqli_connect_error($conn).mysqli_errno($conn).mysqli_error($conn));
	if (mysqli_num_rows($query_show)>0) {
		echo "<button id='tabCategory$newCat' class='tablinks' onclick=\"openTab(event, 'Category$newCat')\"  >$name_groups[$i]</button>";
	}
}
echo "</div>";
echo "<div id='Main' class='tabcontent'>";
echo "Пожалуйста, выберите категорию товаров в меню сверху!<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "По всем вопросам пишите на <a href='mailto:info@discont.org'>info@discont.org</a>";
//echo "ИП Пермина К.И. ИНН 667116868763 ОГРНИП 315665800056070";
echo "</div>";

for($y = 0; $y < $cat_len+1; $y++) {
	//echo "current category is $y <br>";
	$query_line = "SELECT
	 *
	 FROM goods
	 WHERE id_group = $y";

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
	//echo "goods number in category $y is ".$goodies_len."<br>";
	if ($goodies_len > 0) { 
		echo "<div id='Category$y' class='tabcontent'>";
		echo "<div class='goods'>";
			echo "<table border='1'>";
			echo "<tr>";
			echo "<td>Артикул</td>";
			echo "<td>Название</td>";
			echo "<td>Фото</td>";
			echo "<td>Описание</td>";
			echo "<td>Цена</td>";
			echo "<td>Количество</td>";
			echo "<td>Кол-во к покупке</td>";
			echo "</tr>";
			for ($z=0; $z<$goodies_len; $z++) {
				echo "<tr>";

				echo "<td>";
				echo $skus[$z];
				echo "</td>";

				echo "<td>";
				echo $names[$z];
				echo "</td>";

				echo "<td>";
				echo "<img src='images/$groups[$y]/$photos[$z]'></img>";
				echo "</td>";

				echo "<td>";
				echo $descs[$z];
				echo "</td>";

				echo "<td>";
				echo $prices[$z];
				echo "</td>";

				echo "<td>";
				echo $qtys[$z];
				echo "</td>";

				echo "<td>";
				echo "<input type='text' id='input".$skus[$z]."' name='qtyToBuy' size='5' value='0'>";
				echo "</tr>";
			}
			echo "</table>";
		echo "<button onclick='makeOrder()'>Сформировать заказ</button>";
		echo "<br>";
		echo "<br>";
		// file_exists ( string $filename ) : bool
		echo "<a href='images/$groups[$y]/promo.zip'>скачать промоматериалы</a>";
		echo "<br>";
		echo "<br>";
		echo "<br>";
		//echo "ИП Пермина К.И. ИНН 667116868763 ОГРНИП 315665800056070";
		echo "По всем вопросам пишите на <a href='mailto:info@discont.org'>info@discont.org</a>";
		echo "</div>";
		echo "</div>";
	} else {
		//!
	}
}
echo "</div>";
echo "</body>";
echo "</html>";
?>
