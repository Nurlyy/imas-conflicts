<?php
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];
?>
<?
$conn = mysqli_connect('94.247.130.36:3306', 'imas', 'DtYoRTGTzFqsmtCgNEjV8Q', 'imasv2') or die("Не могу соединиться с MySQL.");
// $conn = mysqli_connect('94.247.130.36:3306', 'dashboard', 'he7cai1tooshooTiechei7OH','imasv2') or die("Не могу соединиться с MySQL."); 
mysqli_query($conn,"SET NAMES utf8");
$day=date("Y-m-d");
$query="SELECT I.res_id, count(I.id) as count, R.RESOURCE_NAME, R.RESOURCE_PAGE_URL, R.RESOURCE_LOGO FROM items I, resource R WHERE I.res_id=R.RESOURCE_ID AND I.not_date='".$day."' GROUP BY I.res_id ORDER BY count DESC LIMIT 50";
echo $query.'<br>';
$result=mysqli_query($conn,$query) or die(mysqli_error($conn));
while($res = mysqli_fetch_array($result)) {
	$counts[$res['res_id']]['count']=$res['count'];
	$counts[$res['res_id']]['name']=$res['RESOURCE_NAME'];
	$counts[$res['res_id']]['link']=$res['RESOURCE_PAGE_URL'];
	$counts[$res['res_id']]['img']='http://sub1.imas.kz/media/img/resources/'.$res['RESOURCE_LOGO'];
}
mysqli_close($conn);

$conn2 = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
mysqli_query($conn2,"SET NAMES utf8"); 
$ids=array(); 
foreach($counts as $key=>$value){
	$res_id=$key;
	$name=$value['name'];
	$link=$value['link'];
	$img=$value['img'];
	
	if($img == 'http://sub1.imas.kz/media/img/resources/') $img = 'http://dashboard.imas.kz/images/no-logo.png';
	
	$count=$value['count'];
	$query="SELECT 1 FROM top_smi WHERE res_id=".$res_id;
	$ids[]=$res_id;
	//echo $query.'<br>';
	$result=mysqli_query($conn2,$query) or die(mysqli_error($conn2));
	$num_rows = mysqli_num_rows($result);
	if($num_rows>0){
		$query_insert = "UPDATE top_smi SET news_count=".$count.", resource_logo='".$img."' WHERE res_id=".$res_id;
	}
	else{
		$query_insert = "INSERT INTO top_smi (res_id, resource_name, res_link, resource_logo, news_count) VALUES(".$res_id.",'".$name."','".$link."','".$img."',".$count.")";
	}	
	//echo $query_insert.'<br>';
	mysqli_query($conn2,$query_insert) or die(mysqli_error($conn2));
}
$ids=implode(',',$ids);
if($ids!='') mysqli_query($conn2,"DELETE FROM top_smi WHERE res_id NOT IN(".$ids.")");
mysqli_close($conn2);
$mtime = explode(' ', microtime()); 
echo '<br><br>Page processed in '.round($mtime[0] + $mtime[1] - $starttime, 3).'seconds.<br>';  