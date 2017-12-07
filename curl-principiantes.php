<?php
$ch1=curl_init();
$ch2=curl_init();
$ch3=curl_init();
//$url ='http://www.meetup.com/es/';
//-----------------------------------------------
$u2='http://www.meetup.com/es/YOURSITE1/';
$u3='http://www.meetup.com/es/YOURSITE2/';
curl_setopt($ch1, CURLOPT_URL, $u1);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT,5);
curl_setopt($ch2, CURLOPT_URL, $u2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT,5);
curl_setopt($ch3, CURLOPT_URL, $u3);
curl_setopt($ch3, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch3, CURLOPT_CONNECTTIMEOUT,5);
$mh = curl_multi_init();
curl_multi_add_handle($mh,$ch1);
curl_multi_add_handle($mh,$ch2);
curl_multi_add_handle($mh,$ch3);
$running = null;
  do {
    $r=curl_multi_exec($mh, $running);
  } while ($running);
 $r1 = curl_multi_getcontent($ch1);
 if($r1){
 	preg_match_all("(<ul id=\"ajax-container\" class=\"resetList event-list clearfix\">(.*)</ul>)siU",$r1,$a1);
	preg_match_all("(<dl class=\"event-where\">(.*)</dl>)siU",$r1,$a2);
	echo utf8_decode($a1[0][0]); //tambien funciona con $s1[1][0] pero el boton sale en otro lugar
	echo utf8_decode($a2[0][0]); // tambien funciona con $s1[1][0]*/

 }
 $r2 = curl_multi_getcontent($ch2);
  if($r2){
 	preg_match_all("(<ul id=\"ajax-container\" class=\"resetList event-list clearfix\">(.*)</ul>)siU",$r2,$b1);
	preg_match_all("(<dl class=\"event-where\">(.*)</dl>)siU",$r2,$b2);
	echo utf8_decode($b1[0][0]); //tambien funciona con $s1[1][0] pero el boton sale en otro lugar
	echo utf8_decode($b2[0][0]); // tambien funciona con $s1[1][0]*/
 }

 $r3 = curl_multi_getcontent($ch3);
  if($r3){
 	preg_match_all("(<ul id=\"ajax-container\" class=\"resetList event-list clearfix\">(.*)</ul>)siU",$r3,$c1);
	preg_match_all("(<dl class=\"event-where\">(.*)</dl>)siU",$r3,$c2);
	echo utf8_decode($c1[0][0]); //tambien funciona con $s1[1][0] pero el boton sale en otro lugar
	echo utf8_decode($c2[0][0]); // tambien funciona con $s1[1][0]*/
 }
curl_multi_remove_handle($mh,$ch1);
curl_multi_remove_handle($mh,$ch2);
curl_multi_remove_handle($mh,$ch3);
curl_multi_close($mh);