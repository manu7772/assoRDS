<?php
/**
 * HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */

	// get the HTML
	// ob_start();
	// include(dirname(__FILE__).'/res/exemple02.php');
	// $content = ob_get_clean();
	$content = '
<style type="text/css">
<!--
p {
	margin:0px;
	padding:0px;
	color:#330;
}
table p {
	font-size:14px;
	margin:1px 0px;
	padding:0px;
	color:#000;
}
-->
</style>
<page style="font-size: 10pt">
<br />
<br />
<table style="width:100%;">
	<tr>
		<td style="width:50%;">
			<p>Emmanuel Dujardin</p>
			<p>RD 1084</p>
			<p>Pont de Préau</p>
			<p>01450 CERDON</p>
		</td>
		<td width="50%">
			<p style="font-size:1.2em;font-weight:bolder;">SINGER FRANCE</p>
			<p>qjsd lmkfjqs mldkfjq</p>
			<p>qlksjhdf kqsjdhf q</p>
			<p>75018 PARIS</p>
		</td>
	</tr>
</table>
<br />
<br />
<p>Paris, le 26 mai 2014,</p>
<br />
<p>Bonjour !!!</p>
<p>Bonjour !!!</p>
<p>Bonjour !!!</p>
<p>Bonjour !!!</p>
<br />
<p>Le monde… !!!</p>
</page>';

	// convert in PDF
	require_once(dirname(__FILE__).'/../html2pdf.class.php');
	try
	{
		$html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', array(15, 5, 15, 5));
		$html2pdf->pdf->SetDisplayMode('fullpage');
		$html2pdf->writeHTML($content, isset($_GET['vuehtml']));
		$html2pdf->Output('exemple02.pdf');
	}
	catch(HTML2PDF_exception $e) {
		echo $e;
		exit;
	}

