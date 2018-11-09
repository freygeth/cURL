<?php 
//
//Lima-Agenda Cultura
//http://www.enlima.pe/calendario-cultural/dia/2018-05-01
//Cron que cada dia reejecute y guarde datos
include 'c.php';
$ch = curl_init();
$link = 'http://www.enlima.pe/calendario-cultural/dia/2018-09-01'; //viene del cron como variable que varia cada dia o periodo x
curl_setopt($ch, CURLOPT_URL, $link );
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,5);
$res = curl_exec($ch);
curl_close($ch);
preg_match_all("(<span class=\"date-display-single\">(.*)</span>)siU",$res,$r1);
preg_match_all("/<td class=\"views-field views-field-title views-align-left\"><a href=\"(.+?)\">(.+?)<\/a><\/td>/",$res,$r2);
preg_match_all("(<td class=\"views-field views-field-field-lugar\">(.*)</td>)siU",$res,$r3);
preg_match_all("(<td class=\"views-field views-field-field-precio\">(.*)</td>)siU",$res,$r4);
$qdata = count($r1[0]);
//echo $qdata;
$f = strrpos($link, '/'); //ultima aparicion del "-"
$f2 = $f+1; //uno mas para usar el substr desde alli
$fecha= substr($link, $f2); //extra la cadena desde $u2 hasta el final	
//$fecha = '2018-09-01';
for($i = 0; $i < $qdata ;$i++){	
	$hora = $r1[0][$i];
    $url = 'http://www.enlima.pe'.$r2[1][$i];
    $titulo = $r2[2][$i];
    $lugar = $r3[0][$i];
    $precio = $r4[0][$i];
	$p1 = 'GRATIS';
	$p = strrpos($precio,$p1);
	if($p === false){
		echo 'no es gratis'.'<br>';
	}else{
		$u = strrpos($fecha, '-'); //ultima aparicion del "-"
		$u2 = $u+1; //uno mas para usar el substr desde alli
		$idi = substr($fecha, $u2); //extra la cadena desde $u2 hasta el final	
		//echo $hora.'<br>';
		/*
		echo $url.'<br>';
		echo $titulo.'<br>';
		echo $lugar.'<br>';
		echo $fecha.'<br>';
		echo $idi.'<br><br>';
		*/
		$q = "INSERT INTO `09`(title, url, local, tim, idi) VALUES(?, ?, ?, ?, ?)";
		$pre = mysqli_prepare($c,$q);
		if($pre){
				if(mysqli_stmt_bind_param($pre,"ssssi",$titulo, $url, $lugar, $fecha, $idi)){

					$b = mysqli_stmt_execute($pre);	
					if($b){
							echo '1'.'<br>';
						  	mysqli_stmt_close($pre);
					}else{
							echo '0'.'<br>';
						  	mysqli_stmt_close($pre);
					}
				}else{
					echo "no entro bind".'<br>';
				}				
		}else{
			echo "no entra pre".'<br>';
		}
				
	}
	  
}
mysqli_close($c);

?>