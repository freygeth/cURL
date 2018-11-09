<?php 
//V1 enlima.pe
include 'c.php';
$ch = curl_init();
//$link = 'http://www.enlima.pe/calendario-cultural/dia/2018-09-01'; //viene del cron como variable que varia cada dia o periodo x
$link = $argv[1];
curl_setopt($ch, CURLOPT_URL, $link);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,5);
$res = curl_exec($ch);
curl_close($ch);
preg_match_all("(<span class=\"date-display-single\">(.*)</span>)siU",$res,$r1);
preg_match_all("/<td class=\"views-field views-field-title views-align-left\"><a href=\"(.+?)\">(.+?)<\/a><\/td>/",$res,$r2);
preg_match_all("(<td class=\"views-field views-field-field-lugar\">(.*)</td>)siU",$res,$r3);
preg_match_all("(<td class=\"views-field views-field-field-precio\">(.*)</td>)siU",$res,$r4);
$qdata = count($r1[0]);
$f = strrpos($link, '/'); //ultima aparicion del "-"
$f2 = $f+1; //uno mas para usar el substr desde alli
$fecha= substr($link, $f2); 
$output0 = array();
$output1 = array();
$output2 = array();
for($i = 0; $i < $qdata; $i++){
	$hora = $r1[0][$i];
    $url = 'http://www.enlima.pe'.$r2[1][$i];
    $titulo = $r2[2][$i];
    $lugar = $r3[0][$i];
    $precio = $r4[0][$i];
	$p1 = 'GRATIS';
	$p = strrpos($precio,$p1);
	if($p === false){
		$output2[] = 2;
	}else{
		$u = strrpos($fecha, '-'); //ultima aparicion del "-"
		$u2 = $u+1; //uno mas para usar el substr desde alli
		$idi = substr($fecha, $u2);
		/*
		echo $url.'<br>';
		echo $titulo.'<br>';
		var_dump($lugar).'<br>';
		var_dump(strip_tags($lugar)).'<br>'; 
		echo $fecha.'<br>';
		echo $idi.'<br><br>';		
		*/
/*		$url = 'http://www.enlima.pe/agenda-cultural/exposicion/mirar-nuevas-formas-de-percibir-nuestro-mundo-0';
		$titulo = 'MIRAR. Nuevas formas de percibir nuestro mundo';
		$lugar = 'Espacio Fundación Telefónica';
		$fecha = '2018-09-01';
		$idi= '01'; */ // en la tabla estara 1
		$lugar = strip_tags($lugar);
		$insert = "INSERT INTO e10(title, url, local, tim, idi) VALUES(?, ?, ?, ?, ?)";
		$pre = mysqli_prepare($c,$insert);
		mysqli_stmt_bind_param($pre,"ssssi",$titulo, $url, $lugar, $fecha, $idi);
		$exce = mysqli_stmt_execute($pre);
		if($exce){
			$output1[] = 1;
			mysqli_stmt_close($pre);
		}else{
			$output0[] = 0;
			//echo mysqli_error($c);
			mysqli_stmt_close($pre);
		} 
		
	} 
}
$q0=count($output0);
$q1=count($output1);
$q2=count($output2);
$suma = $q0+$q1+$q2; 
$today = date("Ymd"); 
$file_name = "Enlima-report-".$today.".txt"; 
$content="Total: ".$qdata." eventos. Igresados:".$q1.", error sql ".$q0." ,filtrados ".$q2." Suman ".$suma;
$fo = fopen($file_name, "w");
fwrite($fo, $content);
fclose($fo);
mysqli_close($c);
?>