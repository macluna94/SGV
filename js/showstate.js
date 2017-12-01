$(document).ready(function(){

	function showState(state_select){
	if (state_select == "") {
        document.getElementById("selectEstado").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            newxmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            newxmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        newxmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("selectEstado").innerHTML = this.responseText;
            }
        };
        newxmlhttp.open("GET","php/estados.php?estado="+state_select,true);
        newxmlhttp.send();
    }
console.log("List");
}
}); 