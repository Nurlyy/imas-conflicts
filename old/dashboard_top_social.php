<?php 
set_time_limit(0);
ini_set('display_errors', 1);
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];
?>
<?
// require_once 'vendor/autoload.php';
// $client_posts = new \ClickHouse\Client('http://94.247.130.60', 8123, 'dashboard', 'he7cai1tooshooTiechei7OH');
// // $client_posts = new \ClickHouse\Client('http://94.247.130.60', 8123, 'default', 'KKQ76OSM34PD');

// // $client_posts = new \ClickHouse\Client('http://94.247.130.51', 8123, 'dashboard', 'he7cai1tooshooTiechei7OH');
// $isLive = $client_posts->ping();

// if (false === $isLive) {
  // echo 'сервер запустили?<br><br>';
// }


// $day=date("Y-m-d");
// $query="SELECT I.res_id, count(I.id) as count FROM imas.posts I WHERE toDate(date)='".$day."' GROUP BY I.res_id ORDER BY count DESC LIMIT 30";
// echo $query;
// $posts = $client_posts->select($query)->fetchAll();
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
$url= "https://rest.imas.kz/dashboards/topsocial?token=1qEeeotYWbSY";
$posts=json_decode(get_web_page($url),true);



foreach($posts as $element):
	$element = json_decode(json_encode($element), true);
	$counts[$element['res_id']]['count']=$element['count'];
	$res_ids_array[]=$element['res_id'];
endforeach;

$conn = mysqli_connect('94.247.130.36:3306', 'imas', 'DtYoRTGTzFqsmtCgNEjV8Q', 'imasv2') or die("Не могу соединиться с MySQL.");
// $conn = mysqli_connect('94.247.130.36:3306', 'dashboard', 'he7cai1tooshooTiechei7OH', 'imasv2') or die("Не могу соединиться с MySQL.");
mysqli_query($conn,"SET NAMES utf8");
$res_ids_array[]=0;
$res_ids = implode(",",$res_ids_array);
$query="SELECT R.id as res_id, R.resource_name, R.link, R.type, R.image_profile FROM resource_social R WHERE R.id IN (".$res_ids.")";
echo $query.'<br>';
$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
while($res = mysqli_fetch_array($result)) {
	$counts[$res['res_id']]['name']=$res['resource_name'];
	$counts[$res['res_id']]['link']=$res['link'];
	$counts[$res['res_id']]['type']=$res['type'];
	$counts[$res['res_id']]['img']=$res['image_profile'];
}
mysqli_close($conn);


$conn2 = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
mysqli_query($conn2,"SET NAMES utf8");
$ids=array();
foreach($counts as $key=>$value){
	$res_id=$key;
	$ids[]=$res_id;
	$name=addslashes($value['name']);
	if($name=='BMW Club /// Almaty Куплю Продам Обменяю') $name='BMW Club /// Almaty';
	if($name=='Объявления Петропавловск — Купля\Продажа') $name='Объявления Петропавловск';
	if($name=='[ЖЖ] Жанаозен жаршысы (хабарландыру тактасы)') $name='[ЖЖ] Жанаозен жаршысы';
	if($name=='Доска объявлений | Казахстан | Работа') $name='Доска объявлений | Казахстан';
	$link=$value['link'];
	$img=addslashes($value['img']);
	$type=$value['type'];
	$count=$value['count'];
	$query="SELECT 1 FROM top_social WHERE res_id=".$res_id;
	// echo $query.'<br>';
	$result=mysqli_query($conn2,$query) or die(mysqli_error($conn2));
	$num_rows = mysqli_num_rows($result);
	if($num_rows>0){
		$query_insert = "UPDATE top_social SET news_count=".$count." WHERE res_id=".$res_id;
	}
	else{
		$query_insert = "INSERT INTO top_social (res_id, resource_name, res_link, type, resource_logo, news_count) VALUES(".$res_id.",'".$name."','".$link."',".$type.",'".$img."',".$count.")";
	}	
	// echo $query_insert.'<br>';
	mysqli_query($conn2,$query_insert) or die(mysqli_error($conn2));
}
$ids=implode(',',$ids);
if($ids!='') mysqli_query($conn2,"DELETE FROM top_social WHERE res_id NOT IN(".$ids.")");
mysqli_close($conn2); 
$mtime = explode(' ', microtime()); 
echo '<br><br>Page processed in '.round($mtime[0] + $mtime[1] - $starttime, 3).'seconds.<br>';  