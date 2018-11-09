<?php 
//v1
//EventBrite
//https://www.eventbrite.es/d/peru--lima/free--events--this-month/?crt=regular&sort=best
//https://www.eventbrite.es/d/peru--lima/free--events--next-month/?crt=regular&page=1&sort=best
//wget: wget -O mayo.html url //ojo debe ser automatico con Cron en futuro o ahora,depende si es complicado implementarlo
/*    (link similar con "&page=1". tambien hay page=2,etc.
https://www.eventbrite.es/d/peru--lima/free--events--this-month/?crt=regular&page=1&sort=best) PARECE QUE EL JSON O LOS JSONS CONTIENEN TAMBIEN ESES EVENTO, LO BUSQUE DESDE EL NAVEGADOR Y ESTAN */
//$file = '/home/jose/wget/mayo.html';
include 'c.php';
//$file = '/home/jose/shell/091818.html'; //variable de shell
$file = $argv[1];
$base=file_get_contents($file);
//$regexp = "<script\s[^>]*type=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/script>";
//preg_match_all("(<script type=\"application/ld+json\">(.*?)</script>)siU",$base,$data);
//var_dump($data); 
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($base);
libxml_use_internal_errors(false);
$xpath = new DOMXPath($dom);
$script = $xpath->query('//script[@type="application/ld+json"]');
$data = $script->item(0)->nodeValue;
//echo $data;
$json = json_decode($data,true);
$q=count($json); //cantidad de elemntos:20(0-19)
//echo $q;
$output0 = array();
$output1 = array();
$output2 = array();
for($i=0;$i<$q;$i++){
	$fecha = $json[$i]['startDate'];
	$titulo = $json[$i]['name'];
	$url = $json[$i]['url'];
	$precio = $json[$i]['offers']['lowPrice'];
	$lugar = $json[$i]['location']['address']['addressLocality'];	
	if($precio == 0.00){
		$u = strrpos($fecha, '-'); //ultima aparicion del "-"
		$u2 = $u+1; //uno mas para usar el substr desde alli
		$idi = substr($fecha, $u2); //extra la cadena desde $u2 hasta el final		
		/*
		echo $fecha.'<br>';
		echo $titulo.'<br>';
		echo $url.'<br>';
		//echo $precio.'<br>';
		echo $lugar.'<br>';
		echo $idi.'<br>'; 
		*/	
		//OJO PARECE QUE EVENTBRITE PERMITE DEJAR VACIO EL ESPACIO DE LUGAR ENTONCES EN DATABASE LO PUSE POR DEFECTO VACIO TBN
		//USANDO: DEFAULT: AS DEFINED: LO DEJE VACIO (poniendo null tbn funciona ver si al hacer query con python es indiferente 
		//si da lo mismo usar una de esas formas usar null mejor )
		$query = "INSERT INTO e10(title, url, local, tim, idi) VALUES(?, ?, ?, ?, ?)";
		$p = mysqli_prepare($c,$query);		
		mysqli_stmt_bind_param($p,"ssssi",$titulo, $url, $lugar, $fecha, $idi);
		$b = mysqli_stmt_execute($p);	
			if($b){
					$output1[] = 1;
				  	mysqli_stmt_close($p);
			}else{
					$output0[] = 0;
				  	mysqli_stmt_close($p);
			} 
				
	}else{
		$output2[] = 2;
	}
}
$q0=count($output0);
$q1=count($output1);
$q2=count($output2);
$suma = $q0+$q1+$q2; 
$today = date("Ymd"); 
$file_name = "Eventbrite-report-".$today.".txt"; 
$content="Total: ".$q." eventos. Igresados:".$q1.", error sql ".$q0." ,filtrados ".$q2." Suman ".$suma;
$fo = fopen($file_name, "w");
fwrite($fo, $content);
fclose($fo);
mysqli_close($c);
/*
//echo $data[0][8]; //da vacio, nose xq use 8. el codigo final si da resultado deseado
$json= strip_tags($data[0][8]);
//echo $json; 
$evento=json_decode($json,true);
$q=count($evento);
for($i=0;$i<$q;$i++){
	$dia = $evento[$i]['startDate'];
	$ext0 = substr($dia,11,-1);	
	$fecha = substr($dia,0, -10); 
	$titulo = $evento[$i]['name'];
	$url = $evento[$i]['url'];
	$precio = $evento[$i]['offers']['lowPrice'];
	$lugar = $evento[$i]['location']['name'];
	if($precio == 0.00){						
						$hora = date("g:i a", strtotime("-5 hour", strtotime($ext0)));
						echo $hora.'<br>';										
						echo $fecha.'<br>';
						echo $titulo.'<br>';
						echo $url.'<br>';
						echo $lugar.'<br>';	
	}else{
		echo "no aplica";//mejorar esto
	}
}
*/

?>