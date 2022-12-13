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
$query_insert = "DELETE FROM dashboard_items WHERE id NOT IN(".$ids_str.") AND date<=".$time;
mysqli_query($conn,$query_insert) or die(mysqli_error($conn));
mysqli_close($conn);

$conn2 = mysqli_connect('94.247.130.36:3306', 'imas', 'DtYoRTGTzFqsmtCgNEjV8Q', 'imasv2') or die("Не могу соединиться с MySQL.");
// $conn2 = mysqli_connect('94.247.130.36:3306', 'dashboard', 'he7cai1tooshooTiechei7OH', 'imasv2') or die("Не могу соединиться с MySQL.");
mysqli_query($conn2,"SET NAMES utf8");
$query="SELECT I.id,I.title,I.not_date,I.nd_date,I.content,R.RESOURCE_NAME,R.RESOURCE_PAGE_URL FROM items I, resource R WHERE I.res_id=R.RESOURCE_ID AND I.not_date='".$day."' AND I.nd_date>".$time." AND I.nd_date<=".time();
echo $query.'<br>';
$result=mysqli_query($conn2,$query) or die(mysqli_error($conn2));
$i=0;
while($res = mysqli_fetch_array($result)) {
	$items[$i]['id']=$res['id'];
	$items[$i]['title']=addslashes($res['title']);
	$items[$i]['not_date']=$res['not_date'];
	$items[$i]['date']=$res['nd_date'];
	$items[$i]['text']=addslashes(strip_tags($res['content']));
	$items[$i]['resource_name']=addslashes($res['RESOURCE_NAME']);
	$items[$i]['res_link']=addslashes($res['RESOURCE_PAGE_URL']);
	$i++;
}
mysqli_close($conn2);

$conn3 = mysqli_connect('localhost', 'v-40047_mms', 'R703U1ke', 'v_40047_mms') or die("Не могу соединиться с MySQL2");
mysqli_query($conn3,"SET NAMES utf8");
foreach($items as $value){
	$id=$value['id'];
	$title=strip_tags($value['title']);
	
	$text=strip_tags(str_replace("\n","",str_replace("\t","",$value['text'])));	
	$text=str_replace("                                                    "," ",$text);	
	$text=str_replace("                                "," ",$text);	
	$text=str_replace("                                                                                                                                                                                                                                                                                                                                                ","",$text);	
	$text=str_replace("                "," ",$text);	
	$text=str_replace("                    "," ",$text);	
	$text=str_replace("                                                                                                                                     "," ",$text);	
	$text=str_replace("            "," ",$text);	
	$text=str_replace("        "," ",$text);	
	$text=str_replace("                                    "," ",$text);	
	$text=str_replace("                            "," ",$text);	
	$text=str_replace("                       
                        
                            "," ",$text);		
	$text=str_replace("      "," ",$text);		
	$text=str_replace("
"," ",$text);		
	$text=str_replace("        "," ",$text);
	$teaserText=$text;
	$textLength = strlen($text);
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
		$query="SELECT 1 FROM dashboard_items WHERE news_id=".$id;
		//echo $query.'<br>';
		$result=mysqli_query($conn3,$query) or die(mysqli_error($conn3));
		$num_rows = mysqli_num_rows($result);
		if($num_rows==0){
			$query_insert = "INSERT INTO dashboard_items (news_id, title, text, not_date, date, resource_name, res_link) VALUES(".$id.",'".$title."','".$text."','".$not_date."',".$date.",'".$resource_name."','".$res_link."')";
			mysqli_query($conn3,$query_insert) or die(mysqli_error($conn3));
			echo $query_insert.'<br>';
		}
	}		
	
}
mysqli_close($conn3);
$mtime = explode(' ', microtime()); 
echo '<br><br>Page processed in '.round($mtime[0] + $mtime[1] - $starttime, 3).'seconds.<br>';  