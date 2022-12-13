<?php
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];
$day=date("Y-m-d");
$time=mktime(date("H"),date("i")-10,date("s"),date("m"),date("d"),date("Y"));

$conn = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
mysqli_query($conn,"SET NAMES utf8");
$query="SELECT * FROM dashboard_posts ORDER BY date DESC LIMIT 10";
$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
$ids=array();
while($res = mysqli_fetch_array($result)){$ids[]=$res['id'];}
$ids_str=implode(",",$ids);
if($ids_str=='') $ids_str=0;
$query_insert = "DELETE FROM dashboard_posts WHERE id NOT IN(".$ids_str.") AND date<=".$time;
//echo $query_insert.'<br>';
mysqli_query($conn,$query_insert) or die(mysqli_error($conn));
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
$url= "https://rest.imas.kz/dashboards/posts?token=1qEeeotYWbSY";
$posts=json_decode(get_web_page($url),true);
foreach($posts as $element):
	$element = json_decode(json_encode($element), true);
	$res_ids_array[]=$element['res_id'];
	$item_ids[$element['res_id']]=$element['id'];
	$items[$element['id']]['title']='';
	$items[$element['id']]['not_date']=$element['not_date'];
	$items[$element['id']]['date']=$element['date'];
	$items[$element['id']]['text']=addslashes($element['text']);
endforeach;


// $conn2 = mysqli_connect('185.102.72.44:3306', 'dashboard', 'he7cai1tooshooTiechei7OH', 'imasv2') or die("Не могу соединиться с MySQL.");
// mysqli_query($conn2, "SET NAMES utf8");
// $query="SELECT I.id,I.not_date,I.date,I.text,I.res_id FROM posts I WHERE I.not_date='".$day."' AND I.date>".$time." AND I.date<=".time()." ORDER BY I.id DESC LIMIT 50";
// echo $query.'<br>';
// $res_ids_array=array();
// $item_ids=array();
// $result=mysqli_query($conn2, $query) or die(mysqli_error($conn2));
// while($res = mysqli_fetch_array($result)) {
	// //echo '<pre>';print_r($res);echo '</pre>';
	// $res_ids_array[]=$res['res_id'];
	// $item_ids[$res['res_id']]=$res['id'];
	// $items[$res['id']]['title']='';
	// $items[$res['id']]['not_date']=$res['not_date'];
	// $items[$res['id']]['date']=$res['date'];
	// $items[$res['id']]['text']=addslashes($res['text']);
	// $res=array();
// }
// mysqli_close($conn2);


$res_ids_array[]=0;
$res_ids = implode(",",$res_ids_array);
$conn2 = mysqli_connect('94.247.130.36:3306', 'imas', 'DtYoRTGTzFqsmtCgNEjV8Q', 'imasv2') or die("Не могу соединиться с MySQL.");
// $conn2 = mysqli_connect('94.247.130.36:3306', 'dashboard', 'he7cai1tooshooTiechei7OH', 'imasv2') or die("Не могу соединиться с MySQL.");
mysqli_query($conn2, "SET NAMES utf8");
$query="SELECT R.id as res_id, R.resource_name, R.link as res_link FROM resource_social R WHERE R.id IN (".$res_ids.")";
echo $query.'<br>';
$result=mysqli_query($conn2, $query) or die(mysqli_error($conn2));
while($res = mysqli_fetch_array($result)) {
	$items[$item_ids[$res['res_id']]]['resource_name']=addslashes($res['resource_name']);
	$items[$item_ids[$res['res_id']]]['res_link']=addslashes($res['res_link']);
	$res=array();
}
mysqli_close($conn2);

//echo '<pre>';print_r($items);echo '</pre>';

$conn3 = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
mysqli_query($conn3,"SET NAMES utf8");
foreach($items as $key=>$value){
	$id=$key; 
	$title=strip_tags($value['title']);
	$text=strip_tags(str_replace("\n","",$value['text']));
	$textLength = strlen($text);
	$teaserText=$text;
	if($textLength>500){
		$teaserText = mb_substr($text, 0, 500);
		$teaserText = mb_substr($teaserText, 0, mb_strrpos($teaserText, ' ')) . "...";
	}
	$text=$teaserText;
	$not_date=$value['not_date'];
	$date=$value['date'];
	$resource_name=$value['resource_name'];
	$res_link=$value['res_link'];
	if($text=='   ' && $title==''){}
	else{
		$query="SELECT 1 FROM dashboard_posts WHERE news_id=".$id;
		//echo $query.'<br>';
		$result=mysqli_query($conn3,$query) or die(mysqli_error($conn3));
		$num_rows = mysqli_num_rows($result);
		if($num_rows==0){
			$query_insert = "INSERT INTO dashboard_posts (news_id, title, text, not_date, date, resource_name, res_link) VALUES(".$id.",'".$title."','".$text."','".$not_date."',".$date.",'".$resource_name."','".$res_link."')";
			//echo $query_insert.'<br>';
			mysqli_query($conn3,$query_insert) or die(mysqli_error($conn3));
		}	
	}
	
}
mysqli_close($conn3);
$mtime = explode(' ', microtime()); 
echo '<br><br>Page processed in '.round($mtime[0] + $mtime[1] - $starttime, 3).'seconds.<br>';  