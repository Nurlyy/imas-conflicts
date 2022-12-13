<?php
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];
?>
<?
$conn = mysqli_connect('94.247.130.36:3306', 'imas', 'DtYoRTGTzFqsmtCgNEjV8Q', 'imasv2') or die("Не могу соединиться с MySQL.");
// $conn = mysqli_connect('94.247.130.36:3306', 'dashboard', 'he7cai1tooshooTiechei7OH','imasv2') or die("Не могу соединиться с MySQL.");
mysqli_query($conn,"SET NAMES utf8");
$day=date("Y-m-d");

$query="SELECT count(1) as count FROM items WHERE not_date='".$day."'";
echo $query.'<br>';
$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
$res = mysqli_fetch_array($result);
$smi_day_count=$res['count'];
$res['count']=0;

$query="SELECT count(1) as count FROM items";
echo $query.'<br>';
$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
$res = mysqli_fetch_array($result);
$smi_total_count=$res['count'];
$res['count']=0;

$query="SELECT count(1) as count FROM resource WHERE RESOURCE_STATUS='WORK'";
echo $query.'<br>';
$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
$res = mysqli_fetch_array($result);
$smi_resource_count=$res['count'];
$res['count']=0;

$query="SELECT count(1) as count FROM resource WHERE RESOURCE_STATUS='WORK' AND COUNTRY_ID=57 AND CATEGORY_ID!=8";
echo $query.'<br>';
$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
$res = mysqli_fetch_array($result);
$smi_resource_kz_count=$res['count'];
$res['count']=0;

// $query="SELECT count(1) as count FROM resource_social WHERE stability=1";
// echo $query.'<br>';
// $result=mysqli_query($conn,$query) or die(mysqli_error($conn));
// $res = mysqli_fetch_array($result);
$social_resource_count=0;
// $social_resource_count=$res['count'];
$res['count']=0;
$query="SELECT count(1) as count FROM resource WHERE RESOURCE_STATUS='WORK' AND CATEGORY_ID=8";
echo $query.'<br>';
$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
$res = mysqli_fetch_array($result);
$social_resource_count+=$res['count'];
$res['count']=0;

mysqli_close($conn);


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
$url= "https://rest.imas.kz/dashboards/socialdaycount?token=1qEeeotYWbSY";
$posts=json_decode(get_web_page($url),true);
foreach($posts as $element):
	$element = json_decode(json_encode($element), true);
	$social_day_count=$element['count'];
endforeach;

$url= "https://rest.imas.kz/dashboards/socialtotalcount?token=1qEeeotYWbSY";
$posts=json_decode(get_web_page($url),true);
foreach($posts as $element):
	$element = json_decode(json_encode($element), true);
	$social_total_count=$element['count'];
endforeach;


// $query="SELECT count(1) as count FROM posts WHERE not_date='".$day."'";
// echo $query.'<br>';
// $conn2 = mysqli_connect('185.102.72.44:3306', 'dashboard', 'he7cai1tooshooTiechei7OH','imasv2') or die("Не могу соединиться с MySQL.");
// mysqli_query($conn2,"SET NAMES utf8");
// $result=mysqli_query($conn2,$query) or die(mysqli_error($conn2));
// $res = mysqli_fetch_array($result);
// $social_day_count=$res['count'];
// $res['count']=0;

// $query="SELECT count(1) as count FROM posts";
// echo $query.'<br>';
// $result=mysqli_query($conn2,$query) or die(mysqli_error($conn2));
// $res = mysqli_fetch_array($result);
// $social_total_count=$res['count'];
// $res['count']=0;
// mysqli_close($conn2);



$conn2 = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
mysqli_query($conn2,"SET NAMES utf8");
$query_insert = "UPDATE dashboard SET smi_today=".$smi_day_count.", social_today=".$social_day_count.", smi_total=".$smi_total_count.", social_total=".$social_total_count.", smi_resource_count=".$smi_resource_count.", smi_resource_kz_count=".$smi_resource_kz_count.", social_resource_count=".$social_resource_count." WHERE id=1";
echo $query_insert.'<br>';
mysqli_query($conn2,$query_insert) or die(mysqli_error($conn2));
mysqli_close($conn2);
$mtime = explode(' ', microtime()); 
echo '<br><br>Page processed in '.round($mtime[0] + $mtime[1] - $starttime, 3).'seconds.<br>';  