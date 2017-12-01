<?php
$xss = array("'","#","+","\"","?","*","&&","^","~","`","=","\\","$");

$nombre = $_POST['nombre'];
$nombre = str_replace($xss,'', $nombre);

$apellido = $_POST['apellido'];
$apellido = str_replace($xss,'', $apellido);



$tamano_a = $_FILES['file-pdf']['size'];
$tamano_b = $_FILES['file-xml']['size'];


$nombre_pdf = $_FILES['file-pdf']['name'];
$nombre_pdf = str_replace($xss,'', $nombre_pdf);

$nombre_xml = $_FILES['file-xml']['name'];
$nombre_xml = str_replace($xss,'', $nombre_xml);


echo "Nombre: ", $nombre_pdf,"<br>";
echo "Nombre Temp: ", $_FILES['file-pdf']['tmp_name'],"<br>";
echo "Tamaño: ", $_FILES['file-pdf']['size']/1024000," MB <br>","<br>";

echo "Nombre: ", $nombre_xml,"<br>";
echo "Nombre Temp: ", $_FILES['file-xml']['tmp_name'],"<br>";
echo "Tamaño: ", $_FILES['file-xml']['size']/1024000," MB <br>","<br>";

echo "Nombre: ",$nombre,"<br>Apellido: ",$apellido;


if ($tamano_a < 5000000 AND $tamano_b < 5000000) {


$ruta = "tmp_folder/";
if (opendir($ruta) == TRUE) {
	# code...


$destino = $ruta.$_FILES['file-pdf']['name'];
move_uploaded_file($_FILES['file-pdf']['tmp_name'],$destino);


$destino = $ruta.$_FILES['file-xml']['name'];
move_uploaded_file($_FILES['file-xml']['tmp_name'],$destino);
#echo "<br>Archivo: ";


#echo $nombre, "<br>Subido a la carpeta: ", $ruta;
#echo "<br><embed src='".$ruta.$nombre."' width='500' height='375'>";

#echo "<br>Archivo: ";

#echo $nombre, "<br>XML subido a la carpeta: ", $ruta;

}
}
else{
	echo "tamaño excedido";
}

?>