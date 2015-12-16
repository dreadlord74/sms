var request = new ActiveXObject("Msxml2.XMLHTTP.3.0"); 
var url = "http://localhost/sms/model/classes/auto.php";
request.open("GET", url);
request.send(null);
WScript.Sleep(2000); // чтобы скрипт не завершился, прежде чем запрос уйдет в сеть