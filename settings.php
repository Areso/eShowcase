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
		<table width="100%">
	    <tr>
		<td width="10%"></td>
		<td width="90%">
		   <h2>Профиль</h2>
		   </br>
		   </br>
		   Как к вам обращаться?</br>
		   <input id="fullname" name="fullname" type="text"><label id="fullnameempty" style="visibility: hidden"> заполните поле!</label></br>
		   </br>
		   Номер телефона</br>
		   <input id="phonenumber" name="phonenumber" type="text"><label id="phonenumberempty" style="visibility: hidden"> заполните поле!</label></br>
		   </br>
		   <table>
			   <tr><td>Аккаунт: </td></tr>
			   <tr id="nonblocked"><td>прошел верификацию</td></tr>
			   <tr id="blocked"><td>не прошел верификацию</td></tr>
		   </table>
		   </br>
		   <input type="button" value="Сохранить данные" onclick="saveCompany()"></br>
		   <div id="lblResultAddingNegative" style="visibility: hidden">Профиль сохранить не удалось. Проверьте поля на ошибки.</div>
		   <div id="lblResultAddingPositive" style="visibility: hidden">Профиль сохранен успешно!</div>
		</td>
		</tr>
		</table>
	<div class="footer" style="display:none">
		<!--
		<div class="copyright">
	
		</div>-->
	</div>
</body>
	<script>
		stringtosend='';
			window.onload = load;
			function load() {
				loadDoc('site-backend/loadsettings.php', afterload);
			}
			function showUnsuccessAdd () {
				document.getElementById("lblResultAddingNegative").setAttribute("style", "visibility: hidden");
				if (typeof timerResultAddingObject !== 'undefined') {
					clearInterval(timerResultAddingObject);
				}
			}
			function showSuccessAdd () {
				document.getElementById("lblResultAddingPositive").setAttribute("style", "visibility: hidden");
				if (typeof timerResultAddingObject !== 'undefined') {
					clearInterval(timerResultAddingObject);
				}
			}
			function saveCompany () {
				fullname       = document.getElementById("fullname").value;   //required
				phonenumber    = document.getElementById("phonenumber").value;   //required
				
				isfilled      = true;
				if (fullname     === "") {
					isfilled = false;
					document.getElementById("fullnameempty").style = "";
				} else {
					document.getElementById("fullnameempty").style = "visibility: hidden";
				}
				if (phonenumber     === "") {
					isfilled = false;
					document.getElementById("phonenumberempty").style = "";
				} else {
					document.getElementById("phonenumberempty").style = "visibility: hidden";
				}
				
				if (isfilled === false) {
					document.getElementById("lblResultAddingNegative").setAttribute("style", "");
					timerResultAddingObject = setInterval(showUnsuccessAdd, 3000);
				}
				if (isfilled === true) {
					stringtosend = "fullname="+fullname+"&phonenumber="+phonenumber;
					console.log("sending to php following string "+stringtosend);
					loadDoc('site-backend/savesettings.php', myFunction);
				}
			}
			function loadDoc(url, cFunction) {
			  var xhttp;
			  xhttp=new XMLHttpRequest();
			  xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
				  cFunction(this);
				}
			  };
			  xhttp.open("POST", url, true);
			  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			  console.log(stringtosend);
			  xhttp.send(stringtosend); 
			}
			function myFunction(xhttp) {
			  //document.getElementById("demo").innerHTML = xhttp.responseText;
			  console.log("some response");
			  console.log(xhttp.responseText);
			  if (xhttp.responseText==="OK") {
				    document.getElementById("lblResultAddingPositive").setAttribute("style", "");
					//todo check whether setTimeout is better for here
					autosaveObject = setInterval(showSuccessAdd, 3000);
			  }
			}
			//TODO
			function afterload(xhttp) {
			  //document.getElementById("demo").innerHTML = xhttp.responseText;
			  console.log("some response");
			  console.log(xhttp.responseText);
              stringtoparse = xhttp.responseText;
			  params        = [];
			  myvalues      = [];
			  paramvalue    = 0; 
			  isend         = 0;
			  //while (stringtoparse!=="") {
			  while (paramvalue < 28) {	  
				 if (paramvalue % 2 === 0) {
					  stringstart    = stringtoparse.indexOf("&");
					  stringend      = stringtoparse.indexOf("=");
					  paramname      = stringtoparse.slice(stringstart+1, stringend);
					  deletetext     = stringtoparse.slice(stringstart, stringend+1);
					  params.push(paramname);
					  stringtoparse  = stringtoparse.substring(stringend+1);
					  /*
					  if (paramvalue >= 24) {
					    console.log("paramname is "+paramname);
					    console.log(stringtoparse);
					  }*/
				  }
				  if (paramvalue % 2 === 1) {
					  stringstart    = 0;
					  stringend      = stringtoparse.indexOf("&");
					  myvalue        = stringtoparse.slice(stringstart, stringend);
					  deletetext     = stringtoparse.slice(stringstart, stringend);
					  if (stringend == -1) {
						  myvalue = stringtoparse;
				      }
					  //console.log("stringtoparse"+stringtoparse);
					  //console.log("value is "+myvalue);
     				  myvalues.push(myvalue);
					  stringtoparse  = stringtoparse.substring(stringend);
				  }
				  paramvalue    =  paramvalue+1;
			  }
			  document.getElementById("fullname").value         = myvalues[0];
			  document.getElementById("phonenumber").value      = myvalues[1];
			  
			  console.log("myvalues[2] is "+parseInt(myvalues[2])+" and logic value is ");
			  console.log(parseInt(myvalues[2])===1);
			  if (parseInt(myvalues[2])===1) {
				document.getElementById("blocked").style.visibility = "visible";
				document.getElementById("nonblocked").style.visibility = "collapse";
			  } else { 
				document.getElementById("blocked").style.visibility = "collapse";
				document.getElementById("nonblocked").style.visibility = "visible";  
			  }
			}
	</script>
</html>
