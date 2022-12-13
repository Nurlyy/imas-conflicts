<?php
ini_set('display_errors', 1);
require __DIR__ . '/auth.php';
$login = getUserLogin();

if ($login === null){
	header("Location: /login.php");
	exit();
} else{
	
$host = 'localhost';
$username = 'v-40047_mms';
$password = 'R703U1ke';
$name = 'v_40047_special';
$connect = mysqli_connect($host, $username, $password, $name) or die("Could not connect: " . mysqli_error($connect));
mysqli_query($connect,"SET NAMES 'utf8'"); 
mysqli_query($connect,"SET CHARACTER SET 'utf8'");
mysqli_query($connect,"SET SESSION collation_connection = 'utf8_general_ci'");

$query_counts="Select SUM(`smi_today`) as smi_today, SUM(`social_today`) as social_today, SUM(`smi_total`) as smi_total, SUM(`social_total`) as social_total From mvd_counts_stats";
$sql_dashboard = mysqli_query($connect,$query_counts)or die(mysqli_error($connect));
while($table_dashboard = mysqli_fetch_array($sql_dashboard)){
	$smi_today = 			 $table_dashboard['smi_today'];
	$social_today = 		 $table_dashboard['social_today'];
	$smi_total = 			 $table_dashboard['smi_total'];
	$social_total = 		 $table_dashboard['social_total'];
}
$total=$smi_total+$social_total;
$number_parts=array_reverse(str_split($total));
$k=0;
$total='';
foreach($number_parts as $p){
	$k++;
	$total=$p.$total;
	if($k==3) {
		$total=' '.$total;
		$k=0;
	}
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
</head>
 
<body style="background: #252326; color:#fff;">
	<div class="col-sm-12 col-md-12 col-lg-12 padding-0 body_content">
		<div class="col-sm-12 col-md-12 col-lg-12 padding-0" style="margin-top: 20px;"> 
			<div class="col-sm-1 col-md-1 col-lg-1 padding-0">
				<img style="height:70px;" src="/images/logo_flag.png">
			</div>
			<div class="col-sm-10 col-md-10 col-lg-10 padding-0" style="height:70px;line-height:70px;font-size: 40px;text-align: center;font-weight:bold;">
				<span>РЕЗУЛЬТАТЫ РАБОТЫ iMAS В ЦИФРАХ</span>
			</div>
			<div class="col-sm-1 col-md-1 col-lg-1 padding-0" style="height:70px;line-height:70px;font-size: 20px;text-align:right;">
				<a href="/" style="color:#fff;margin-right:40px;"><i class="fa fa-close"></i></a>
			</div>
		</div>
		<div class="col-sm-12 col-md-12 col-lg-12 padding-0"> 
			<div class="col-sm-1 col-md-1 col-lg-1 padding-0" style="height:80%">
			</div>
			<div class="col-sm-10 col-md-10 col-lg-10 padding-0" style="height:80%"> 
				<div class="col-sm-12 col-md-12 col-lg-12" style="height:33%; padding:20px;"> 
					<a href="https://drive.google.com/drive/folders/1hXnWDLVXu8CAxLeHUsmvR3Bxr8g7RsB3" target="_blank">
						<div class="col-sm-4 col-md-4 col-lg-4" style="height:100%; padding:20px; color:#fff;"> 
							<div class="col-sm-12 col-md-12 col-lg-12 text-center"><img src="/images/dash/1.png" style="height:50%;"></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:40px;font-weight: bold;"><span>5</span></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:20px;"><span>ЛЕТ СОТРУДНИЧЕСТВА</span></div>
						</div>
					</a>
					<?/*<a href="#" target="_blank">*/?>
						<div class="col-sm-4 col-md-4 col-lg-4" style="height:100%; padding:20px; color:#fff;"> 
							<div class="col-sm-12 col-md-12 col-lg-12 text-center"><img src="/images/dash/2.png" style="height:50%;"></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:40px;font-weight: bold;"><span><?=$total?></span></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:20px;"><span>ВСЕГО ПУБЛИКАЦИЙ В АРХИВЕ</span></div>
						</div>
					<?/*</a>*/?>
					<?/*<a href="#" target="_blank">*/?>
						<div class="col-sm-4 col-md-4 col-lg-4" style="height:100%; padding:20px; color:#fff;"> 
							<div class="col-sm-12 col-md-12 col-lg-12 text-center"><img src="/images/dash/3.png" style="height:50%;"></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:40px;font-weight: bold;"><span>1 750 000+</span></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:20px;"><span>ПУБЛИКАЦИЙ ОБРАБОТАНО РЕЛЕВАНТНЫХ МВД</span></div>
						</div>
					<?/*</a>*/?>
				</div>
				<div class="col-sm-12 col-md-12 col-lg-12" style="height:33%; padding:20px;">
					<?/*<a href="#" target="_blank"> */?>
						<div class="col-sm-4 col-md-4 col-lg-4" style="height:100%; padding:20px; color:#fff;"> 
							<div class="col-sm-12 col-md-12 col-lg-12 text-center"><img src="/images/dash/4.png" style="height:50%;"></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:40px;font-weight: bold;"><span>7</span></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:20px;"><span>СПЕЦИАЛИСТОВ iMAS РАБОТАЮТ 24/7 НАД СОВМЕСТНЫМИ ЗАДАЧАМИ</span></div>
						</div>
					<?/*</a> */?>
					<a href="https://drive.google.com/drive/folders/1JUx29dnYKIA-FboL7m2QbK8RuxeOQYKp" target="_blank">
						<div class="col-sm-4 col-md-4 col-lg-4" style="height:100%; padding:20px; color:#fff;"> 
							<div class="col-sm-12 col-md-12 col-lg-12 text-center"><img src="/images/dash/5.png" style="height:50%;"></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:40px;font-weight: bold;"><span>5 000+</span></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:20px;"><span>ОТЧЕТОВ СОЗДАНО</span></div>
						</div>
					</a>
					<a href="https://drive.google.com/drive/folders/17tzKjelVqe7dHhNxIaTVgD4az5iIAWfV" target="_blank">
						<div class="col-sm-4 col-md-4 col-lg-4" style="height:100%; padding:20px; color:#fff;"> 
							<div class="col-sm-12 col-md-12 col-lg-12 text-center"><img src="/images/dash/6.png" style="height:50%;"></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:40px;font-weight: bold;"><span>100+</span></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:20px;"><span>ЭКСТРЕННЫХ И ЧРЕЗВЫЧАЙНЫХ СИТУАЦИЙ ОТРАБОТАНО</span></div>
						</div>
					</a>
				</div>
				<div class="col-sm-12 col-md-12 col-lg-12" style="height:33%; padding:20px;"> 
					<a href="http://special.imas.kz/regionsdp" target="_blank">
						<div class="col-sm-4 col-md-4 col-lg-4" style="height:100%; padding:20px; color:#fff;"> 
							<div class="col-sm-12 col-md-12 col-lg-12 text-center"><img src="/images/dash/7.png" style="height:50%;"></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:40px;font-weight: bold;"><span>+30%</span></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:20px;"><span>ЕЖЕНЕДЕЛЬНЫЙ МЕДИА-РЕЙТИНГ ПОВЛИЯЛ НА РОСТ ИНФОРМАЦИОННОЙ АКТИВНОСТИ ДП</span></div>
						</div>
					</a>
					<a href="https://t.me/+YhMftooB4_VF9Ssm" target="_blank">
						<div class="col-sm-4 col-md-4 col-lg-4" style="height:100%; padding:20px; color:#fff;"> 
							<div class="col-sm-12 col-md-12 col-lg-12 text-center"><img src="/images/dash/8.png" style="height:50%;"></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:40px;font-weight: bold;"><span>100 000+</span></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:20px;"><span>КРИТИЧЕСКИХ ЗАМЕЧАНИЙ В АДРЕС МВД ВЫЯВЛЕНО И ПЕРЕДАНО ДЛЯ РАБОТЫ</span></div>
						</div>
					</a>
					<a href="https://drive.google.com/drive/folders/1qsWDHqilR_CK5haDt3XtohQ8ByP7wtqi" target="_blank">
						<div class="col-sm-4 col-md-4 col-lg-4" style="height:100%; padding:20px; color:#fff;"> 
							<div class="col-sm-12 col-md-12 col-lg-12 text-center"><img src="/images/dash/9.png" style="height:50%;"></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:40px;font-weight: bold;"><span>50+</span></div>
							<div class="col-sm-12 col-md-12 col-lg-12 text-center" style="font-size:20px;"><span>ВЫЯВЛЕНИЕ ИНТЕРНЕТ-МАГАЗИНОВ ПО ПРОДАЖЕ НАРКОТИЧЕСКИХ СРЕДСТВ В DARKNET</span></div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-sm-1 col-md-1 col-lg-1 padding-0" style="height:80%">
			</div>
		</div>
	</div> 
</body>
</html>

<?}?>