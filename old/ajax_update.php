<?
$host = 'localhost';
$username = 'v-40047_mms';
$password = 'R703U1ke';
$name = 'v_40047_mms';
$connect = mysqli_connect($host, $username, $password, $name) or die("Could not connect: " . mysqli_error($connect));
mysqli_query($connect,"SET NAMES 'utf8'"); 
mysqli_query($connect,"SET CHARACTER SET 'utf8'");
mysqli_query($connect,"SET SESSION collation_connection = 'utf8_general_ci'");

switch($_GET['type']){
	case 'day_count':{
		$sql_dashboard = mysqli_query($connect,"Select * From dashboard WHERE id=1")or die(mysqli_error($connect));
		$table_dashboard = mysqli_fetch_array($sql_dashboard);
		echo number_format($table_dashboard['smi_today'] + $table_dashboard['social_today'], 0, ',', ' ' );
	}
	break;
	case 'day_count_smi':{
		$sql_dashboard = mysqli_query($connect,"Select * From dashboard WHERE id=1")or die(mysqli_error($connect));
		$table_dashboard = mysqli_fetch_array($sql_dashboard);
		$val = $table_dashboard['smi_today'];
		echo number_format($val, 0, ',', ' ' );
	}
	break;
	case 'day_count_social':{
		$sql_dashboard = mysqli_query($connect,"Select * From dashboard WHERE id=1")or die(mysqli_error($connect));
		$table_dashboard = mysqli_fetch_array($sql_dashboard);
		$val = $table_dashboard['social_today'];
		echo number_format($val, 0, ',', ' ' );
	}
	break;
	case 'total_count':{
		$sql_dashboard = mysqli_query($connect,"Select * From dashboard WHERE id=1")or die(mysqli_error($connect));
		$table_dashboard = mysqli_fetch_array($sql_dashboard);
		echo number_format($table_dashboard['smi_total'] + $table_dashboard['social_total'], 0, ',', ' ' );
	}
	break;
	case 'total_count_smi':{
		$sql_dashboard = mysqli_query($connect,"Select * From dashboard WHERE id=1")or die(mysqli_error($connect));
		$table_dashboard = mysqli_fetch_array($sql_dashboard);
		$val = $table_dashboard['smi_total'];
		echo number_format($val, 0, ',', ' ' );
	}
	break;
	case 'total_count_social':{
		$sql_dashboard = mysqli_query($connect,"Select * From dashboard WHERE id=1")or die(mysqli_error($connect));
		$table_dashboard = mysqli_fetch_array($sql_dashboard);
		$val = $table_dashboard['social_total'];
		echo number_format($val, 0, ',', ' ' );
	}
	break;
	case 'stat_day':{
		$time=mktime(date("H"),date("i")-1,date("s"),date("m"),date("d"),date("Y"));
		$day_time=date("H:i", $time);
		$sql_res = mysqli_query($connect,"Select * From statistic_day WHERE date='".$day_time."'")or die(mysqli_error($connect));
		$res = mysqli_fetch_array($sql_res);
		$val = $res['smi'].'&&'.$res['social'];
		echo $val;
	}
	break;
	case 'top_smi':{
		$res_id = (int) $_GET['res_id'];
		$sql_top_smi= mysqli_query($connect,"Select * From top_smi order by news_count desc limit 15")or die(mysqli_error($connect));
		while($table_top_smi = mysqli_fetch_array($sql_top_smi)){
			$top_smi_array[] = $table_top_smi;
		}
		foreach($top_smi_array as $top_smi){
			$news_count[] = $top_smi['news_count'];
		}
		$max_top_smi = max($news_count);

		$sql_top_smi= mysqli_query($connect,"Select * From top_smi where res_id = ".$res_id)or die(mysqli_error($connect));
		/*while($table_top_smi = mysqli_fetch_array($sql_top_smi)){
			$top_smi_array[] = $table_top_smi;
		}
		foreach($top_smi_array as $top_smi){
			$news_count[] = $top_smi['news_count'];
		}
		$max_top_smi = max($news_count);
		$i_smi = 0;
		foreach($top_smi_array as $top_smi){*/
		$top_smi = mysqli_fetch_array($sql_top_smi);
			$i_smi++;
			?>
				<td class="top_smi_name">
					<?if($top_smi['resource_logo'] != 'http://sub1.imas.kz/media/img/resources/'&&$top_smi['resource_logo'] != '') {?>
						<img class="res_logos_smi" style="width: 20px;" src="<?echo $top_smi['resource_logo'];?>">
					<?}else{?>
						<img style="width: 20px;" src="/images/no-logo.png">
					<?}?>
				</td>
				<td class="top_block_count">
					<a href="<?echo $top_smi['res_link'];?>" target="_blank">
						<span>
							<?echo $top_smi['resource_name'];?>
						</span>
					</a>
					
					<div style="width: <?echo $top_smi['news_count'] * 100 / $max_top_smi?>%;">
					</div>
				</td>
				<td class="top_smi_count">
					<span data-type="smi_size"><?echo $top_smi['news_count'];?></span>
				</td>
		<?/*}*/
	}
	break;
	case 'top_social':{
		$res_id = (int) $_GET['res_id'];
		$sql_top_social= mysqli_query($connect,"Select * From top_social order by news_count desc limit 15")or die(mysqli_error($connect));
		while($table_top_social = mysqli_fetch_array($sql_top_social)){
			$top_social_array[] = $table_top_social;
		}
		foreach($top_social_array as $top_social){
			$social_count[] = $top_social['news_count'];
		}
		$max_top_social = max($social_count);

		$sql_top_social= mysqli_query($connect,"Select * From top_social where res_id = ".$res_id)or die(mysqli_error($connect));
		/*while($table_top_social = mysqli_fetch_array($sql_top_social)){
			$top_social_array[] = $table_top_social;
		}
		foreach($top_social_array as $top_social){
			$social_count[] = $top_social['news_count'];
		}
		$max_top_social = max($social_count);
		$i_social = 0;
		foreach($top_social_array as $top_social){*/
		$top_social = mysqli_fetch_array($sql_top_social);
			$i_social++;
			if($top_social['type'] == '1') $img_social = 'icon/vk.png';
			if($top_social['type'] == '2') $img_social = 'icon/fb.png';
			if($top_social['type'] == '3') $img_social = 'icon/tw.png';
			if($top_social['type'] == '4') $img_social = 'icon/ig.png';
			if($top_social['type'] == '5') $img_social = 'icon/gp.png';
			if($top_social['type'] == '6') $img_social = 'icon/yt.png';
			if($top_social['type'] == '7') $img_social = 'icon/ok.png';
			if($top_social['type'] == '8') $img_social = 'icon/m.png';
			if($top_social['type'] == '9') $img_social = 'icon/lj.png';
			?>
				<td class="top_smi_name">
					<img class="res_logos" style="width: 20px;" src="<?echo $top_social['resource_logo'];?>">
				</td>
				<td class="top_block_count">
					<img style="width: 12px; height: 12px;" src="<?echo $img_social;?>">
					<a href="<?echo $top_social['res_link'];?>" target="_blank">
						<span>
							<?echo $top_social['resource_name'];?>
						</span>
					</a>
					<div style="width: <?echo $top_social['news_count'] * 100 / $max_top_social?>%; background-color: #68a4ec; height: 5px; margin-top: 2px;">
					</div>
				</td>
				<td class="top_smi_count">
					<span data-type="size_social"><?echo $top_social['news_count'];?></span>
				</td>
		<?/*}*/
	}
	break;
	case 'news_items':{
		$time=mktime(date("H"),date("i")-5,date("s"),date("m"),date("d"),date("Y"));
		$sql_news_items= mysqli_query($connect,"Select * From dashboard_items WHERE date>".$time." AND date<=".time()." ORDER BY date")or die(mysqli_error($connect));
		$news_items_array=array();
		while($table_news_items = mysqli_fetch_array($sql_news_items)){
			$news_items_array[] = $table_news_items;
		}
		foreach($news_items_array as $item){?>
			<span><?=date("H:i", $item['date'])?> </span>
			<b><?=$item['resource_name']?>:</b> <?=$item['text']?>
			<?echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";?>
		<?}
	}
	break;
	case 'news_posts':{
		$time=mktime(date("H"),date("i")-5,date("s"),date("m"),date("d"),date("Y"));
		$sql_news_posts= mysqli_query($connect,"Select * From dashboard_posts WHERE date>".$time." AND date<=".time()." ORDER BY date")or die(mysqli_error($connect));
		while($table_news_posts = mysqli_fetch_array($sql_news_posts)){
			$news_posts_array[] = $table_news_posts;
		}
		foreach($news_posts_array as $item){?>
			<span><?=date("H:i", $item['date'])?> </span>
			<b><?=$item['resource_name']?>:</b> <?=$item['text']?>
			<?echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";?>
		<?}
	}
	break;
}
$time=mktime(date("H"),date("i")-1,date("s"),date("m"),date("d"),date("Y"));
$day_time=date("H:i", $time);
$sql_res = mysqli_query($connect,"Select * From statistic_day")or die(mysqli_error($connect));
while($res = mysqli_fetch_array($sql_res)){
	$smi[]=$res['smi'];
	$social[]=$res['social'];
	$total[]=$res['smi']+$res['social'];
}
$sm=implode(',',$smi);
$sc=implode(',',$social);
$tot=implode(',',$total);
mysqli_close($connect);
?>