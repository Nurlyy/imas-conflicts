<?php
$host = 'localhost';
$username = 'v_40047_mms';
$password = 'R703U1ke';
$name = 'v_40047_mms';
$connect = mysqli_connect($host, $username, $password, $name) or die("Could not connect: " . mysqli_error($connect));
mysqli_query($connect,"SET NAMES 'utf8'"); 
mysqli_query($connect,"SET CHARACTER SET 'utf8'");
mysqli_query($connect,"SET SESSION collation_connection = 'utf8_general_ci'");


$sql_statistic = mysqli_query($connect,"
	Select * From statistic order by date
")or die(mysqli_error($connect));
while($table_statistic = mysqli_fetch_array($sql_statistic)){	
	$statistic_array[] = $table_statistic;
	$statistic_date_array[] = $table_statistic['date'];
	$statistic_smi_array[] = $table_statistic['smi'];
	$statistic_social_array[] = $table_statistic['social'];
}
foreach($statistic_array as $statistic){
	$statistic_all_array[] = $statistic['smi'] + $statistic['social'];
}
$statistic_smi = implode(',', $statistic_smi_array);
$statistic_social = implode(',', $statistic_social_array);
$statistic_all = implode(',', $statistic_all_array);


foreach($statistic_date_array as $statistic_date){
	if($statistic_date >= '2014-10-01' && $statistic_date <= '2017-12-31'){
		$date_grafics_1[] = '';
	}
}


$sql_res = mysqli_query($connect,"Select * From statistic_day")or die(mysqli_error($connect));
while($res = mysqli_fetch_array($sql_res)){
	$smi[]=$res['smi'];
	$social[]=$res['social'];
	$total[]=$res['smi']+$res['social'];
}
$statistic_smi_day=implode(',',$smi);
$statistic_social_day=implode(',',$social);
$statistic_all_day=implode(',',$total);






$sql_dashboard = mysqli_query($connect,"
	Select * From dashboard
")or die(mysqli_error($connect));
while($table_dashboard = mysqli_fetch_array($sql_dashboard)){
	$smi_today = 			 $table_dashboard['smi_today'];
	$social_today = 		 $table_dashboard['social_today'];
	$smi_total = 			 $table_dashboard['smi_total'];
	$social_total = 		 $table_dashboard['social_total'];
	$smi_resource_count = 	 $table_dashboard['smi_resource_count'];
	$social_resource_count = $table_dashboard['social_resource_count'];
	$smi_resource_kz_count = $table_dashboard['smi_resource_kz_count'];
}

$sql_top_smi= mysqli_query($connect,"
	Select * From top_smi order by news_count desc limit 15
")or die(mysqli_error($connect));
while($table_top_smi = mysqli_fetch_array($sql_top_smi)){
	$top_smi_array[] = $table_top_smi;
}
foreach($top_smi_array as $top_smi){
	$news_count[] = $top_smi['news_count'];
}
$max_top_smi = max($news_count);


$sql_top_social= mysqli_query($connect,"
	Select * From top_social order by news_count desc limit 15
")or die(mysqli_error($connect));
while($table_top_social = mysqli_fetch_array($sql_top_social)){
	$top_social_array[] = $table_top_social;
}

foreach($top_social_array as $top_social){
	$social_count[] = $top_social['news_count'];
}
$max_top_social = max($social_count);

$sql_map_world= mysqli_query($connect,"Select * From map_world")or die(mysqli_error($connect));
while($table_map_world = mysqli_fetch_array($sql_map_world)){
	$map_world_array[] = $table_map_world;
}

$sql_map_kz= mysqli_query($connect,"Select * From map_kz")or die(mysqli_error($connect));
while($table_map_kz = mysqli_fetch_array($sql_map_kz)){
	$map_kz_array[] = $table_map_kz;
}

$time=mktime(date("H"),date("i")-5,date("s"),date("m"),date("d"),date("Y"));
$sql_news_items= mysqli_query($connect,"Select * From dashboard_items WHERE date>".$time." AND date<=".time()." ORDER BY date")or die(mysqli_error($connect));
while($table_news_items = mysqli_fetch_array($sql_news_items)){
	if($table_news_items['text']!='') $news_items_array[] = $table_news_items;
}
if(count($news_items_array)<15){
	$sql_news_items= mysqli_query($connect,"SELECT * FROM (Select * From dashboard_items WHERE date<=".time()." ORDER BY date DESC LIMIT 15) as A ORDER BY date")or die(mysqli_error($connect));
	$news_items_array=array();
	while($table_news_items = mysqli_fetch_array($sql_news_items)){
		if($table_news_items['text']!='') $news_items_array[] = $table_news_items;
	}
	array_multisort($news_items_array[0], SORT_NUMERIC, SORT_ASC);
}
$sql_news_posts= mysqli_query($connect,"Select * From dashboard_posts WHERE date>".$time." AND date<=".time()." ORDER BY date")or die(mysqli_error($connect));
while($table_news_posts = mysqli_fetch_array($sql_news_posts)){
	if($table_news_posts['text']!='') $news_posts_array[] = $table_news_posts;
}
if(count($news_posts_array)<15){
	$sql_news_posts= mysqli_query($connect,"SELECT * FROM (Select * From dashboard_posts WHERE date<=now() ORDER BY date DESC LIMIT 15) as A ORDER BY date")or die(mysqli_error($connect));
	$news_posts_array=array();
	while($table_news_posts = mysqli_fetch_array($sql_news_posts)){
		if($table_news_posts['text']!='') $news_posts_array[] = $table_news_posts;
	}
	//echo '<pre>';print_r($news_posts_array);
	array_multisort($news_posts_array[0], SORT_NUMERIC, SORT_ASC);
}
?>



<html>
<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name = "viewport" content = "user-scalable=no, width=device-width">
	<title>DASHBOARD IMAS</title>

	
	
	<?if (!@fopen('http://dashboard.imas.kz/images/three.png','r')){?>
		<link rel="stylesheet" type="text/css" media="screen" href="/css/style_no_3.css"/>	
	<?}else{?>
		<link rel="stylesheet" type="text/css" media="screen" href="/css/style.css"/>
	<?}?>
	
	<?if (!@fopen('http://dashboard.imas.kz/images/four.png','r')){?>
		<link rel="stylesheet" type="text/css" media="screen" href="/css/style_no_4.css"/>	
	<?}else{?>
		<link rel="stylesheet" type="text/css" media="screen" href="/css/style.css"/>
	<?}?>



	<link href="/d_css/bootstrap.min.css" rel="stylesheet">
    <link href="/d_css/font-awesome.css" rel="stylesheet">
    <link href="/d_css/toastr.min.css" rel="stylesheet">
    <link href="/d_css/jquery.gritter.css" rel="stylesheet">
    <link href="/d_css/animate.css" rel="stylesheet">
    <link href="/d_css/style.css" rel="stylesheet">
	<link href="/d_css/deshbord_style.css" rel="stylesheet">
	<link href="/d_css/load_modal.css" rel="stylesheet">
	<link href="/d_css/bootstrap-chosen.css" rel="stylesheet">

	
	
	
	<link rel="stylesheet" type="text/css" href="/nav/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="/nav/jquery.fullPage.min.css" />
	<link rel="stylesheet" type="text/css" href="/nav/font-awesome.min.css">
	<link id="animated_stylesheet" rel="stylesheet" type="text/css" href="/nav/animate.css" />
	<link rel="stylesheet" type="text/css" href="/nav/scrollbox.min.css" media="screen">
	<link rel="stylesheet" type="text/css" href="/nav/style.css" />
	<link rel="stylesheet" type="text/css" href="/nav/odometer.css" />
	<link rel="stylesheet" type="text/css" href="/nav/owl.carousel.css" />
	<link rel="stylesheet" type="text/css" href="/nav/owl.theme.css" />
	


	<script src="/d_js/jquery-3.1.1.min.js"></script>
</head>

<body style="background: #252326;">
	<div id="slider">
		<div id="first" class="firstanimation">
			<div class="col-sm-12 col-md-12 col-lg-12 padding-0 body_content">
				<!-- Навигационное меню -->
				<nav class="nav_top col-sm-12 col-md-12 col-lg-12 padding-0">
						<a class="navbar-brand" href="http://info.imas.kz"><img class="img-responsive col-xs-12" src="http://info.imas.kz/img/logo.png"></a>
					<div class="text">
						<span>Самая мощная казахстанская система мониторинга и анализа <br>информационных потоков в режиме реального времени</span>
					</div>
					
					<div class="madeinkz">
						<img class="img-responsive" src="http://imas.kz/media/img/madeinkz.png" style="zoom: 70%;"> 
					</div>
					
					<div class="number_one">
						<img src="/icon/number_one.jpg" style="height: 50px; float: left;">
						<div class="text">
							<span>Система в Казахстане <br>в режиме реального времени</span>
						</div>
					</div>
					<div class="date">

						<script>
						function Clock_ms() { 
							var monthsArr = ["Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", 
							"Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"];

							var daysArr = ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"];
							
							
							/* Настройки внешнего вида */ 
							var c_h  =      '#34a5eb';   // Цвет часов 
							var c_m  =      '#34a5eb';  // Цвет минут 
							var c_s  =      '#34a5eb';  // Цвет секунд 
							var c_ms =      '#34a5eb';  // Цвет миллисекунд 
							var sep  =      '#34a5eb';  // Цвет разделителей 

						  
							/* Для нормальной работы скрипта ниже лучше ничего не менять! */ 
							var data = new Date(); 
							
							var year = data.getFullYear();
							var month = data.getMonth();
							var numDay = data.getDate();
							var day = data.getDay();
							
							
							var hour = data.getHours(); 
							var min = data.getMinutes(); 
							var sec = data.getSeconds(); 
							var ms = (data.getTime()/10).toFixed(0).substr(10); 
							if (hour<10) {hour = '0' + hour}; 
							if (min<10) {min = '0' + min}; 
							if (sec<10) {sec = '0' + sec}; 
							var time = '<div id="time"><span id="hour" style="color:'+c_h+'">'+hour+'</span>'+'<span style="color:'+sep+'">:</span>'+'<span id="min" style="color:'+c_m+'">'+min+'</span>'+'<span style="color:'+sep+'">:</span>'+'<span  id="sec" style="color:'+c_s+'">' +sec +'</span>'+'<span style="color:'+sep+'">.</span>'+'<span style="color:'+c_ms+'">'+ms+'</span></div>'+'<div id="date"><span id="day_arr" style="color:'+c_h+'">'+daysArr[day]+', '+'<br></span>'+'<span id="day_num" style="color:'+c_h+'">'+numDay+' '+'</span>'+'<span id="month_arr" style="color:'+c_h+'">'+monthsArr[month]+' '+'</span>'+'<span id="year" style="color:'+c_h+'">'+year+' г. '+'</span></div>'; 
							document.getElementById('date_time').innerHTML = time; 
							setTimeout("Clock_ms()",1); 
							/* ========================== /END ========================== */ 
						} 
						onload = Clock_ms; 
						</script>
						<span id="date_time"></span>
					</div>
					
					
				</nav>


				<div class="col-sm-12 col-md-12 col-lg-12 padding-0 top_block">
					<!-- Левый блок -->
					<div class="col-sm-9 col-md-9 col-lg-9 padding-0">
						<div class="col-sm-12 col-md-12 col-lg-12 padding-0">
							<!-- Диаграмма 1 -->
							<div class="col-sm-6 col-md-6 col-lg-6 padding-0 diagramm_1">
								<div class="title_block"><?echo ("Динамика публикаций за весь период:");?></div><br>
								<div class="name_statistic_2014">2014</div>
								<div class="name_statistic_2015">2015</div>
								<div class="name_statistic_2016">2016</div>
								<div class="name_statistic_2017">2017</div>
								<div id="container_1"></div>
								
							</div>
							
							
							<!-- Диаграмма 2 -->
							<div class="col-sm-6 col-md-6 col-lg-6 padding-0 diagramm_2">
								<div class="title_block"><?echo ("Динамика публикаций за сегодня:");?></div><br>
								
								<div id="container_2"></div>
							</div>
						</div>
					</div>

					
					<!-- Правый блок -->
					<div class="col-sm-3 col-md-3 col-lg-3 padding-0">
						<div class="col-sm-12 col-md-12 col-lg-12 padding-0">
							<!-- Публикаций за сегодня -->
							<div class="col-sm-6 col-md-6 col-lg-6 div_to_day">
								<div class="public_to_day">
									<?echo ("Публикаций <br>за сегодня:");?>
								</div>
								
								<?$today_sum=$smi_today + $social_today;
								$av_sum = round($today_sum/(date("H")*60+date("i")+1));
								$av_smi = round($smi_today/(date("H")*60+date("i")+1));
								$av_social = round($social_today/(date("H")*60+date("i")+1));?>
								<h2 id="smi_social_day" class="title_div_to_day"><?echo number_format($smi_today + $social_today - $av_sum, 0, ',', ' ' );?></h2>
								<span class="day_all_public">
									<?echo ("Всего публикаций");?>
								</span>
								
								
								<h2 id="smi_day" class="title_div_to_day"><?echo number_format($smi_today - $av_smi, 0, ',', ' ' );?></h2>
								<span class="day_smi_public">
									<?echo ("Публикаций СМИ");?>
								</span>
								
								
								<h2 id="social_day" class="title_div_to_day"><?echo number_format($social_today - $av_social, 0, ',', ' ' );?></h2>
								<span class="day_social_public">
									<?echo ("Публикаций соцсети");?>
								</span>
							</div>
							
							
							<!-- Публикаций в архиве -->
							<div class="col-sm-6 col-md-6 col-lg-6 div_to_arhiv">
								<div class="public_all_period">
									<?echo ("Публикаций <br>в архиве:");?>
								</div>
								
								
								<h2 id="smi_social_arhiv" class="title_div_to_day"><?echo number_format($smi_total + $social_total - $av_sum, 0, ',', ' ' );?></h2>
								<span class="day_all_public">
									<?echo ("Всего публикаций");?>
								</span>
								
								
								<h2 id="smi_arhiv" class="title_div_to_day"><?echo number_format($smi_total - $av_smi, 0, ',', ' ' );?></h2>
								<span class="day_smi_public">
									<?echo ("Публикаций СМИ");?>
								</span>
								
								
								<h2 id="social_arhiv" class="title_div_to_day"><?echo number_format($social_total - $av_social, 0, ',', ' ' );?></h2>
								<span class="day_social_public">
									<?echo ("Публикаций соцсети");?>
								</span>
							</div>
						</div>
					</div>
				</div>


				<div class="col-sm-12 col-md-12 col-lg-12 padding-0 ">
					<!-- Карты -->
					<div class="col-sm-6 col-md-6 col-lg-6 padding-0 maps_block">
						<!-- Карта 1 -->
						<div class="col-sm-6 col-md-6 col-lg-6 padding-0 maps_world">
							<div class="title_block"><?echo ("Геодинамика публикаций<br> за весь период по миру");?></div><br>
							<div id="container_maps_world">
								<div class="loading">
									<i class="icon-spinner icon-spin icon-large"></i>
								</div>
							</div>
						</div>
						
						
						<!-- Карта 2 -->
						<div class="col-sm-6 col-md-6 col-lg-6 padding-0 maps_rk">
							<div class="title_block"><?echo ("Геодинамика публикаций<br> за весь период по Казахстану");?></div><br>
							<div id="container_maps_rk"></div>
						</div>
						
						
						<!-- Статистика -->
						<div class="col-sm-12 col-md-12 col-lg-12 padding-0 statistics_maps">
							<div class="col-sm-6 col-md-6 col-lg-6 padding-0">
								<div class="col-sm-6 col-md-6 col-lg-6">
									<h1 id="smi_resource_count"><?
									echo $smi_resource_world = '12 072';
									// echo number_format($smi_resource_world, 0, ',', ' ' );?></h1>
									<span>Источников СМИ<br> по миру</span>
								</div>
								
								<div class="col-sm-6 col-md-6 col-lg-6">
									<h1 id="smi_resource_kz_count"><?echo $smi_resource_kz_count;?></h1>
									<span>Источников СМИ<br> по Казахстану</span>
								</div>
							</div>
							
							<div class="col-sm-6 col-md-6 col-lg-6 padding-0">
								<!-- 
									<div class="col-sm-4 col-md-4 col-lg-4">
										<h1 id="social_resource_count"><?// echo number_format($social_resource_count, 0, ',', ' ' );?></h1>
										<span>Источников соцсетей</span>
									</div>
								-->
								<div class="col-sm-3 col-md-3 col-lg-3">
									<h1>9</h1>
									<span>Соцсетей</span>
								</div>
								
								<div class="col-sm-9 col-md-9 col-lg-9">
									<table border="0" cellspacing='0'>
										<tr height="26px">
											<td width="26px">
												<img src="/icon/vk.png" width="20px">
											</td>
											<td width="26px">
												<img src="/icon/fb.png" width="20px">
											</td>
											<td width="26px">
												<img src="/icon/tw.png" width="20px">
											</td>

											<td width="26px">
												<img src="/icon/m.png" width="20px">
											</td>
											<td width="26px">
												<img src="/icon/ok.png" width="20px">
											</td>
											<td width="26px">
												<img src="/icon/lj.png" width="20px">
											</td>
									
											<td width="26px">
												<img src="/icon/ig.png" width="20px">
											</td>
											<td width="26px">
												<img src="/icon/gp.png" width="20px">
											</td>
											<td width="26px">
												<img src="/icon/yt.png" width="20px">
											</td>
										</tr>
									</table>
								</div>
						</div>
						</div>
					</div>
					
					<!-- ТОП список -->
					<div class="col-sm-6 col-md-6 col-lg-6 padding-0">
						<!-- ТОП СМИ -->
						<div class="col-sm-6 col-md-6 col-lg-6 padding-0 top_smi">
							<div class="title_block">
								<?echo ("ТОП СМИ");?>
								<span><?echo ("(по количеству публикаций за сегодня)");?></span>
							</div>
							
							<div class="col-sm-1 col-md-1 col-lg-1 padding-0">
								<table border="0" cellspacing='0' class="table_num">
										<tr><td class="numbering">1</td></tr>
										<tr><td class="numbering">2</td></tr>
										<tr><td class="numbering">3</td></tr>
										<tr><td class="numbering">4</td></tr>
										<tr><td class="numbering">5</td></tr>
										<tr><td class="numbering">6</td></tr>
										<tr><td class="numbering">7</td></tr>
										<tr><td class="numbering">8</td></tr>
										<tr><td class="numbering">9</td></tr>
										<tr><td class="numbering">10</td></tr>
								</table>
							</div>
							<div class="col-sm-11 col-md-11 col-lg-11 padding-0">
								<table border="0" cellspacing='0' class="table-sorter table_smi">		
									<div class="sort_table_smi" name="sort_table_smi" onclick="update_table_smi()" style="display: none;"> sort_social</div>
									<tbody id="applications_smi">
									<?
									$i_smi = 0;
									foreach($top_smi_array as $top_smi){
										$i_smi++;
										?>
										<tr data-id="id-<?=$i_smi;?>" id="trsmi_<?=$top_smi['res_id'];?>" <?if($i_smi>=11) echo 'style="display:none;"';?>>
											<td class="top_smi_name">
												<img style="width: 20px;" src="<?echo $top_smi['resource_logo'];?>">
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
										</tr>
									<?}?>
									</tbody>
								</table>
							</div>
						</div>
						
						<!-- ТОП соцсети -->
						<div class="col-sm-6 col-md-6 col-lg-6 padding-0 top_social">
							<div class="title_block">
								<?echo ("ТОП соцсетей");?>
								<span><?echo ("(по количеству публикаций за сегодня)");?></span>
							</div>
							
							<div class="col-sm-1 col-md-1 col-lg-1 padding-0">
								<table border="0" cellspacing='0' class="table_num">
										<tr><td class="numbering">1</td></tr>
										<tr><td class="numbering">2</td></tr>
										<tr><td class="numbering">3</td></tr>
										<tr><td class="numbering">4</td></tr>
										<tr><td class="numbering">5</td></tr>
										<tr><td class="numbering">6</td></tr>
										<tr><td class="numbering">7</td></tr>
										<tr><td class="numbering">8</td></tr>
										<tr><td class="numbering">9</td></tr>
										<tr><td class="numbering">10</td></tr>
								</table>
							</div>
							<div class="col-sm-11 col-md-11 col-lg-11 padding-0">
								<table border="0" cellspacing='0' class="table-sorter table_soc">
									<div class="sort_table_social" name="sort_table_social" onclick="update_table_social()" style="display: none;"> sort_social</div>
									<tbody id="applications_social">
									<?
									$i_social = 0;
									foreach($top_social_array as $top_social){
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
										<tr data-id="id-<?=$i_social;?>" id="tr_<?=$top_social['res_id'];?>" <?if($i_social>=11) echo 'style="display:none;"';?>>
											<td class="top_smi_name">
												<img style="width: 20px;" src="<?echo $top_social['resource_logo'];?>">
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
										</tr>
									<?}?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>


				<div class="col-sm-12 col-md-12 col-lg-12 padding-0 live_block">
					<div class="col-sm-3 col-md-3 col-lg-3 padding-0">
						<div class="col-sm-6 col-md-6 col-lg-6 padding-0 live_title">
							<div>LIVE:</div>
						</div>
						<div class="col-sm-6 col-md-6 col-lg-6 padding-0 live_smi_social">
							<div class="col-sm-12 col-md-12 col-lg-12 padding-0 live_smi">
								<div>СМИ</div>
							</div>
							
							<div class="col-sm-12 col-md-12 col-lg-12 padding-0 live_social">
								<div>Соцсети</div>
							</div>
						</div>
					</div>

					
					
					<div class="col-sm-9 col-md-9 col-lg-9 padding-0 live_content">
						<div class="col-sm-12 col-md-12 col-lg-12 padding-0 live_smi_content">
							<div>
								<marquee behavior="scroll" direction="left" id="marquee_smi">
									<?foreach($news_items_array as $item){?>
										<span><?=date("H:i", $item['date'])?> </span>
										<b><?=$item['resource_name']?>:</b> <?if($item['text']=='') echo $item['title']; else echo $item['text'];?>
										<?echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";?>
									<?}?>
								</marquee>
							</div>
						</div>
						
						<div class="col-sm-12 col-md-12 col-lg-12 padding-0 live_social_content">
							<div>
								<marquee behavior="scroll" direction="left" id="marquee_social">
									<?foreach($news_posts_array as $item){?>
										<span><?=date("H:i", $item['date'])?> </span>
										<b><?=$item['resource_name']?>:</b> <?=$item['text']?>
										<?echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";?>
									<?}?>
								</marquee>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="second" class="secondanimation">
			<img src="/images/one.png" alt="Lions"/>
		</div>
		<div id="third" class="thirdanimation">
			<img src="/images/two.png" alt="Snowalker"/>	
		</div>

		
		<?
		if (!@fopen('http://dashboard.imas.kz/images/three.png','r')){ 
		}else{?>
			<div id="fourth" class="fourthanimation">
					<img src="/images/three.png" alt="Snowalker"/>	
			</div>
		<?}?>
		
		<?
		if (!@fopen('http://dashboard.imas.kz/images/four.png','r')){ 
		}else{?>
			<div id="five" class="fivethanimation">
					<img src="/images/four.png" alt="Snowalker"/>	
			</div>
		<?}?>
	</div>







<!-- Mainly scripts -->
<script src="/d_js/analytics.js"></script>
<script src="/d_js/bootstrap.min.js"></script>
<script src="/d_js/jquery.metisMenu.js"></script>
<script src="/d_js/jquery.slimscroll.min.js"></script>

<!-- Flot -->
<script src="/d_js/jquery.flot.js"></script>
<script src="/d_js/jquery.flot.tooltip.min.js"></script>
<script src="/d_js/jquery.flot.spline.js"></script>
<script src="/d_js/jquery.flot.resize.js"></script>
<script src="/d_js/jquery.flot.pie.js"></script>

<!-- Peity -->
<script src="/d_js/jquery.peity.min.js"></script>
<?// <script src="d_js/peity-demo.js"></script> ?>

<!-- Custom and plugin javascript -->
<?// <script src="d_js/inspinia.js"></script> ?>
<?// <script src="d_js/pace.min.js"></script> ?>

<!-- jQuery UI -->
<script src="/d_js/jquery-ui.min.js"></script>

<!-- GITTER -->
<script src="/d_js/jquery.gritter.min.js"></script>

<!-- Sparkline -->
<script src="/d_js/jquery.sparkline.min.js"></script>

<!-- Sparkline demo data  -->
<?// <script src="d_js/sparkline-demo.js"></script> ?>

<!-- ChartJS-->
<script src="/d_js/Chart.min.js"></script>

<!-- Toastr -->
<script src="/d_js/toastr.min.js"></script>	
<script src="/d_js/chosen.jquery.js"></script>	


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<span style="display:none" id="stat_day_hidden"></span>
<!-- Графики -->

<script>
// setInterval(function(){
	// $('.highcharts-name-казахстан').css('fill', '#FF8C00');
	// setTimeout(function(){
		// $('.highcharts-name-казахстан').css('fill', '#02349a');
	// },1000*2);
// },1000*5);


// setInterval(function(){
	// $('.highcharts-name-россия').css('fill', '#de9141');
	// setTimeout(function(){
		// $('.highcharts-name-россия').css('fill', '#597abc');
	// },1000*2);
// },1000*120);

// setInterval(function(){
	// $('.highcharts-name-сша').css('fill', '#de9141');
	// setTimeout(function(){
		// $('.highcharts-name-сша').css('fill', '#597abc');
	// },1000*2);
// },1000*60);

</script>

<script>

function get_ajax(type,id){
	$.ajax({ 
		type : "GET",			
		url: "/ajax_update.php", 					
		data:{type:type},
		success: function(data){
			update_numbers(id,data);
		}  
	});
}

	function sort_table_smi(res_id){
		$.ajax({ 
			type : "GET",			
			url: "/ajax_update.php", 					
			data:{type:'top_smi', res_id:res_id},
			success: function(data){
				$("#trsmi_"+res_id).html(data);
			}  
		});
	}


	function sort_table_social(res_id){
		$.ajax({ 
			type : "GET",			
			url: "/ajax_update.php", 					
			data:{type:'top_social', res_id:res_id},
			success: function(data){
				$("#tr_"+res_id).html(data);
			}  
		});
	}
	//alert('res_id');


function add_to_marque_smi(){
	$.ajax({ 
		type : "GET",			
		url: "/ajax_update.php", 					
		data:{type:'news_items'},
		success: function(data){
			$("#marquee_smi").append(data);
		}  
	});
}
function add_to_marque_social(){
	$.ajax({ 
		type : "GET",			
		url: "/ajax_update.php", 					
		data:{type:'news_posts'},
		success: function(data){
			$("#marquee_social").append(data);
		}  
	});
}

function update_numbers(id,finish){
	var text=$('#'+id).text();
	var currentNumber = parseInt(text.replace(/ /gi, ""));
	$({numberValue: currentNumber}).animate({numberValue: parseInt(finish.toString().replace(/ /gi, ""))}, {
		duration: 60000,
		easing: 'linear',
		step: function() { 
			var ready_number=Math.ceil(this.numberValue).toString();
			$('#'+id).text(ready_number.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, "$1 ")); 
		}
	});
}


$(document).ready(function () {
	update_numbers('smi_social_day','<?echo number_format($smi_today + $social_today, 0, ',', ' ' );?>');
	update_numbers('smi_day','<?echo number_format($smi_today, 0, ',', ' ' );?>');
	update_numbers('social_day','<?echo number_format($social_today, 0, ',', ' ' );?>');
	update_numbers('smi_social_arhiv','<?echo number_format($smi_total + $social_total, 0, ',', ' ' );?>');
	update_numbers('smi_arhiv','<?echo number_format($smi_total, 0, ',', ' ' );?>');
	update_numbers('social_arhiv','<?echo number_format($social_total, 0, ',', ' ' );?>');
});


setInterval(function () {add_to_marque_social();}, 60000*5);
setInterval(function () {add_to_marque_smi();}, 60000*5);

setInterval(function () {get_ajax('day_count','smi_social_day');}, 60000);
setInterval(function () {get_ajax('day_count_smi','smi_day');}, 60000);
setInterval(function () {get_ajax('day_count_social','social_day');}, 60000);
setInterval(function () {get_ajax('total_count','smi_social_arhiv');}, 60000);
setInterval(function () {get_ajax('total_count_smi','smi_arhiv');}, 60000);
setInterval(function () {get_ajax('total_count_social','social_arhiv');}, 60000);

$(document).ready(function () {
	Highcharts.chart('container_2', {
		chart: {
			backgroundColor: '#252326',
			polar: true,
			type: 'spline',
			animation: Highcharts.svg, // don't animate in old IE
			marginRight: 10,
			events: {
				load: function () {
					// set up the updating of the chart each second
					var series1 = this.series[0];
					var series2 = this.series[1];
					var series3 = this.series[2];
					setInterval(function () {
						get_ajax('stat_day','stat_day_hidden');
						var y=$("#stat_day_hidden").html();
						var arr = y.split('&&');
						y2 = parseInt(arr[0]);
						y3 = parseInt(arr[1]);
						y1 = y2+y2;
						series1.addPoint([y1]);
						series2.addPoint([y2]);
						series3.addPoint([y3]);
					}, 60000);
				}
			}
		},
		title: {
			text: ''
		},
		
		credits: {
			enabled: false
		},
		
		subtitle: {
			text: ''
		},
		xAxis: {
			type: 'datetime', 
			ordinal: false,
			tickColor: '#161417',
			tickWidth: 0,
			lineColor: '#161417',
			lineWidth: 1,
			labels: {
				style: {
					color: '#fff'
				}
			}
		},
			
		yAxis: {
			title: {
				text: ''
			},
			labels: {
				style: {
					color: '#fff'
				}
			},
			gridLineColor: '#161417'
		},
		plotOptions: {
			line: {
				dataLabels: {
					enabled: false
				},
				enableMouseTracking: false
			},
			
			series: {
				lineWidth: 2,	
				<?$year=date("Y")-1900;
				 $month=date("m")-1;?>		
				pointStart: Date.UTC(<?=$year?>, <?=$month?>, <?=date("d")?>, 0, 1, 0, 0),
				pointInterval: 60 * 1000,
				marker: {
					enabled: false
				}
			}
		},
		series: [{
			name: 'Всего публикаций',         
			 data: [<?echo $statistic_all_day;?>],
			color: '#68a4ec'
		}, {
			name: 'Публикации СМИ',
		   data: [<?echo $statistic_smi_day;?>],
			color: '#74b283'
		}, {
			name: 'Публикации соцсети',
			data: [<?echo $statistic_social_day;?>],
			color: '#de9141'
		}],
		exporting: {
			enabled: false
		}
	});

	Highcharts.chart('container_1', {
		chart: {
			backgroundColor: '#252326',
			polar: true,
			type: 'spline'
		},
		title: {
			text: ''
		},
		
		credits: {
			enabled: false
		},
		
		
		subtitle: {
			text: ''
		},
		xAxis: {
			categories: [<?echo "'".implode("','",$date_grafics_1)."'";?>],
			
			tickColor: '#161417',
			tickWidth: 0,
			lineColor: '#161417',
			lineWidth: 1,
			labels: {
				style: {
					color: '#fff'
				}
			}
		},
		yAxis: {
			title: {
				text: ''
			},
			labels: {
				style: {
					color: '#fff'
				}
			},
			gridLineColor: '#161417'
		},
		plotOptions: {
			line: {
				dataLabels: {
					enabled: false
				},
				enableMouseTracking: false
			},
			
			series: {
				lineWidth: 2,
				marker: {
					enabled: false
				}
			}
		},
		series: [{
			name: 'Всего публикаций',
			data: [<?echo $statistic_all;?>],
			color: '#68a4ec',
		}, {
			name: 'Публикации СМИ',
			data: [<?echo $statistic_smi;?>],
			color: '#74b283',
		}, {
			name: 'Публикации соцсети',
			data: [<?echo $statistic_social;?>],
			color: '#de9141',
		}],
		
		exporting: {
			enabled: false
		}
	});
});
</script>
 <script src="http://code.highcharts.com/maps/modules/map.js"></script>
<script src="/d_js/kz-all.js"></script>
<script src="/d_js/world.js"></script>


<!-- Карта мира -->
<script>
$(function () {
	var data = [
		<?foreach($map_world_array as $sm):?>
			['<?=$sm['hc']?>', <?=$sm['news_count']?>],
		<?endforeach;?>
	];
    // Initiate the chart
    Highcharts.mapChart('container_maps_world', {
		chart: {
			backgroundColor: '#252326',
			polar: true,
			map: 'custom/world'
		},
		
		credits: {
			enabled: false
		},
		
		exporting: {
			enabled: false
		},
		
		mapNavigation: {
			enabled: false,
		},
		
        title: {
            text: ''
        },

        colorAxis: {
            min: 1,
            type: 'logarithmic',

        },

        legend: {
            title: {
                text: ''
            }
        },

		series: [{
			data: data,
			name: 'Карта',
			states: {
				hover: {
					color: '#BADA55',
				}
			},
			dataLabels: {
				enabled: false,
				format: '{point.name}'
			},		
		}]
    });
});
</script>


<!-- Карта КЗ -->
<script>
	var data = [
		<?foreach($map_kz_array as $sm):?>
			<?/*if($sm['hc']!='kz-as'&&$sm['hc']!='kz-ac'){*/?>['<?=$sm['hc']?>', <?=$sm['news_count']?>],<?/*}*/?>
		<?endforeach;?>
	];

// Create the chart
Highcharts.mapChart('container_maps_rk', {
    chart: {
		backgroundColor: '#252326',
		polar: true,
        map: 'countries/kz/kz-all'
    },

    title: {
        text: ''
    },

	credits: {
        enabled: false
    },
	
	exporting: {
        enabled: false
    },
	
	mapNavigation: {
        enabled: false,
    },
	
    subtitle: {
        text: ''
    },

    colorAxis: {
        min: 0
    },

    series: [{
        data: data,
        name: 'Карта',
        states: {
            hover: {
                color: '#BADA55',
            }
        },
        dataLabels: {
            enabled: false,
            format: '{point.name}'
        },		
    }]
});
</script>


	<script src="/d_js/jquery-1.4.2.min.js" type="text/javascript"></script>
	<script src="/d_js/jquery.easing.1.3.js" type="text/javascript"></script>
	<script src="/d_js/jquery.quicksand.min.js" type="text/javascript"></script>
<!-- Таблица -->
<script>
function update_table_smi(){	
	// if($(".sort_table_smi").attr('name') != ''){	
	// $(".sort_table_smi").attr('name', '');
	
	<?foreach($top_smi_array as $top_smi){?>
		sort_table_smi(<?=$top_smi['res_id']?>);
	<?}?>
	setTimeout(function(){
		(function($) {
			$.fn.sorted = function(customOptions) {
				var options = {
					reversed: false,
					by: function(a) { return parseFloat(a.text()); }
				};
				$.extend(options, customOptions);
				$data = $(this);
				arr = $data.get();
				arr.sort(function(a, b) {
					var valA = options.by($(a));
					var valB = options.by($(b));
					if (options.reversed) {
						return (valA < valB) ? -1 : (valA > valB) ? 1 : 0;				
					} else {		
						return (valA < valB) ? 1 : (valA > valB) ? -1 : 0;	
					}
				});
				return $(arr);
			};
		})(jQuery);
	  

		// Выполняется после загруки DOM
		$(function() {
			var $applications = $('#applications_smi');
			var $data = $applications.clone();
			var $filteredData = $data.find('tr');
			var $sortedData = $filteredData.sorted({
				by: function(v) {
					return parseFloat($(v).find('span[data-type=smi_size]').text());
				}
			});
			$applications.quicksand($sortedData, {
				duration: 1000*2.5,
				easing: 'easeInOutQuad'
			});
			
		});
	// }
	},1000);
	
}
setInterval(function(){
	update_table_smi();
},1000*60);



function update_table_social(){	
	// if($(".sort_table_social").attr('name') != ''){	
	// $(".sort_table_social").attr('name', '');
	<?foreach($top_social_array as $top_social){?>
		sort_table_social(<?=$top_social['res_id']?>);
	<?}?>
	setTimeout(function(){
		(function($) {
			$.fn.sorted = function(customOptions) {
				var options = {
					reversed: false,
					by: function(a) { return parseFloat(a.text()); }
				};
				$.extend(options, customOptions);
				$data = $(this);
				arr = $data.get();
				arr.sort(function(a, b) {
					var valA = options.by($(a));
					var valB = options.by($(b));
					if (options.reversed) {
						return (valA < valB) ? -1 : (valA > valB) ? 1 : 0;				
					} else {		
						return (valA < valB) ? 1 : (valA > valB) ? -1 : 0;	
					}
				});
				return $(arr);
			};
		})(jQuery);
	  

		// Выполняется после загруки DOM
		$(function() {
			var $applications = $('#applications_social');
			var $data = $applications.clone();
			var $filteredData = $data.find('tr');
			var $sortedData = $filteredData.sorted({
				by: function(v) {
					return parseFloat($(v).find('span[data-type=size_social]').text());
				}
			});
			$applications.quicksand($sortedData, {
				duration: 1000*2.5,
				easing: 'easeInOutQuad'
			});
			
		});
	// }
	<?/*foreach($top_social_array as $top_social){?>
		setTimeout(function(){sort_table_social(<?=$top_social['res_id']?>);},1000*10);
	<?}*/?>
	},1000);
}
setInterval(function(){
		update_table_social();
},1000*60);
</script>
 


</body>
</html>