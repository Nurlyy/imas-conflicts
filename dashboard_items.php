<?php 
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

$conn2 = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
$query_categories="SELECT U.id,U.s_id FROM v_40047_special.mvd M, v_40047_special.user U WHERE M.user_id=U.id";
$categories=array();
$categories_res = mysqli_query($conn2,$query_categories) or die(mysqli_error($conn2));
while($row = mysqli_fetch_array($categories_res)) {
	$cat['id']=$row['id'];
	$cat['s_id']=$row['s_id'];
	$categories[]=$cat;
}
$query_categories="SELECT U.id,U.s_id FROM v_40047_special.mvd2 M, v_40047_special.user U WHERE M.user_id=U.id";
$categories_res = mysqli_query($conn2,$query_categories) or die(mysqli_error($conn2));
while($row = mysqli_fetch_array($categories_res)) {
	$cat['id']=$row['id'];
	$cat['s_id']=$row['s_id'];
	$categories[]=$cat;
}
mysqli_close($conn2);

function get_web_page($url) {
	$url=str_replace(" ", "%20", $url);
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


foreach($categories as $cat){
	$category_id=$cat['id'];
	$p_id=$cat['s_id'];
	$url= "https://rest.imas.kz/dashboards/itemsnewproject?p_id=".$p_id."&token=1qEeeotYWbSY";
	echo $url.'<br>';
	$result=json_decode(get_web_page($url),true);
	$conn2 = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
	mysqli_query($conn2,"SET NAMES utf8");
	foreach($result as $element):
		$element = json_decode(json_encode($element), true);
		$news_id=$element['news_id'];
		$not_date=$element['not_date'];
		$date=$element['date'];
		$title=htmlspecialchars($element['title']);
		$text=htmlspecialchars($element['text']);
		$resource_name=htmlspecialchars($element['resource_name']);
		$res_link=$element['link'];
		$query_insert = "INSERT IGNORE INTO v_40047_special.`mvd_live_items`(`news_id`, `not_date`, `date`, `title`, `text`, `resource_name`, `res_link`, `category_id`)  VALUES (".$news_id.", '".$not_date."', ".$date.", '".$title."', '".$text."', '".$resource_name."', '".$res_link."', ".$category_id.")";
		echo $query_insert.'<br><br>';
		mysqli_query($conn2,$query_insert) or die(mysqli_error($conn2));
	endforeach;
	mysqli_close($conn2);
}

$mtime = explode(' ', microtime()); 
echo '<br><br>Page processed in '.round($mtime[0] + $mtime[1] - $starttime, 3).'seconds.<br>';  