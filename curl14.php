<?php 
//pendiente: https://www.formate.pe/conferencias
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.formate.pe/conferencias");
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,5);
$res = curl_exec($ch);
curl_close($ch);
//preg_match_all("(<article class=\"post\">(.*)</article>)siU",$res,$r1);
/*
preg_match_all("(<article class=\"post\">(.*)</article>)siU",$res,$r1);
$qdata = count($r1[0]); //cantidad elementos
for($i = 0; $i < $qdata; $i++){
	echo $r1[0][$i].'<br>';
}
*/
//este don me extrae el primer elemento item(0) del total, debo hacer un loop para extraer todos y aparte filtrar solo los que necesito
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($res);
libxml_use_internal_errors(false);
$xpath = new DOMXPath($dom);
$div = $xpath->query('//article[@class="post"]');
$div = $div->item(0);
$result = $dom->saveXML($div);
echo $result;
/*
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($res);
libxml_use_internal_errors(false);
foreach($dom->query('article[class=post]') as $data) {
        echo $data->nodeValue, PHP_EOL;
        echo "<br/>";
}
 libxml_clear_errors();
*/
/*
//extrae valor en medio de la etiqueta: <strong>TEXTO EXTRAIDO</strong>
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($res);
libxml_use_internal_errors(false);
foreach($dom->getElementsByTagName('strong') as $data) {
        echo $data->nodeValue, PHP_EOL;
        echo "<br/>";
}
 libxml_clear_errors();
*/
/*
//con DOM extraigo solo links de la web extraida con curl
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($res);
libxml_use_internal_errors(false);
foreach($dom->getElementsByTagName('a') as $link) {
        echo $link->getAttribute('href');
        echo "<br />";
}
 libxml_clear_errors();
 */
 ?>

                                      
