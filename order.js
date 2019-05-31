 function makeOrder() {
        var inputs, index;
        sending_data = [];
        inputs = document.getElementsByTagName('input');
        for (index = 0; index < inputs.length; ++index) {
                // deal with inputs[index] element.
                sku = inputs[index].id;
                qty = inputs[index].value;
                if (qty !=='0') {
                    sku = sku.replace('input', '');
                    order_line= 'ordered sku is '+sku+' , ordered number is '+qty+' ;';
                    console.log(order_line);
                    sending_data.push(order_line);
                }
        }
        if (sending_data.length > 0) {
            email = prompt('please type your email');
            phone = prompt('please type your phone number');
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
            }
        };
        xhttp.open("POST", "write_to_db.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send('dataToParse='+sending_data);
    }