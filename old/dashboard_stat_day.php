<?php
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];
$time=date("H:i");

if($time=="00:00"){
	$connetct = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
	mysqli_query($connetct,"SET NAMES utf8");
	$query_insert = "DELETE FROM statistic_day WHERE id>0";
	mysqli_query($connetct,$query_insert) or die(mysqli_error($connetct));
	mysqli_close($connetct);
}
$conn2 = mysqli_connect('94.247.130.36:3306', 'imas', 'DtYoRTGTzFqsmtCgNEjV8Q', 'imasv2') or die("Не могу соединиться с MySQL.");
// $conn2= mysqli_connect('94.247.130.36:3306', 'dashboard', 'he7cai1tooshooTiechei7OH', 'imasv2') or die("Не могу соединиться с MySQL.");
mysqli_query($conn2,"SET NAMES utf8");
$day=date("Y-m-d");
$s_date=mktime(0,0,0,date("m"),date("d"),date("Y"));
$f_date=time();
//$s_date=mktime(date("H")-1,0,0,date("m"),date("d"),date("Y"));
$query="SELECT nd_date, count(1) as count FROM items WHERE nd_date>=".$s_date." AND nd_date<=".$f_date." GROUP BY nd_date";
echo $query.'<br>';
$result=mysqli_query($conn2,$query) or die(mysqli_error($conn2));
while($res = mysqli_fetch_array($result)) {
	if(isset($counts[date("H:i", $res['nd_date'])]['smi_count'])) $counts[date("H:i", $res['nd_date'])]['smi_count']+=$res['count'];
	else $counts[date("H:i", $res['nd_date'])]['smi_count']=$res['count'];
	$counts[date("H:i", $res['nd_date'])]['social_count']=0;
}
mysqli_close($conn2);


function get_web_page($url) {
	$cookie = dirname(__DIR__)."/cookie.txt";
	$uagent = "Opera/9.80 (Windows NT 6.1; WOW64) Presto/2.12.388 Version/12.14";	 
	$uagent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.87 Safari/537.36";
	$ch = curl_init( $url );	 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($ch, CURLOPT_USERAGENT, $uagent);	  
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_COOKIEFILE,$cookie);
	curl_setopt($ch, CURLOPT_COOKIEJAR,$cookie);  
	$content = curl_exec( $ch );
	return $content;
}
$url= "https://rest.imas.kz/dashboards/statday?token=1qEeeotYWbSY";
$posts=json_decode(get_web_page($url),true);
foreach($posts as $element):
	$element = json_decode(json_encode($element), true);
	if(isset($counts[date("H:i", $element['date'])]['social_count'])) $counts[date("H:i", $element['date'])]['social_count']+=$element['count'];
	else $counts[date("H:i", $element['date'])]['social_count']=$element['count'];
	if(!isset($counts[date("H:i", $element['date'])]['smi_count'])) $counts[date("H:i", $element['date'])]['smi_count']=0;
endforeach;
// $conn2 = mysqli_connect('185.102.72.44:3306', 'dashboard', 'he7cai1tooshooTiechei7OH','imasv2') or die("Не могу соединиться с MySQL.");
// mysqli_query($conn2,"SET NAMES utf8");
// $query="SELECT date, count(1) as count FROM posts WHERE date>=".$s_date." AND date<=".$f_date." GROUP BY date";
// echo $query.'<br>';
// $result=mysqli_query($conn2,$query) or die(mysqli_error($conn2));
// while($res = mysqli_fetch_array($result)) {
	// if(isset($counts[date("H:i", $res['date'])]['social_count'])) $counts[date("H:i", $res['date'])]['social_count']+=$res['count'];
	// else $counts[date("H:i", $res['date'])]['social_count']=$res['count'];
	// if(!isset($counts[date("H:i", $res['date'])]['smi_count'])) $counts[date("H:i", $res['date'])]['smi_count']=0;
// }
// mysqli_close($conn2);

$conn3 = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
mysqli_query($conn3,"SET NAMES utf8");
foreach($counts as $key=>$value){
	$date=$key;
	$social_count=$value['social_count'];
	$smi_count=$value['smi_count'];
	$query="SELECT 1 FROM statistic_day WHERE date='".$date."'";;
	//echo $query.'<br>';
	$result=mysqli_query($conn3,$query) or die(mysqli_error($conn3));
	$num_rows = mysqli_num_rows($result);
	if($num_rows>0){
		$query_insert = "UPDATE statistic_day SET smi=".$smi_count.", social=".$social_count." WHERE date='".$date."'";
	}
	else{
		$query_insert = "INSERT INTO statistic_day (date, smi, social) VALUES('".$date."',".$smi_count.",".$social_count.")";
	}	
	//echo $query_insert.'<br>';
	mysqli_query($conn3,$query_insert) or die(mysqli_error($conn3));
}
mysqli_close($conn3);
$mtime = explode(' ', microtime()); 
echo '<br><br>Page processed in '.round($mtime[0] + $mtime[1] - $starttime, 3).'seconds.<br>';  