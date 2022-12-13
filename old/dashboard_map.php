<?php
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

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

for($n=0;$n<1;$n++){
	$time=mktime(0,0,0,date("m"),date("d")-$n,date("Y"));
	$day=date("Y-m-d",$time);
	$time=mktime(date("H"),date("i")-10,date("s"),date("m"),date("d"),date("Y"));
	$conn = mysqli_connect('94.247.130.36:3306', 'imas', 'DtYoRTGTzFqsmtCgNEjV8Q', 'imasv2') or die("Не могу соединиться с MySQL.");
	// $conn = mysqli_connect('94.247.130.36:3306', 'dashboard', 'he7cai1tooshooTiechei7OH', 'imasv2') or die("Не могу соединиться с MySQL.");
	mysqli_query($conn,"SET NAMES utf8");
	$query="SELECT R.COUNTRY_ID, count(I.id) as count, C.country_name, C.hc FROM items I, resource R, countries C 
				WHERE C.id=R.COUNTRY_ID 
				AND I.res_id=R.RESOURCE_ID 
				AND I.not_date='".$day."'
				GROUP BY R.COUNTRY_ID";
	echo $query.'<br>';
	$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
	while($res = mysqli_fetch_array($result)) {
		$counts[$res['country_name']]['count']=$res['count'];
		$counts[$res['country_name']]['hc']=$res['hc'];
	}
	mysqli_close($conn);

	// $conn2 = mysqli_connect('185.102.72.44:3306', 'dashboard', 'he7cai1tooshooTiechei7OH','imasv2') or die("Не могу соединиться с MySQL.");
	// mysqli_query($conn2,"SET NAMES utf8");
	// $query="SELECT R.country_id, count(I.id) as count, C.country_name, C.hc FROM posts I, resource_social R, imas.countries C 
				// WHERE C.id=R.country_id 
				// AND I.res_id=R.id 
				// AND I.not_date='".$day."'
				// GROUP BY R.country_id";
	// echo $query.'<br>';
	// $result=mysqli_query($conn2,$query) or die(mysqli_error($conn2));
	// while($res = mysqli_fetch_array($result)) {
		// if(isset($counts[$res['country_name']]['count'])){
			// $counts[$res['country_name']]['count']+=$res['count'];
		// }
		// else {
			// $counts[$res['country_name']]['count']=$res['count'];
			// $counts[$res['country_name']]['hc']=$res['hc'];
		// }
	// }
	// mysqli_close($conn2);
	$url= "https://rest.imas.kz/dashboards/map?day=".$day."&token=1qEeeotYWbSY";
	$counts2=json_decode(get_web_page($url),true);
	foreach($counts2 as $key=>$value){
		if(isset($counts[$key]['count'])){
			$counts[$key]['count']+=$value['count'];
		}
		else {
			$counts[$key]['count']=$value['count'];
			$counts[$key]['hc']=$value['hc'];
		}
	}

	$conn2 = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
	mysqli_query($conn2,"SET NAMES utf8");
	foreach($counts as $key=>$value){
		$country=$key;
		$hc=$value['hc'];
		$count=$value['count'];
		$query="SELECT news_count FROM map_world WHERE country='".$country."'";
		//echo $query.'<br>';
		$result=mysqli_query($conn2,$query) or die(mysqli_error($conn2));
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0){
			$res = mysqli_fetch_array($result);
			$old_count=$res['news_count'];
			$count+=$old_count;
			$query_insert = "UPDATE map_world SET news_count=".$count." WHERE country='".$country."'";
		}
		else{
			$query_insert = "INSERT INTO map_world (country, hc, news_count) VALUES('".$country."','".$hc."',".$count.")";
		}	
		//echo $query_insert.'<br>';
		mysqli_query($conn2,$query_insert) or die(mysqli_error($conn2));
	}
	mysqli_close($conn2);
}
$mtime = explode(' ', microtime()); 
echo '<br><br>Page processed in '.round($mtime[0] + $mtime[1] - $starttime, 3).'seconds.<br>';  