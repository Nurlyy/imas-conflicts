<?php 
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];
?>
<?
// $s_date='2018-06-29';
// $f_date='2018-07-08';
// $i=54;
// $s_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30-30*$i,date("Y")));
// $f_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30*$i,date("Y")));
// $s_date=date("Y-m-d",mktime(0,0,0,date("m")-1-1*$i,1,date("Y")));
	// $f_date=date("Y-m-d",mktime(0,0,0,date("m")-1*$i,1,date("Y")));
// echo "not_date>='".$s_date."' AND not_date<'".$f_date."'<br>";

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


for($i=1;$i>=0;$i--){
	echo $i.' - $i<br>';
	$counts=array();
	$conn = mysqli_connect('94.247.130.36:3306', 'imas', 'DtYoRTGTzFqsmtCgNEjV8Q', 'imasv2') or die("Не могу соединиться с MySQL.");
	mysqli_query($conn,"SET NAMES utf8");
	// $s_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30-30*$i,date("Y")));
	// $f_date=date("Y-m-d",mktime(0,0,0,date("m"),date("d")-30*$i,date("Y")));
	$s_date=date("Y-m-d",mktime(0,0,0,date("m")-1-1*$i,1,date("Y")));
	$f_date=date("Y-m-d",mktime(0,0,0,date("m")-1*$i,1,date("Y")));
	$query="SELECT not_date, count(1) as count FROM items WHERE not_date>='".$s_date."' AND not_date<'".$f_date."' GROUP BY not_date";
	echo $query.'<br>';
	$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
	while($res = mysqli_fetch_array($result)) {
		$counts[$res['not_date']]['smi_count']=$res['count'];
		$counts[$res['not_date']]['social_count']=0;
	}
	mysqli_close($conn);
	
	$url= "https://rest.imas.kz/dashboards/stat?s_date=".$s_date."&f_date=".$f_date."&token=1qEeeotYWbSY";
	$posts=json_decode(get_web_page($url),true);
	foreach($posts as $element):
		$element = json_decode(json_encode($element), true);
		$counts[$element['not_date']]['social_count']=$element['count'];
		if(!isset($counts[$element['not_date']]['smi_count'])) $counts[$element['not_date']]['smi_count']=0;
	endforeach;
	


	$conn2 = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
	mysqli_query($conn2,"SET NAMES utf8");
	$k=0; 
	$social_count_sum=0;
	$smi_count_sum=0;
	// print_r($counts).'<br>';

	foreach($counts as $key=>$value){
			$social_count_sum+=$value['social_count'];
			$smi_count_sum+=$value['smi_count'];
	}

	// $query="SELECT 1 FROM statistic WHERE date='".$f_date."'";;
	// $result=mysqli_query($conn2,$query) or die(mysqli_error($conn2));
	// $num_rows = mysqli_num_rows($result);
	
	$dt=explode("-",$f_date);
	$d30=['04','06','09','11'];
	$m_d=31;
	if(in_array($dt[1],$d30)) $m_d=30;
	if($dt[1]=='02') $m_d=28;
	$social_count=round($social_count_sum/$m_d);
	$smi_count=round($smi_count_sum/$m_d);
	echo 'Среднее ко-во Соцсети: '.$social_count.'<br>';
	echo 'Среднее ко-во СМИ: '.$smi_count.'<br>';

	// if($num_rows>0){
		// $query_insert = "UPDATE statistic SET smi=".$smi_count.", social=".$social_count." WHERE date='".$f_date."'";
	// }
	// else{
		$query_insert = "INSERT INTO statistic (date, smi, social) VALUES('".$f_date."',".$smi_count.",".$social_count.")";
	// }

	echo $query_insert.'<br><br>';
	mysqli_query($conn2,$query_insert) or die(mysqli_error($conn2));
	mysqli_close($conn2);
}

$mtime = explode(' ', microtime()); 
echo '<br><br>Page processed in '.round($mtime[0] + $mtime[1] - $starttime, 3).'seconds.<br>';  