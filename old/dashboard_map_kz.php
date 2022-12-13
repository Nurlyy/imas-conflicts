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

// $connect = mysqli_connect('94.247.130.36:3306', 'dashboard', 'he7cai1tooshooTiechei7OH', 'imasv2') or die("Не могу соединиться с MySQL.");
for($n=0;$n<1;$n++){
	$time=mktime(0,0,0,date("m"),date("d")-$n,date("Y"));
	$day=date("Y-m-d",$time);
	$counts=array();
	$connect = mysqli_connect('94.247.130.36:3306', 'imas', 'DtYoRTGTzFqsmtCgNEjV8Q', 'imasv2') or die("Не могу соединиться с MySQL.");
	mysqli_query($connect,"SET NAMES utf8");
	$query="SELECT R.region_id, count(I.id) as count, C.name, C.hc FROM items I, resource R, regions C 
				WHERE R.COUNTRY_ID = 57
				AND I.res_id=R.RESOURCE_ID 
				AND I.not_date='".$day."'
				AND R.region_id=C.id
				GROUP BY R.region_id";
	echo $query.'<br>';
	$result=mysqli_query($connect, $query) or die(mysqli_error($connect));
	while($res = mysqli_fetch_array($result)) {
		$counts[$res['hc']]['count']=$res['count'];
		$counts[$res['hc']]['name']=$res['name'];
	}
	mysqli_close($connect);

	$url= "https://rest.imas.kz/dashboards/mapkz?day=".$day."&token=1qEeeotYWbSY";
	// echo $url.'<br>';
	$counts2=json_decode(get_web_page($url),true);
	foreach($counts2 as $key=>$value){
		if(isset($counts[$key]['count'])){
			$counts[$key]['count']+=$value['count'];
		}
		else {
			$counts[$key]['count']=$value['count'];
			$counts[$key]['name']=$value['name'];
		}
	}
	// $connect2 = mysqli_connect('185.102.72.44:3306', 'dashboard', 'he7cai1tooshooTiechei7OH','imasv2') or die("Не могу соединиться с MySQL.");
	// mysqli_query($connect2,"SET NAMES utf8");
	// $result=mysqli_query($connect2,$query) or die(mysqli_error($connect2));
	// while($res = mysqli_fetch_array($result)) {
		// if(isset($counts[$res['hc']]['count'])){
			// $counts[$res['hc']]['count']+=$res['count'];
		// }
		// else {
			// $counts[$res['hc']]['count']=$res['count'];
			// $counts[$res['hc']]['name']=$res['name'];
		// }
	// }
	// mysqli_close($connect2);
	
	$connect2 = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
	mysqli_query($connect2,"SET NAMES utf8") or die(mysqli_error());
	foreach($counts as $key=>$value){
		$hc=$key;
		$region=$value['name'];
		$count=$value['count'];
		$query="SELECT news_count FROM map_kz WHERE hc='".$hc."'";
		// echo $query.'<br>';
		$result=mysqli_query($connect2,$query) or die(mysqli_error($connect2));
		$num_rows = mysqli_num_rows($result);
		if($num_rows>0){
			$res = mysqli_fetch_array($result);
			$old_count=$res['news_count'];
			$count+=$old_count;
			$query_insert = "UPDATE map_kz SET news_count=".$count." WHERE hc='".$hc."'";
		}
		else{
			$query_insert = "INSERT INTO map_kz (region, hc, news_count) VALUES('".$region."','".$hc."',".$count.")";
		}	
		// echo $query_insert.'<br>';
		mysqli_query($connect2,$query_insert) or die(mysqli_error($connect2));
	}
	mysqli_close($connect2);
}
$mtime = explode(' ', microtime()); 
echo '<br><br>Page processed in '.round($mtime[0] + $mtime[1] - $starttime, 3).'seconds.<br>';  