
<?php
require __DIR__ . '/auth.php';
$login = getUserLogin();

if ($login === null){
	header("Location: /login.php");
	exit();
} else{
$active_project=0;
$active_tab=1;
$category=0;
if(isset($_GET['category'])) {
	$category=(int) $_GET['category'];
}

// Блок Медиарейтинг
$host = 'localhost';
$username = 'v-40047_mms';
$password = 'R703U1ke';
$name = 'v_40047_special';
$connect = mysqli_connect($host, $username, $password, $name) or die("Could not connect: " . mysqli_error($connect));
mysqli_query($connect,"SET NAMES 'utf8'"); 
mysqli_query($connect,"SET CHARACTER SET 'utf8'");
mysqli_query($connect,"SET SESSION collation_connection = 'utf8_general_ci'");

	
$query = "SELECT U.id, U.s_id, U.name, A.vsego FROM user U, mvd A where U.id = A.user_id ORDER BY A.vsego DESC";
$sql_reiting= mysqli_query($connect,$query);
$table1=array();
while($row = mysqli_fetch_array($sql_reiting)){
	$table1[]=$row;
	if($category==$row['id'])  {$active_project=$row['s_id'];}
}

$query2 = "SELECT U.id, U.s_id, U.name, A.vsego FROM user U, mvd2 A where U.id = A.user_id ORDER BY A.vsego DESC LIMIT 20";
$sql_reiting2= mysqli_query($connect,$query2);
$table2=array();
$table2_ids_array=array();
while($row = mysqli_fetch_array($sql_reiting2)){
	$table2[]=$row;
	if($category==$row['id'])  {$active_project=$row['s_id'];$active_tab=2;}
}

if($category==0) {
	$query_map="Select `hc`, region_id, SUM(`news_count`) as news_count From mvd_map_kz GROUP BY hc";
}
else {
	$query_map="Select `hc`, region_id, `news_count` From mvd_map_kz WHERE category_id=".$category;
}

$sql_map_kz= mysqli_query($connect,$query_map)or die(mysqli_error($connect));
while($table_map_kz = mysqli_fetch_array($sql_map_kz)){
	$map_kz_array[] = $table_map_kz;
}?>


<html>
<head> 
    <title>iMAS Dashboard - Map</title>
	<link href="d_css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="d_css/toastr.min.css" rel="stylesheet">
    <link href="d_css/jquery.gritter.css" rel="stylesheet">
    <link href="d_css/animate.css" rel="stylesheet">
    <link href="d_css/style.css" rel="stylesheet">
	<link href="d_css/deshbord_style.css" rel="stylesheet">
	<link href="d_css/load_modal.css" rel="stylesheet">
	<link href="d_css/bootstrap-chosen.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="nav/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="nav/jquery.fullPage.min.css" />
	<link id="animated_stylesheet" rel="stylesheet" type="text/css" href="nav/animate.css" />
	<link rel="stylesheet" type="text/css" href="nav/scrollbox.min.css" media="screen">
	<link rel="stylesheet" type="text/css" href="nav/style.css" />
	<link rel="stylesheet" type="text/css" href="nav/odometer.css" />
	<link rel="stylesheet" type="text/css" href="nav/owl.carousel.css" />
	<link rel="stylesheet" type="text/css" href="nav/owl.theme.css" />
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

	<script src="d_js/jquery-3.1.1.min.js"></script>

</head>
<?$rk=0;
foreach($map_kz_array as $sm):
	if($sm['hc']=='kz') $rk=$sm['news_count'];
endforeach;?>
<body style="background: #252326;">
	<div class="col-sm-12 col-md-12 col-lg-12 padding-0">
		<div class="col-sm-12 col-md-12 col-lg-12 padding-0" style="height: 50px; line-height: 50px;"> 
			<?if($active_tab==1){?><div class="title_block" title="Показать на весь экран" style="float:left;padding: 10px 15px;"><?echo ("Сферы управления");?></div><?}?>
			<?if($active_tab==2){?><div class="title_block" title="Показать на весь экран" style="float:left;padding: 10px 15px;"><?echo ("ТОП проблемных тем");?></div><?}?>
			<ul class="nav nav-pills" id="tabs-mvd" style="float:right">
				<li id="all-tab-button"><a href="/">Перейти к дэшборду</a></li>
			</ul> 
		</div>
		<div class="col-sm-2 col-md-2 col-lg-2 padding-0"> 
			<table border='0' cellspacing='0' class='table-sorter' style="min-height: auto;">
				<tbody id='applications'>
					<?$i=0;
					if($active_tab==1){
						foreach ($table1 as $user){
							if($user['vsego']!=0){
								$i++;?>
								<tr data-id="id-<?=$i ?>">
									<td id='td-1' class='fio-<?=$i?>'>
										<div class='div_fio' <style="width: 100% !important; padding-left: 4%;">
											<div style='margin: auto;'>
												<a href="https://cabinet.imas.kz/board?id=<?=$user['s_id']?>&t=all&token=TViCyutrwQ8Z" target="_blank"><i class="fa fa-desktop"></i></a>
												<a href="https://cabinet.imas.kz/tape?id=<?=$user['s_id']?>&t=all&token=TViCyutrwQ8Z" target="_blank"><i class="fa fa-newspaper-o"></i></a>
												<a href="/map.php?category=<?=$user['id']?>" <?if($category==$user['id']){?>class='active_category'<?}?>><b><strong><?=$user['name']?></strong></b></a>
											</div>
										</div>
									</td>
								</tr>		
							<?}
						}
					} else{
						foreach ($table2 as $user){
							if($user['vsego']!=0){
								$i++;?>
								<tr data-id="id-<?=$i ?>">
									<td id='td-1' class='fio-<?=$i?>'>
										<div class='div_fio' <style="width: 100% !important; padding-left: 4%;">
											<div style='margin: auto;'>
												<a href="https://cabinet.imas.kz/board?id=<?=$user['s_id']?>&t=all&token=TViCyutrwQ8Z" target="_blank"><i class="fa fa-desktop"></i></a>
												<a href="https://cabinet.imas.kz/tape?id=<?=$user['s_id']?>&t=all&token=TViCyutrwQ8Z" target="_blank"><i class="fa fa-newspaper-o"></i></a>
												<a href="/map.php?category=<?=$user['id']?>" <?if($category==$user['id']){?>class='active_category'<?}?>><b><strong><?=$user['name']?></strong></b></a>
											</div>
										</div>
									</td>
								</tr>		
							<?}
						}
					}?>
				</tbody>	
			</table>
		</div>
		<div class="col-sm-10 col-md-10 col-lg-10 padding-0"> 
			<div id="map_rk" style="width: 100%; height: 90%;"></div>
		</div>
	</div>
	<div class="col-sm-12 col-md-12 col-lg-12 padding-0" style="font-size: 30px; color: #FFF">Республиканские источники: <?=$rk?></div>
</body>








<!-- Mainly scripts -->
<script src="d_js/analytics.js"></script>
<script src="d_js/bootstrap.min.js"></script>
<script src="d_js/jquery.metisMenu.js"></script>
<script src="d_js/jquery.slimscroll.min.js"></script>

<!-- Flot -->
<script src="d_js/jquery.flot.js"></script>
<script src="d_js/jquery.flot.tooltip.min.js"></script>
<script src="d_js/jquery.flot.spline.js"></script>
<script src="d_js/jquery.flot.resize.js"></script>
<script src="d_js/jquery.flot.pie.js"></script>

<!-- Peity -->
<script src="d_js/jquery.peity.min.js"></script>
<script src="d_js/peity-demo.js"></script>

<!-- Custom and plugin javascript -->
<script src="d_js/inspinia.js"></script>
<script src="d_js/pace.min.js"></script>

<!-- jQuery UI -->
<script src="d_js/jquery-ui.min.js"></script>

<!-- GITTER -->
<script src="d_js/jquery.gritter.min.js"></script>

<!-- Sparkline -->
<script src="d_js/jquery.sparkline.min.js"></script>

<!-- Sparkline demo data  -->
<script src="d_js/sparkline-demo.js"></script>

<!-- ChartJS-->
<script src="d_js/Chart.min.js"></script>

<!-- Toastr -->
<script src="d_js/toastr.min.js"></script>	
<script src="d_js/chosen.jquery.js"></script>	


<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>


<script src="http://code.highcharts.com/maps/modules/map.js"></script>
<script src="d_js/kz-all.js"></script>
<!-- Карта КЗ -->
<script>

	function show_project_map(region_id){
		var href = 'https://cabinet.imas.kz/ru/tape?id=<?=$active_project?>&s_date=01.05.2022&s_time=00:00&f_date=<?=date("d.m.Y")?>&f_time=23:59&sentiment=&t=all&p=1&place=3&place_id='+region_id+'&token=TViCyutrwQ8Z'
		// console.log(href);
		<?if($active_project!=0){?>window.open(href, '_blank').focus();<?}?>
	}
	var data_map=[];
	<?$k=0;
	foreach($map_kz_array as $sm){
		if($sm['hc']!='kz'){?>
			data_map[<?=$k?>]={
							'hc-key':'<?=$sm['hc']?>',
							'region':'<?=$sm['region_id']?>',
							'value':<?=$sm['news_count']?>,
						};
			<?$k++;
		}
	}?>

// Create the chart
Highcharts.mapChart('map_rk', {
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
        data: data_map,
        name: 'Карта',
        states: {
            hover: {
                color: '#BADA55',
            }
        },
        dataLabels: {
            enabled: true,
			 format: '<i style="font-size: 20px;">{point.value}</i>'
        },	
		point: {
			events: {
				click: function (e) {	
					// console.log(this);
					show_project_map(this.region);	
				}
			}
		}			
    }]
});
</script>
<?}?>