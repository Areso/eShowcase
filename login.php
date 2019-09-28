<?php
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
	<script>
		function clearText() {
			document.getElementById("InputEmail").value="";
		}
	</script>
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

	<table border="0" width="100%">
		<tr>
			<td width="20%"></td>
			<td width="70%">
				Вход / Регистрация<br><br>
				<form id="login_form" action="site-backend/login_register.php" method="post">
				email:<br>
				<input id="InputEmail" type="text" onclick="clearText()" name="email" value="example@google.com">	<br>
				Пароль:<br>
				<input type="password" name="password" class="ym-disable-keys" value=""><br>
				<input type="checkbox" name="tos_accepted"><a href="terms_of_service.php">Прочитано и принято Соглашение о сервисе</a><br><br>
				<input type="submit" value="Войти / зарегистрироваться"><br>
				</form> 
			</td>
			<td width="10%"></td>
		</tr>
	</table>
	<div class="footer">
		<div class="copyright">
			&copy; <?php echo $siteowner ?>, <?php echo $sitefound ?>-<?php echo date("Y") ?> | <a href="changelog.php"><?php echo $myVers ?></a>
		</div>
	</div>
</body>
</html>
