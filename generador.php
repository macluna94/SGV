<?php 
ob_start();
require_once "tabla.php";


	$content = ob_get_clean();
	require __DIR__.'/vendor/autoload.php';
	use Spipu\Html2Pdf\Html2Pdf;

	try{
		$html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8');
		$html2pdf->pdf->SetDisplayMode('fullpage');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output('SGV.pdf');
	}
	catch(HTML2PDF_exception $e) {
	echo $e;
	exit;
	}
 ?>