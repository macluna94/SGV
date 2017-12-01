$(document).ready(function(){

	function showPrograms(hub){
    	if (hub != "Docencia") {
            document.getElementById("selectPrograma").innerHTML = "";
            return;
        } else { 
            if (window.XMLHttpRequest) {
                newxhttp = new XMLHttpRequest();
            } else {
                newxhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            newxhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("selectPrograma").innerHTML = this.responseText;
                }
            };
            newxhttp.open("GET","php/programas.php",true);
            newxhttp.send();
        }
    }
});


 


