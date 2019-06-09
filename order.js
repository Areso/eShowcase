function makeOrder() {
        var inputs, index;
        sending_data = [];
        inputs = document.getElementsByTagName('input');
        for (index = 0; index < inputs.length; ++index) {
                // deal with inputs[index] element.
                sku = inputs[index].id;
                qty = inputs[index].value;
                if (qty !=='0' && qty !== '' && qty>0) {
                    sku = sku.replace('input', '');
                    order_line= 'ordered sku is '+sku+' , ordered number is '+qty+' ;';
                    console.log(order_line);
                    sending_data.push(order_line);
                }
        }
        if (sending_data.length > 0) {
			customer = prompt('Пожалуйста, ваше ФИО');
            email    = prompt('Пожалуйста, введите ваш email');
            phone    = prompt('Пожалуйста, введите ваш номер телефона');
            sending_data.push(customer);
            sending_data.push(email);
            sending_data.push(phone);
            sendOrder();
        }
}
function sendOrder() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                server_response = this.responseText;
                console.log(server_response);
                if (server_response==='success') {
                    alert('Ваш заказ успешно отравлен в магазин');
                }
            }
        };
        xhttp.open("POST", "write_to_db.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send('dataToParse='+sending_data);
}
function openTab(evt, tabName) {
		var i, tabcontent, tablinks;
		tabcontent = document.getElementsByClassName("tabcontent");
		for (i = 0; i < tabcontent.length; i++) {
			tabcontent[i].style.display = "none";
		}
		tablinks = document.getElementsByClassName("tablinks");
		for (i = 0; i < tablinks.length; i++) {
			tablinks[i].className = tablinks[i].className.replace(" active", "");
		}
		document.getElementById(tabName).style.display = "block";
		evt.currentTarget.className += " active";
		console.log("opentab");
}
function bodyOnloadHandler() {
    console.log("body onload");
    document.getElementById("tabCity").click();
}

document.onload = function(e) {
	bodyOnloadHandler();
    console.log("document loaded");
};

window.onload = function(e) {
	bodyOnloadHandler();
    console.log("window loaded");
};
