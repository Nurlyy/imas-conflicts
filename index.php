<?php
require __DIR__ . '/auth.php';
$login = getUserLogin();

if ($login === null){
	header("Location: /login.php");
	exit();
} else{
$control = 'mvd';
// Блок Медиарейтинг
$host = 'localhost';
$username = 'v-40047_mms';
$password = 'R703U1ke';
$name = 'v_40047_special';
$connect = mysqli_connect($host, $username, $password, $name) or die("Could not connect: " . mysqli_error($connect));
mysqli_query($connect,"SET NAMES 'utf8'"); 
mysqli_query($connect,"SET CHARACTER SET 'utf8'");
mysqli_query($connect,"SET SESSION collation_connection = 'utf8_general_ci'");
$period='month';
$t='A.vsego as vsego, A.pozitiv as pozitiv, A.neitral as neitral, A.smeshannyi as smeshannyi, A.negativ as negativ, A.dannye as dannye';
switch($period) {
	case 'month': $t='A.vsego as vsego, A.pozitiv as pozitiv, A.neitral as neitral, A.smeshannyi as smeshannyi, A.negativ as negativ, A.dannye as dannye';
	break;
	case 'week': $t='A.vsego_week as vsego, A.pozitiv_week as pozitiv, A.neitral_week as neitral, A.smeshannyi_week as smeshannyi, A.negativ_week as negativ, A.dannye_week as dannye';
	break;
	case 'year': $t='A.vsego_year as vsego, A.pozitiv_year as pozitiv, A.neitral_year as neitral, A.smeshannyi_year as smeshannyi, A.negativ_year as negativ, A.dannye_year as dannye';
	break;
	case 'day': $t='A.vsego_day as vsego, A.pozitiv_day as pozitiv, A.neitral_day as neitral, A.smeshannyi_day as smeshannyi, A.negativ_day as negativ, A.dannye_day as dannye';
	break;
	case 'total': $t='A.vsego_total as vsego, A.pozitiv_total as pozitiv, A.neitral_total as neitral, A.smeshannyi_total as smeshannyi, A.negativ_total as negativ, A.dannye_total as dannye';
	break;
}
	
$query = "SELECT U.id, U.s_id, U.name, U.dolzhnost, U.image, U.vk, U.facebook, U.instagram, U.twitter, ".$t." FROM user U, mvd A where U.id = A.user_id ORDER BY vsego DESC";
$sql_reiting= mysqli_query($connect,$query);
$table1=array();
while($row = mysqli_fetch_array($sql_reiting)){
	$table1[]=$row;
}

$query2 = "SELECT U.id, U.s_id, U.name, U.dolzhnost, U.image, U.vk, U.facebook, U.instagram, U.twitter, ".$t." FROM user U, mvd2 A where U.id = A.user_id ORDER BY vsego DESC LIMIT 20";
$sql_reiting2= mysqli_query($connect,$query2);
$table2=array();
$table2_ids_array=array();
while($row = mysqli_fetch_array($sql_reiting2)){
	$table2[]=$row;
	$table2_ids_array[]=$row['id'];
}


$active_tab=1;
$active_project=0;
$category=0;
if(isset($_GET['category'])) {
	$category=(int) $_GET['category'];
	if(in_array($category,$table2_ids_array)) $active_tab=2;
}



if($category==0) {
	$query_dynamic="Select `date`, SUM(`smi`) as smi, SUM(`social`) as social, SUM(`pos`) as pos, SUM(`neg`) as neg, SUM(`neu`) as neu From mvd_dynamic GROUP BY date order by date ";
	$query_counts="Select SUM(`smi_today`) as smi_today, SUM(`social_today`) as social_today, SUM(`smi_total`) as smi_total, SUM(`social_total`) as social_total From mvd_counts_stats";
	$query_map="Select `hc`, SUM(`news_count`) as news_count From mvd_map_kz GROUP BY hc";
	$query_live_posts="Select * From mvd_live_posts WHERE date<=".time()." GROUP BY news_id ORDER BY date DESC LIMIT 15";
	$query_live_items="Select * From mvd_live_items WHERE date<=".time()." GROUP BY news_id ORDER BY date DESC LIMIT 15";
}
else {
	$query_dynamic="Select `date`, `smi`, `social`, `pos`, `neg`, `neu` From mvd_dynamic WHERE category_id=".$category." order by date";
	$query_counts="Select SUM(`smi_today`) as smi_today, SUM(`social_today`) as social_today, SUM(`smi_total`) as smi_total, SUM(`social_total`) as social_total From mvd_counts_stats";
	// $query_counts="Select `smi_today`, `social_today`, `smi_total`, `social_total` From mvd_counts_stats WHERE category_id=".$category;
	$query_map="Select `hc`, `news_count` From mvd_map_kz WHERE category_id=".$category;
	$query_live_posts="Select * From mvd_live_posts WHERE date<=".time()." AND category_id=".$category." ORDER BY date DESC LIMIT 15";
	$query_live_items="Select * From mvd_live_items WHERE date<=".time()." AND category_id=".$category." ORDER BY date DESC LIMIT 15";
}
$sql_statistic = mysqli_query($connect,$query_dynamic)or die(mysqli_error($connect));
while($table_statistic = mysqli_fetch_array($sql_statistic)){	
	$statistic_array[] = $table_statistic;
	$statistic_date_array[] = $table_statistic['date'];
	$statistic_smi_array[] = $table_statistic['smi'];
	$statistic_social_array[] = $table_statistic['social'];
	$statistic_pos_array[] = $table_statistic['pos'];
	$statistic_neg_array[] = $table_statistic['neg'];
	$statistic_neu_array[] = $table_statistic['neu'];
}
foreach($statistic_array as $statistic){
	$statistic_all_array[] = $statistic['smi'] + $statistic['social'];
}
$statistic_smi = implode(',', $statistic_smi_array);
$statistic_social = implode(',', $statistic_social_array);
$statistic_all = implode(',', $statistic_all_array);
$statistic_neg = implode(',', $statistic_neg_array);
$statistic_neu = implode(',', $statistic_neu_array);
$statistic_pos = implode(',', $statistic_pos_array);
foreach($statistic_date_array as $statistic_date){
	$dt=explode("-",$statistic_date);
	$date_grafics_1[] = $dt[2].'.'.$dt[1].'.'.$dt[0];
}


$sql_dashboard = mysqli_query($connect,$query_counts)or die(mysqli_error($connect));
while($table_dashboard = mysqli_fetch_array($sql_dashboard)){
	$smi_today = 			 $table_dashboard['smi_today'];
	$social_today = 		 $table_dashboard['social_today'];
	$smi_total = 			 $table_dashboard['smi_total'];
	$social_total = 		 $table_dashboard['social_total'];
	$smi_resource_count = 	 $table_dashboard['smi_resource_count'];
	$social_resource_count = $table_dashboard['social_resource_count'];
	$smi_resource_kz_count = $table_dashboard['smi_resource_kz_count'];
}


$sql_map_kz= mysqli_query($connect,$query_map)or die(mysqli_error($connect));
while($table_map_kz = mysqli_fetch_array($sql_map_kz)){
	$map_kz_array[] = $table_map_kz;
}

$time=mktime(date("H"),date("i")-5,date("s"),date("m"),date("d"),date("Y"));
$sql_news_items= mysqli_query($connect,$query_live_items)or die(mysqli_error($connect));
while($table_news_items = mysqli_fetch_array($sql_news_items)){
	if($table_news_items['text']!='') $news_items_array[] = $table_news_items;
}
$sql_news_posts= mysqli_query($connect,$query_live_posts)or die(mysqli_error($connect));
while($table_news_posts = mysqli_fetch_array($sql_news_posts)){
	if($table_news_posts['text']!='') $news_posts_array[] = $table_news_posts;
}
?>




<html>
<head> 
	<link href="d_css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="d_css/toastr.min.css" rel="stylesheet">
    <link href="d_css/jquery.gritter.css" rel="stylesheet">
    <link href="d_css/animate.css" rel="stylesheet">
    <link href="d_css/style.css" rel="stylesheet">
	<link href="d_css/deshbord_style.css" rel="stylesheet">
	<link href="d_css/load_modal.css" rel="stylesheet">
	<link href="d_css/bootstrap-chosen.css" rel="stylesheet">
    <title>iMAS Dashboard</title>
	<link rel="stylesheet" type="text/css" href="nav/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="nav/jquery.fullPage.min.css" />
	<link id="animated_stylesheet" rel="stylesheet" type="text/css" href="nav/animate.css" />
	<link rel="stylesheet" type="text/css" href="nav/scrollbox.min.css" media="screen">
	<link rel="stylesheet" type="text/css" href="nav/style.css" />
	<link rel="stylesheet" type="text/css" href="nav/odometer.css" />
	<link rel="stylesheet" type="text/css" href="nav/owl.carousel.css" />
	<link rel="stylesheet" type="text/css" href="nav/owl.theme.css" />
	

	<script src="/d_js/jquery-3.1.1.min.js"></script>
	<?/*<style>
		#container_1 .highcharts-xaxis-labels{
			display: none !important;
		}
	</style>*/?>
	<style>
		table.table-sorter {
			display: table-caption;
			max-width: 1366px;
			min-height: 857px;
			margin:0 auto;
			color: #fff;
			// background-color: #fff;
			// border-radius: 10px;
			// box-shadow: 0px 0px 10px 0px #c1c0c0;
			}
		
		th {
			max-height: 30px;
			// background-color: #fff;
			color: #a5a5a5;	
			border-bottom: 1px solid #d1d1d1;
			 }
			
		#th-1{
			width: 300px;
			max-width: 300px;
			min-width: 300px;
			border-radius: 10px 0px 0px 0px;
			border-right: 1px solid #d1d1d1;
			cursor:pointer;}		
		#th-2{
			width: 226px;
			max-width: 226px;
			min-width: 226px; 
			border-right: 1px solid #d1d1d1;
			border-left: 1px solid #d1d1d1;
			cursor:pointer;}		
		#th-3{
			width: 730px;
			max-width: 730px;
			min-width: 730px;
			border-radius: 0px 10px 0px 0px;
			border-right: 0px solid #d1d1d1;}
			
		#td-1{
			background-color:none;
			width: 300px;
			max-width: 300px;
			min-width: 300px;
			padding: 5px 10px 0px 0px; }		
		#td-2{
			background-color:none;
			width: 226px;
			max-width: 226px;
			min-width: 226px;
			padding: 5px 10px 0px 10px; }		
		#td-3{
			background-color:none;
			width: 730px;
			max-width: 730px;
			min-width: 730px;
			padding: 5px 10px 0px 10px; }		

		#td-100{
			height: 300px;
			max-height: 300px;
			min-height: 300px;	
		}
		
		/*Кнопки сортировки по убыванию 9..1*/
		#triangle_0,#triangle_1,#triangle_2,#triangle_3,#triangle_4,#triangle_5{
			width: 10px; 
			height: 6px;
			border: 10px solid transparent;
			border-top: 6px solid #ababab; 
			margin: 0 auto;
			cursor:pointer;}
		
		/*Круги (позитив, нейтрал, негатив, смешанный)*/
		#div-th-pozitiv{
			width: 20px;
			height: 20px;
			border: 2px solid #fff;
			border-radius: 100%;
			background-color: #489229;
			float: left;
			margin-top: 4px;}
		#div-th-neitral{
			width: 20px;
			height: 20px;
			border: 2px solid #fff;
			border-radius: 100%;
			background-color: #d4d81d;
			float: left; 
			margin-top: 4px; }
		#div-th-negativ{
			width: 20px;
			height: 20px;
			border: 2px solid #fff;
			border-radius: 100%;
			background-color: rgb(162,12,14);
			float: left;
			margin-top: 4px; }
		#div-th-smeshannyi{
			width: 20px;
			height: 20px;
			border: 2px solid #fff;
			border-radius: 100%;
			background-color: #7c808c;
			float: left;
			margin-top: 4px; }
			
		/*Круг (всего, позитив, нейтрал, негатив, смешанный, дополнительно)*/
		#div-td-vsego,#div-td-pozitiv,#div-td-neitral,#div-td-negativ,#div-td-smeshannyi,#div-td-dannye{
			width: fit-content;
			padding: 0px 10px;
			height: 16px;
			border-radius: 5px;
			background-color: #fff;
			position: relative;
			margin: auto;
			margin-top: -6px; }
		#div-td-vsego{
			border: 1px solid #25aae9;	
			z-index: 13; }
		#div-td-pozitiv{
			border: 1px solid #1bbc9b; 
			z-index: 13; }
		#div-td-neitral{
			border: 1px solid #f1c40f;
			z-index: 12; }
		#div-td-smeshannyi{
			border: 1px solid #d0d5d7;
			z-index: 10; }
		#div-td-negativ{
			border: 1px solid #e64b3c;
			z-index: 11; }	
		#div-td-dannye{
			border: 1px solid rgb(162,12,14);
			z-index: 9; }
		
		/*Ячейки (позитив, нейтрал, негатив, смешанный)*/
		#p-th-people,#p-th-vsego{
			/*height: 40px;*/
			font-family: 'Open-sans' sans serif;
			font-weight: 500;
			font-weight: normal;
			text-align:center;
			margin-top: 12px;
			// background: linear-gradient(to top, #fbfbfb, #fff);}
		
		#p-th-pozitiv,#p-th-neitral,#p-th-negativ,#p-th-smeshannyi{
			width: 115px;
			/*height: 40px;*/
			text-align: center;
			float: left;
			font-family: 'Open-sans' sans serif;
			font-weight: 500;
			font-weight: normal;
			border-right: 1px solid #d1d1d1;
			border-left: 1px solid #fff;
			padding-top: 12px;
			// background: linear-gradient(to top, #fbfbfb, #fff);
			cursor:pointer;
			/*box-shadow: 0 0 10px #cfcfcf;*/
			}	
		
		/*Ячейка период*/
		#p-th-period{
			width: 269px;
			/*height: 40px;*/
			font-family: 'Open-sans' sans serif;
			font-weight: 500;
			font-weight: normal;
			float: left;
			text-align: center;
			padding-top: 12px;
			padding-bottom: 12px;
			border-radius: 0px 10px 0px 0px;
			border-left: 1px solid #fff;
			// background: linear-gradient(to top, #fbfbfb, #fff);
			/*box-shadow: 0 0 10px #cfcfcf;*/ }	
		
		
		/*Значение данных (всего, позитив, нейтрал, негатив, смешанный, дополнительно) в цифрах и в проценнтах*/
		#p-td-vsego,#p-td-pozitiv,#p-td-neitral,#p-td-negativ,#p-td-smeshannyi,#p-td-dannye{
			font-family: 'Open-sans' sans serif;
			font-size: 10px;
			text-align: center;
			/*margin-top: 20px;*/ }

		.descr{
			display:none;
			margin-left: 24px;
			padding: 2px;
			background:  #f6f6f6;
			height: 24px;
			color: #000;
			border: 2px solid #7c808c;
			border-radius: 5px;
			text-align: center;
			z-index: 99;
			font-weight: 500;
			font-size: 100%;}

		.poster:hover .descr{
			/* display: block; */ display:none;
			position: relative;
			top: 0px;
			z-index: 99;
			width: 150px;
		}
		
		#div-img{
			width: 32px;
			height: 36px;
			max-width: 32px;
			max-height: 32px;
			float:left;
			margin-left: 4%;
			position: relative;
		}
		
		.div_fio span{
			font-size: 0.7em; }
			
		.div_fio b{
			font-size: 0.7em; }
			
		.div_fio{
			width: 80%; 
			float:right; 
			margin-left: 4%; 
			height: 40px; }
			
		.news-content-sentiment{
			padding-left: 24px; }
		
		.glyphicon-one-fine:before {
			content:"\25cf";
			font-size: 25px;
			cursor:pointer; }
			
		.sentiment1 {
			background-color:#1BB394; }
			
		.sentiment0 {
			background-color:#F2C94C; }
			
		.sentiment-1 {
			background-color:#EC5D5D; }
			
		.bg_total{
			background-color: #357EBD;
		}
		
		.highcharts-series-0 text{
			font-size: 10px !important;
			font-weight: inherit !important;
			fill: #357EBD !important;
		}
		.highcharts-series-1 text{
			font-size: 10px !important;
			font-weight: inherit !important;
			fill: #7ddcdc !important;
		}
		.highcharts-series-2 text{
			font-size: 10px !important;
			font-weight: inherit !important;
			fill: #6ab9fd !important;
		}

		.highcharts-series-3 text{
			font-size: 10px !important;
			font-weight: inherit !important;
			fill: #1BB394 !important;
		}
		.highcharts-series-4 text{
			font-size: 10px !important;
			font-weight: inherit !important;
			fill: #EC5D5D !important;
		}
		.highcharts-series-5 text{
			font-size: 10px !important;
			font-weight: inherit !important;
			fill: #F2C94C !important;
		}
		.nav_top span {
			color: #357EBD;
		}
		.nav-tabs {
			border-bottom: none;
		}
		.nav-pills {
			border-bottom: none;
			margin-top: 1px;
		}
		.nav-pills a{
			font-size: 16px;
			// color: #fff;
			line-height: 16px;
			font-weight: bold;
		}
		.nav-pills>li>a {
			color: #fff;
			background-color: none;
			border-radius: unset;
		}
		.nav-pills>li.active>a, .nav-pills>li.active>a:focus, .nav-pills>li.active>a:hover {
			color: #357ebd;
			background-color: #fff;
		}
		.nav > li.active {
			border-left: none;
			background: #fff;
		}
		.live_smi {
			color: #7ddcdc;
		}
		.live_social  {
			color: #6ab9fd;
		}
		.live_smi_content span {
			color: #7ddcdc;
		}
		.live_social_content span {
			color: #6ab9fd;
		}
		.day_smi_public {
			color: #7ddcdc !important;
		}
		.day_social_public {
			color: #6ab9fd !important;
		}
		.day_all_public {
			color: #357EBD !important;
		}
		.div_fio a{
			color: #fff;
		}
		.div_fio {
			height: 34px;
		}
		.active_category {
			color: #357EBD !important;
		}
	</style>

</head>
 
<body style="background: #252326;">
<div class="col-sm-12 col-md-12 col-lg-12 padding-0 body_content">
	<!-- Навигационное меню -->
	<nav class="nav_top col-sm-12 col-md-12 col-lg-12 padding-0">
			<a class="navbar-brand" href="http://imas.kz"><img class="img-responsive col-xs-12" src="https://cabinet.imas.kz/media/img/imas_logo_en_blue.png" style="width:240px;"></a>
		<div class="text">
			<span>Самая мощная казахстанская система мониторинга и анализа <br>информационных потоков в режиме реального времени</span>
		</div>
		
		<div class="madeinkz">
			<img class="img-responsive" src="/images/madeinkz.png" style="zoom: 70%;"> 
		</div>
		<div class="madeinkz" style="height: 50px; line-height:50px; padding:0;">
			<a href="/results.php"><b>Результаты работы</b></a>
		</div>
		
		<div class="number_one">
			<img src="/icon/number_one.jpg" style="height: 50px; float: left;">
			<div class="text">
				<span id="system_text">Система в Казахстане <br>в режиме реального времени</span>
			</div>
		</div>
		<div class="date">

			<script>
			function Clock_ms() { 
				var monthsArr = ["Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", 
				"Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"];

				var daysArr = ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"];
				
				
				/* Настройки внешнего вида */ 
				var c_h  =      '#357EBD';   // Цвет часов 
				var c_m  =      '#357EBD';  // Цвет минут 
				var c_s  =      '#357EBD';  // Цвет секунд 
				var c_ms =      '#357EBD';  // Цвет миллисекунд 
				var sep  =      '#357EBD';  // Цвет разделителей 

			  
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


	<div class="col-sm-12 col-md-12 col-lg-12 padding-0"> 
		<div class="col-sm-8 col-md-8 col-lg-8 padding-0"> 
			<div class="col-sm-12 col-md-12 col-lg-12 padding-0" height="90%">
				<ul class="nav nav-pills" id="tabs-mvd">
					<li <?if($active_tab==1){?>class="active"<?}?> id="mvd-tab-button"><a href="#mvd-tab-content" onclick="show_tab1();">Сферы управления</a></li>
					<li <?if($active_tab==2){?>class="active"<?}?> id="mvd2-tab-button"><a href="#mvd2-tab-content" onclick="show_tab2();">ТОП проблемных тем</a></li>
					<?if($category!=0){?><li id="all-tab-button"><a href="/">Показать всё</a></li><?}?>
				</ul>

				<div class="tab-content">
					<div id="mvd-tab-content" class="tab-pane fade <?if($active_tab==1){?>in active<?}?>">
						<table border='0' cellspacing='0' class='table-sorter' style="min-height: auto;">
							<thead style="display:none">
								<tr>
									<th id='th-1'>
										<div id='p-th-people'class='sort_strong' onclick='sort_table_strong(0);' name="sort_table_strong(0)">По алфавиту
											<div id='triangle_0' ></div>
										</div>
									</th>
										
									<th id='th-2'>
										<div id='p-th-vsego' class='sort_table' onclick='sort_table_visual_desc(1);' name="sort_table_visual_desc(1)">Число публикаций
											<div id='triangle_1' ></div>
										</div>
									</th>
									
									<th id='th-3'>
										<div id='p-th-pozitiv' class='sort_table' onclick='sort_table_visual_desc(2);' name="sort_table_visual_desc(2)">Позитив
											<div id='triangle_2'></div>
										</div>

										<div id='p-th-neitral' class='sort_table' onclick='sort_table_visual_desc(3);' name="sort_table_visual_desc(3)">Нейтрал
											<div id='triangle_3' ></div>
										</div>	

										<div id='p-th-negativ' class='sort_table' onclick='sort_table_visual_desc(4);' name="sort_table_visual_desc(4)">Негатив
											<div id='triangle_4'></div>
										</div>


										<div id='p-th-period'>
												<img style='width: 20px;  margin-left: 10px;' src='http://special.imas.kz/media/img/wathc.png'><span style="font-family: 'Open-sans' sans serif; font-weight: 500; font-weight: normal; margin-left: 10px;">Период:</span>
											
											<select style="background: none;" id="select-form" name="period" onchange="window.location.href='?period='+this.value">
												<?/*<option value="total" <?if($period=='total') echo 'selected';?>>с момента назначения </option>*/?>
												<?/*<option value="year" <?if($period=='year') echo 'selected';?>>за год</option>*/?>
												<option style="background: none;" value="month" <?if($period=='month') echo 'selected';?>>за месяц</option>
												<option style="background: none;" value="week" <?if($period=='week') echo 'selected';?>>за неделю</option>
												<option style="background: none;" value="day" <?if($period=='day') echo 'selected';?>>за сутки</option>
											</select>
										</div>
									</th>
								</tr>
							</thead>
							<?
							$y=1;
							$a[$y]=0;
							$b[$y]=0;
							$c[$y]=0;
							$d[$y]=0;
							$e[$y]=0;
							$f[$y]=0;
							$aw[$y]=0;
							$bw[$y]=0;
							$cw[$y]=0;
							$dw[$y]=0;
							$ew[$y]=0;
							$fw[$y]=0;
							$ccww[$y]=0;
							$ddww[$y]=0;	
							$eeww[$y]=0;
							foreach ($table1 as $user){
								$a[$y]=$user['vsego'];
								$b[$y]=$user['pozitiv'];
								$c[$y]=$user['neitral'];
								$d[$y]=$user['smeshannyi'];
								$e[$y]=$user['negativ'];
								$f[$y]=$user['dannye'];
								$aw[$y]=0;
								$bw[$y]=0;
								$cw[$y]=0;
								$dw[$y]=0;
								$ew[$y]=0;
								$fw[$y]=0;
								$ccww[$y]=0;
								$ddww[$y]=0;	
								$eeww[$y]=0;
								$y++;
							}
							$amax = max($a);
							$bmax = max($b);
							$cmax = max($c);
							$dmax = max($d);
							$emax = max($e);
							$fmax = max($f);
							$px = 100;

							for($y=1;$y<=count($a);$y++){
								if ($a[$y] != 0){
									if($amax!=0) $aw[$y] = round($a[$y] * $px) / $amax; else $aw[$y]=0;
									if($a[$y]!=0) $bw[$y] = round($b[$y] / $a[$y] * $px); else $bw[$y]=0;
									if($a[$y]!=0) $cw[$y] = round($c[$y] / $a[$y] * $px); else $cw[$y]=0;
									if($a[$y]!=0) $dw[$y] = round($d[$y] / $a[$y] * $px); else $dw[$y]=0;
									if($a[$y]!=0) $ew[$y] = round($e[$y] / $a[$y] * $px); else $ew[$y]=0;
									if($fmax!=0) $fw[$y] = round($f[$y] * $px) / $fmax; else $fw[$y]=0;
									if($a[$y]!=0) $ccww[$y] = ($c[$y] / $a[$y] * $px); else $ccww[$y]=0;
									if($a[$y]!=0) $ddww[$y] = ($d[$y] / $a[$y] * $px); else $ddww[$y]=0;
									if($a[$y]!=0) $eeww[$y] = ($e[$y] / $a[$y] * $px); else $eeww[$y]=0;
								}
								if ($bw[$y] + $cw[$y] + $dw[$y] + $ew[$y] < 100) {
									if ($ccww[$y] - $cw[$y] > $ddww[$y] - $dw[$y] && $ccww[$y] - $cw[$y] > $eeww[$y] - $ew[$y])
										$cw[$y] = $cw[$y] + 1;
									
									if ($ddww[$y] - $dw[$y] > $ccww[$y] - $cw[$y] && $ddww[$y] - $dw[$y] > $eeww[$y] - $ew[$y])
										$dw[$y] = $dw[$y] + 1;
									
									if ($eeww[$y] - $ew[$y] > $ccww[$y] - $cw[$y] && $eeww[$y] - $ew[$y] > $ddww[$y] - $dw[$y])
										$ew[$y] = $ew[$y] + 1;
									
									if ($bw[$y] + $cw[$y] + $dw[$y] + $ew[$y] < 100) {
										//Если данные ячеек равны между собой и меньше 100
										if ($bw[$y] == $cw[$y] && $dw[$y] > $ew[$y])
											$dw[$y] = $dw[$y] + 1;
										if ($bw[$y] == $cw[$y] && $dw[$y] < $ew[$y])
											$ew[$y] = $ew[$y] + 1;
										
										if ($bw[$y] == $dw[$y] && $cw[$y] > $ew[$y])
											$cw[$y] = $cw[$y] + 1;
										if ($bw[$y] == $dw[$y] && $cw[$y] < $ew[$y])
											$ew[$y] = $ew[$y] + 1;
										
										if ($bw[$y] == $ew[$y] && $cw[$y] > $dw[$y])
											$cw[$y] = $cw[$y] + 1;
										if ($bw[$y] == $ew[$y] && $cw[$y] < $dw[$y])
											$dw[$y] = $dw[$y] + 1;
										
										if ($cw[$y] == $dw[$y] && $bw[$y] > $ew[$y])
											$bw[$y] = $bw[$y] + 1;
										if ($cw[$y] == $dw[$y] && $bw[$y] < $ew[$y])
											$ew[$y] = $ew[$y] + 1;
										
										if ($cw[$y] == $ew[$y] && $bw[$y] > $dw[$y])
											$bw[$y] = $bw[$y] + 1;
										if ($cw[$y] == $ew[$y] && $bw[$y] < $dw[$y])
											$dw[$y] = $dw[$y] + 1;

										if ($dw[$y] == $ew[$y] && $bw[$y] > $cw[$y])
											$bw[$y] = $bw[$y] + 1;
										if ($dw[$y] == $ew[$y] && $bw[$y] < $cw[$y])
											$cw[$y] = $cw[$y] + 1;
									}
								}
									
								if ($bw[$y] + $cw[$y] + $dw[$y] + $ew[$y] == 101) 
									$bw[$y] = $bw[$y] - 1;
								
								if ($bw[$y] + $cw[$y] + $dw[$y] + $ew[$y] == 102) 
									$bw[$y] = $bw[$y] - 2;
								
								if ($bw[$y] + $cw[$y] + $dw[$y] + $ew[$y] == 103) 
									$bw[$y] = $bw[$y] - 3;
								
								if ($bw[$y] + $cw[$y] + $dw[$y] + $ew[$y] == 104) 
									$bw[$y] = $bw[$y] - 4;
							}
							$max_pozitiv = max($bw);
							$max_neitral = max($cw);
							$max_smeshannyi = max($dw);
							$max_negativ = max($ew);
							$i=0;?>
							<tbody id='applications'>
								<?foreach ($table1 as $user){
									if($category==$user['id']) $active_project=$user['s_id'];
									if($user['vsego']!=0){
										$i++;?>
										<tr data-id="id-<?=$i ?>">
											<td id='td-1' class='fio-<?=$i?>'>
												<?if($control == 'main2' || $control == 'akims2'){?>
													<div id='div-img'>
														<img style='width: 30px; height: 30px; border-radius: 50%;' src='<?=$user['image']?>'>
													</div>
												<?}?>
												<div class='div_fio' <?if($control != 'main2' && $control != 'akims2') echo 'style="width: 100% !important; padding-left: 4%;"'?>>
													<div style='margin: auto;'>
														<a href="https://cabinet.imas.kz/board?id=<?=$user['s_id']?>&t=all&token=TViCyutrwQ8Z" target="_blank"><i class="fa fa-desktop"></i></a>
														<a href="https://cabinet.imas.kz/tape?id=<?=$user['s_id']?>&t=all&token=TViCyutrwQ8Z" target="_blank"><i class="fa fa-newspaper-o"></i></a>
														<a href="/?category=<?=$user['id']?>" <?if($category==$user['id']){?>class='active_category'<?}?>><b><strong><?=$user['name']?></strong></b></a>
														<br>
														<span><?=$user['dolzhnost']?></span>
													</div>
												</div>
											</td>
											
											<td id='td-2'>
												<a href='http://special.imas.kz/mvd'>
													<div class="bg_total" style='width:<?=$aw[$i]?>%; height: 16px; float: left; border-radius: 7px;'>
														<div id='div-td-vsego'>
															<p id='p-td-vsego'>
																<span data-type='size1'>
																	<?=$user['vsego']?>
																</span>
															</p>
														</div>	
														<?if($user['vsego']==$amax){?><img src="http://special.imas.kz/media/img/1.png" style="float: right; margin-right: 10px; margin-top: -8px;"><?}?>
													</div>
													
												</a>
											</td>
											
											<td id='td-3'>					
											
												<a href='http://special.imas.kz/mvd'>
													<div class='poster sentiment1' style='width: <?=$bw[$i]?>%; height: 16px; float: left; border-radius: 7px 0 0 7px;<?if($user['pozitiv']==100){?>border-radius: 7px;<?}?>'>
														<div id='div-td-pozitiv' style='<? if ($user['pozitiv'] <= 0) {?> display: none;<?}?>' title="Позитив: <?=$user['pozitiv']?>">
															<p id='p-td-pozitiv'>
																<span data-type='size2'>
																	<?=$user['pozitiv']?>
																</span>
															</p>
															
															<div class='descr'>
																Позитив: <?=$bw[$i]?>%
															</div>
														</div>
														<?if($bw[$i]==$max_pozitiv){?><img src="http://special.imas.kz/media/img/1.png" style="float: right; margin-right: 10px; margin-top: -8px;"><?}?>
													</div>
												</a>
												
												<a href='http://special.imas.kz/mvd'>
													<div class='poster sentiment0' style='width: <?=$cw[$i]?>%; height: 16px; float: left; <?if($user['negativ']<=0 && $user['smeshannyi']<=0 && $user['pozitiv']>0){?>border-radius: 0px 7px 7px 0px;<?} elseif($user['negativ']<=0 && $user['smeshannyi']<=0 && $user['pozitiv']<=0){?>border-radius: 7px 7px 7px 7px;<?} elseif($user['negativ']>0 && $user['pozitiv']<=0){?>border-radius: 7px 0px 0px 7px;<?}?>'>
														<div id='div-td-neitral' style='<? if ($user['neitral'] <= 0) {?> display: none;<?} if ($cw[$i] <= 3) {?> margin-left: -10px; <?}?> ' title="Нейтрал: <?=$user['neitral']?>">
															<p id='p-td-neitral'>
																<span data-type='size3'>
																	<?=$user['neitral']?>
																</span>
															</p>
															<div class='descr'>
																Нейтрал: <?=$cw[$i]?>%
															</div>
														</div>	
														<?if($cw[$i]==$max_neitral){?><img src="http://special.imas.kz/media/img/1.png" style="float: right; margin-right: 10px; margin-top: -8px;"><?}?>
													</div>
												</a>
												<a href='http://special.imas.kz/mvd'>
													<div class='poster sentiment-1' style='width: <?=$ew[$i]?>%;  height: 16px; float: left; border-radius: 0px 7px 7px 0px;'>
														<div  id='div-td-negativ' style='<? if ($user['negativ'] <= 0) {?> display: none;<?}?>' title="Негатив: <?=$user['negativ']?>">
															<p id='p-td-negativ'>
																<span data-type='size4'>
																	<?=$user['negativ']?>
																</span>
															</p>
															<div class='descr'>
																Негатив: <?=$ew[$i]?>%
															</div>
														</div>	
														<?if($ew[$i]==$max_negativ){?><img src="http://special.imas.kz/media/img/1.png" style="float: right; margin-right: 10px; margin-top: -8px;"><?}?>
													</div>
												</a>
											</td>
										</tr>		
									<?}
								}?>
							</tbody>	
						</table>
					</div>
					<div id="mvd2-tab-content" class="tab-pane fade <?if($active_tab==2){?>in active<?}?>">
						<table border='0' cellspacing='0' class='table-sorter' style="min-height: auto;">
							<thead style="display:none">
								<tr>
									<th id='th-1'>
										<div id='p-th-people'class='sort_strong' onclick='sort_table_strong(0);' name="sort_table_strong(0)">По алфавиту
											<div id='triangle_0' ></div>
										</div>
									</th>
										
									<th id='th-2'>
										<div id='p-th-vsego' class='sort_table' onclick='sort_table_visual_desc(1);' name="sort_table_visual_desc(1)">Число публикаций
											<div id='triangle_1' ></div>
										</div>
									</th>
									
									<th id='th-3'>
										<div id='p-th-pozitiv' class='sort_table' onclick='sort_table_visual_desc(2);' name="sort_table_visual_desc(2)">Позитив
											<div id='triangle_2'></div>
										</div>

										<div id='p-th-neitral' class='sort_table' onclick='sort_table_visual_desc(3);' name="sort_table_visual_desc(3)">Нейтрал
											<div id='triangle_3' ></div>
										</div>	

										<div id='p-th-negativ' class='sort_table' onclick='sort_table_visual_desc(4);' name="sort_table_visual_desc(4)">Негатив
											<div id='triangle_4'></div>
										</div>


										<div id='p-th-period'>
												<img style='width: 20px;  margin-left: 10px;' src='http://special.imas.kz/media/img/wathc.png'><span style="font-family: 'Open-sans' sans serif; font-weight: 500; font-weight: normal; margin-left: 10px;">Период:</span>
											
											<select style="background: none;" id="select-form" name="period" onchange="window.location.href='?period='+this.value">
												<?/*<option value="total" <?if($period=='total') echo 'selected';?>>с момента назначения </option>*/?>
												<?/*<option value="year" <?if($period=='year') echo 'selected';?>>за год</option>*/?>
												<option style="background: none;" value="month" <?if($period=='month') echo 'selected';?>>за месяц</option>
												<option style="background: none;" value="week" <?if($period=='week') echo 'selected';?>>за неделю</option>
												<option style="background: none;" value="day" <?if($period=='day') echo 'selected';?>>за сутки</option>
											</select>
										</div>
									</th>
								</tr>
							</thead>
							<?
							$y=1;
							$a[$y]=0;
							$b[$y]=0;
							$c[$y]=0;
							$d[$y]=0;
							$e[$y]=0;
							$f[$y]=0;
							$aw[$y]=0;
							$bw[$y]=0;
							$cw[$y]=0;
							$dw[$y]=0;
							$ew[$y]=0;
							$fw[$y]=0;
							$ccww[$y]=0;
							$ddww[$y]=0;	
							$eeww[$y]=0;
							foreach ($table2 as $user){
								$a[$y]=$user['vsego'];
								$b[$y]=$user['pozitiv'];
								$c[$y]=$user['neitral'];
								$d[$y]=$user['smeshannyi'];
								$e[$y]=$user['negativ'];
								$f[$y]=$user['dannye'];
								$aw[$y]=0;
								$bw[$y]=0;
								$cw[$y]=0;
								$dw[$y]=0;
								$ew[$y]=0;
								$fw[$y]=0;
								$ccww[$y]=0;
								$ddww[$y]=0;	
								$eeww[$y]=0;
								$y++;
							}
							$amax = max($a);
							$bmax = max($b);
							$cmax = max($c);
							$dmax = max($d);
							$emax = max($e);
							$fmax = max($f);
							$px = 100;

							for($y=1;$y<=count($a);$y++){
								if ($a[$y] != 0){
									if($amax!=0) $aw[$y] = round($a[$y] * $px) / $amax; else $aw[$y]=0;
									if($a[$y]!=0) $bw[$y] = round($b[$y] / $a[$y] * $px); else $bw[$y]=0;
									if($a[$y]!=0) $cw[$y] = round($c[$y] / $a[$y] * $px); else $cw[$y]=0;
									if($a[$y]!=0) $dw[$y] = round($d[$y] / $a[$y] * $px); else $dw[$y]=0;
									if($a[$y]!=0) $ew[$y] = round($e[$y] / $a[$y] * $px); else $ew[$y]=0;
									if($fmax!=0) $fw[$y] = round($f[$y] * $px) / $fmax; else $fw[$y]=0;
									if($a[$y]!=0) $ccww[$y] = ($c[$y] / $a[$y] * $px); else $ccww[$y]=0;
									if($a[$y]!=0) $ddww[$y] = ($d[$y] / $a[$y] * $px); else $ddww[$y]=0;
									if($a[$y]!=0) $eeww[$y] = ($e[$y] / $a[$y] * $px); else $eeww[$y]=0;
								}
								if ($bw[$y] + $cw[$y] + $dw[$y] + $ew[$y] < 100) {
									if ($ccww[$y] - $cw[$y] > $ddww[$y] - $dw[$y] && $ccww[$y] - $cw[$y] > $eeww[$y] - $ew[$y])
										$cw[$y] = $cw[$y] + 1;
									
									if ($ddww[$y] - $dw[$y] > $ccww[$y] - $cw[$y] && $ddww[$y] - $dw[$y] > $eeww[$y] - $ew[$y])
										$dw[$y] = $dw[$y] + 1;
									
									if ($eeww[$y] - $ew[$y] > $ccww[$y] - $cw[$y] && $eeww[$y] - $ew[$y] > $ddww[$y] - $dw[$y])
										$ew[$y] = $ew[$y] + 1;
									
									if ($bw[$y] + $cw[$y] + $dw[$y] + $ew[$y] < 100) {
										//Если данные ячеек равны между собой и меньше 100
										if ($bw[$y] == $cw[$y] && $dw[$y] > $ew[$y])
											$dw[$y] = $dw[$y] + 1;
										if ($bw[$y] == $cw[$y] && $dw[$y] < $ew[$y])
											$ew[$y] = $ew[$y] + 1;
										
										if ($bw[$y] == $dw[$y] && $cw[$y] > $ew[$y])
											$cw[$y] = $cw[$y] + 1;
										if ($bw[$y] == $dw[$y] && $cw[$y] < $ew[$y])
											$ew[$y] = $ew[$y] + 1;
										
										if ($bw[$y] == $ew[$y] && $cw[$y] > $dw[$y])
											$cw[$y] = $cw[$y] + 1;
										if ($bw[$y] == $ew[$y] && $cw[$y] < $dw[$y])
											$dw[$y] = $dw[$y] + 1;
										
										if ($cw[$y] == $dw[$y] && $bw[$y] > $ew[$y])
											$bw[$y] = $bw[$y] + 1;
										if ($cw[$y] == $dw[$y] && $bw[$y] < $ew[$y])
											$ew[$y] = $ew[$y] + 1;
										
										if ($cw[$y] == $ew[$y] && $bw[$y] > $dw[$y])
											$bw[$y] = $bw[$y] + 1;
										if ($cw[$y] == $ew[$y] && $bw[$y] < $dw[$y])
											$dw[$y] = $dw[$y] + 1;

										if ($dw[$y] == $ew[$y] && $bw[$y] > $cw[$y])
											$bw[$y] = $bw[$y] + 1;
										if ($dw[$y] == $ew[$y] && $bw[$y] < $cw[$y])
											$cw[$y] = $cw[$y] + 1;
									}
								}
									
								if ($bw[$y] + $cw[$y] + $dw[$y] + $ew[$y] == 101) {
									$max_w = max(array($bw[$y],$cw[$y],$ew[$y]));
									if($max_w==$bw[$y]) $bw[$y] = $bw[$y] - 1;
									if($max_w==$cw[$y]) $cw[$y] = $cw[$y] - 1;
									if($max_w==$ew[$y]) $ew[$y] = $ew[$y] - 1;
								}
								
								if ($bw[$y] + $cw[$y] + $dw[$y] + $ew[$y] == 102) 
									$bw[$y] = $bw[$y] - 2;
								
								if ($bw[$y] + $cw[$y] + $dw[$y] + $ew[$y] == 103) 
									$bw[$y] = $bw[$y] - 3;
								
								if ($bw[$y] + $cw[$y] + $dw[$y] + $ew[$y] == 104) 
									$bw[$y] = $bw[$y] - 4;
							}
							$max_pozitiv = max($bw);
							$max_neitral = max($cw);
							$max_smeshannyi = max($dw);
							$max_negativ = max($ew);
							$i=0;?>
							<tbody id='applications'>
								<?foreach ($table2 as $user){
									if($category==$user['id']) $active_project=$user['s_id'];
									if($user['vsego']!=0){
										$i++;?>
										<tr data-id="id-<?=$i ?>">
											<td id='td-1' class='fio-<?=$i?>'>
												<?if($control == 'main2' || $control == 'akims2'){?>
													<div id='div-img'>
														<img style='width: 30px; height: 30px; border-radius: 50%;' src='<?=$user['image']?>'>
													</div>
												<?}?>
												<div class='div_fio' <?if($control != 'main2' && $control != 'akims2') echo 'style="width: 100% !important; padding-left: 4%;"'?>>
													<div style='margin: auto;'>
													
														<a href="https://cabinet.imas.kz/board?id=<?=$user['s_id']?>&t=all&token=TViCyutrwQ8Z" target="_blank"><i class="fa fa-desktop"></i></a>
														<a href="https://cabinet.imas.kz/tape?id=<?=$user['s_id']?>&t=all&token=TViCyutrwQ8Z" target="_blank"><i class="fa fa-newspaper-o"></i></a>
														<a href="/?category=<?=$user['id']?>" <?if($category==$user['id']){?>class='active_category'<?}?>><b><strong><?=$user['name']?></strong></b></a>
													
														<br>
														<span><?=$user['dolzhnost']?></span>
													</div>
												</div>
											</td>
											
											<td id='td-2'>
												<a href='http://special.imas.kz/mvd2'>
													<div class="bg_total" style='width:<?=$aw[$i]?>%; height: 16px; float: left; border-radius: 7px;'>
														<div id='div-td-vsego'>
															<p id='p-td-vsego'>
																<span data-type='size1'>
																	<?=$user['vsego']?>
																</span>
															</p>
														</div>	
														<?if($user['vsego']==$amax){?><img src="http://special.imas.kz/media/img/1.png" style="float: right; margin-right: 10px; margin-top: -8px;"><?}?>
													</div>
													
												</a>
											</td>
											
											<td id='td-3'>					
											
												<a href='http://special.imas.kz/mvd2'>
													<div class='poster sentiment1' style='width: <?=$bw[$i]?>%; height: 16px; float: left; border-radius: 7px 0 0 7px;<?if($user['pozitiv']==100){?>border-radius: 7px;<?}?>'>
														<div id='div-td-pozitiv' style='<? if ($user['pozitiv'] <= 0) {?> display: none;<?}?>' title="Позитив: <?=$user['pozitiv']?>">
															<p id='p-td-pozitiv'>
																<span data-type='size2'>
																	<?=$user['pozitiv']?>
																</span>
															</p>
															
															<div class='descr'>
																Позитив: <?=$bw[$i]?>%
															</div>
														</div>
														<?if($bw[$i]==$max_pozitiv){?><img src="http://special.imas.kz/media/img/1.png" style="float: right; margin-right: 10px; margin-top: -8px;"><?}?>
													</div>
												</a>
												
												<a href='http://special.imas.kz/mvd2'>
													<div class='poster sentiment0' style='width: <?=$cw[$i]?>%; height: 16px; float: left; <?if($user['negativ']<=0 && $user['smeshannyi']<=0 && $user['pozitiv']>0){?>border-radius: 0px 7px 7px 0px;<?} elseif($user['negativ']<=0 && $user['smeshannyi']<=0 && $user['pozitiv']<=0){?>border-radius: 7px 7px 7px 7px;<?} elseif($user['negativ']>0 && $user['pozitiv']<=0){?>border-radius: 7px 0px 0px 7px;<?}?>'>
														<div id='div-td-neitral' style='<? if ($user['neitral'] <= 0) {?> display: none;<?} if ($cw[$i] <= 3) {?> margin-left: -10px; <?}?> ' title="Нейтрал: <?=$user['neitral']?>">
															<p id='p-td-neitral'>
																<span data-type='size3'>
																	<?=$user['neitral']?>
																</span>
															</p>
															<div class='descr'>
																Нейтрал: <?=$cw[$i]?>%
															</div>
														</div>	
														<?if($cw[$i]==$max_neitral){?><img src="http://special.imas.kz/media/img/1.png" style="float: right; margin-right: 10px; margin-top: -8px;"><?}?>
													</div>
												</a>
												<a href='http://special.imas.kz/mvd2'>
													<div class='poster sentiment-1' style='width: <?=$ew[$i]?>%;  height: 16px; float: left; border-radius: 0px 7px 7px 0px;'>
														<div  id='div-td-negativ' style='<? if ($user['negativ'] <= 0) {?> display: none;<?}?>' title="Негатив: <?=$user['negativ']?>">
															<p id='p-td-negativ'>
																<span data-type='size4'>
																	<?=$user['negativ']?>
																</span>
															</p>
															<div class='descr'>
																Негатив: <?=$ew[$i]?>%
															</div>
														</div>	
														<?if($ew[$i]==$max_negativ){?><img src="http://special.imas.kz/media/img/1.png" style="float: right; margin-right: 10px; margin-top: -8px;"><?}?>
													</div>
												</a>
											</td>
										</tr>		
									<?}
								}?>
							</tbody>	
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4 col-md-4 col-lg-4 padding-0"> 
			<?/*<a href="/dynamic.php" style="cursor: zoom-in;">*/?>
				<div class="col-sm-12 col-md-12 col-lg-12 padding-0">
					<!-- Диаграмма 1 -->
					<div class="col-sm-12 col-md-12 col-lg-12 padding-0 diagramm_1" style="height: 30%">
						<div class="title_block"><?echo ("Динамика публикаций");?></div><br>
						<div id="container_1" style="min-width: 200px !important; max-width: 700px !important; width: 560px !important;"></div>
					</div>
				</div>
			<?/*</a>*/?>
			
			<a href="/map.php?category=<?=$category?>" style="cursor: zoom-in;">
				<div class="col-sm-12 col-md-12 col-lg-12 padding-0 maps_world" style="height: 30%">
					<div class="title_block" title="Показать на весь экран"><?echo ("Статистика по регионам");?></div><br>
					<div id="container_maps_rk">
						<div class="loading">
							<i class="icon-spinner icon-spin icon-large"></i>
						</div>
					</div>
				</div>
			</a>
			
			<div class="col-sm-12 col-md-12 col-lg-12 padding-0" style="height: 30%;margin-top: 20px;">
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
<script src="/d_js/peity-demo.js"></script>

<!-- Custom and plugin javascript -->
<script src="/d_js/inspinia.js"></script>
<script src="/d_js/pace.min.js"></script>

<!-- jQuery UI -->
<script src="/d_js/jquery-ui.min.js"></script>

<!-- GITTER -->
<script src="/d_js/jquery.gritter.min.js"></script>

<!-- Sparkline -->
<script src="/d_js/jquery.sparkline.min.js"></script>

<!-- Sparkline demo data  -->
<script src="/d_js/sparkline-demo.js"></script>

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

function show_tab1(){
	$('.tab-pane').hide();
	$('#mvd-tab-content').show();
	$('#mvd2-tab-content').removeClass('active');
	$('#mvd2-tab-content').removeClass('in');
	$('#mvd2-tab-button').removeClass('active');
	$('#mvd-tab-content').addClass('active');
	$('#mvd-tab-content').addClass('in');
	$('#mvd-tab-button').addClass('active');
}
function show_tab2(){
	$('.tab-pane').hide();
	$('#mvd2-tab-content').show();
	$('#mvd-tab-content').removeClass('active');
	$('#mvd-tab-content').removeClass('in');
	$('#mvd-tab-button').removeClass('active');
	$('#mvd2-tab-content').addClass('active');
	$('#mvd2-tab-content').addClass('in');
	$('#mvd2-tab-button').addClass('active');
}
	
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

function show_project(t, date, sentiment){
	var href = 'https://cabinet.imas.kz/ru/tape?id=<?=$active_project?>&s_date='+date+'&s_time=00:00&f_date='+date+'&f_time=23:59&sentiment='+sentiment+'&t='+t+'&p=1&token=TViCyutrwQ8Z'
	// console.log(href);
	<?if($active_project!=0){?>window.open(href, '_blank').focus();<?}?>
}
function show_project_map(region_id){
	var href = 'https://cabinet.imas.kz/ru/tape?id=<?=$active_project?>&sentiment=&t=all&p=1&place=3&place_id='+region_id+'&token=TViCyutrwQ8Z'
	// console.log(href);
	<?/*if($active_project!=0){?>window.open(href, '_blank').focus();<?}*/?>
}

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

</script>
<script>
$(document).ready(function () {
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
				step: 18,
				style: {
					color: '#fff'
				}
			}
		},
		// },
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
					enabled: true
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
			color: '#357EBD',
			point: {
				events: {
					click: function (e) {
						// console.log(this);
						show_project('all', this.category, '');	
					}
				}
			}
		},{
			name: 'Публикации СМИ',
			visible: false,
			data: [<?echo $statistic_smi;?>],
			color: '#7ddcdc',
			point: {
				events: {
					click: function (e) {
						show_project('smi', this.category, '');	
					}
				}
			}
		}, {
			name: 'Публикации соцсети',
			visible: false,
			data: [<?echo $statistic_social;?>],
			color: '#6ab9fd',
			point: {
				events: {
					click: function (e) {
						show_project('social', this.category, '');	
					}
				}
			}
		}, {
			name: 'Позитивные публикации',
			data: [<?echo $statistic_pos;?>],
			color: '#1BB394',
			point: {
				events: {
					click: function (e) {
						show_project('all', this.category, '1');	
					}
				}
			}
		},  {
			name: 'Негативные публикации',
			data: [<?echo $statistic_neg;?>],
			color: '#EC5D5D',
			point: {
				events: {
					click: function (e) {
						show_project('all', this.category, '-1');	
					}
				}
			}
		}, {
			name: 'Нейтральные публикации',
			data: [<?echo $statistic_neu;?>],
			color: '#F2C94C',
			point: {
				events: {
					click: function (e) {
						show_project('all', this.category, '0');	
					}
				}
			}
		}],
		
		exporting: {
			enabled: false
		}
	});
});
</script>
 <script src="http://code.highcharts.com/maps/modules/map.js"></script>
<script src="/d_js/kz-all.js"></script>
<!-- Карта КЗ -->
<script>
	var data = [
		<?foreach($map_kz_array as $sm):?>
			<?if($sm['hc']!='kz'){?>['<?=$sm['hc']?>', <?=$sm['news_count']?>],<?}?>
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
		point: {
			events: {
				click: function (e) {
					console.log(this);
					show_project_map(this.category);	
				}
			}
		}	
    }]
});
</script>


	
	<script src="/d_js/jquery-1.4.2.min.js" type="text/javascript"></script>
	<script src="/d_js/jquery.easing.1.3.js" type="text/javascript"></script>
	<script src="/d_js/jquery.quicksand.min.js" type="text/javascript"></script>
<!-- Таблица -->
<script>
// $(document).ready(function () {
	// setTimeout(function(){check_images();check_images_smi();setTimeout(function(){check_images_smi();check_images();},300);},50);
// });
</script>
</body>
</html>

<?php } ?>