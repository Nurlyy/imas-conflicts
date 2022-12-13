<?php
$host = 'localhost';
$username = 'v-40047_mms';
$password = 'R703U1ke';
$name = 'v_40047_mms';
$connect = mysqli_connect($host, $username, $password, $name) or die("Could not connect: " . mysqli_error($connect));
mysqli_query($connect,"SET NAMES 'utf8'"); 
mysqli_query($connect,"SET CHARACTER SET 'utf8'");
mysqli_query($connect,"SET SESSION collation_connection = 'utf8_general_ci'");

$sql_map_world= mysqli_query($connect,"Select * From map_world")or die(mysqli_error($connect));
while($table_map_world = mysqli_fetch_array($sql_map_world)){
	$map_world_array[] = $table_map_world;
}

?>

<?$rk=0;
foreach($map_world_array as $sm):
	if($sm['hc']=='unknown') $rk=$rk+$sm['news_count'];
endforeach;?>
<html>
<head> 

	<script src="d_js/jquery-3.1.1.min.js"></script>

</head>
 
<body style="background: #252326;">
	<div id="container_maps_world" style="width: 1900px; height: 900px;"></div>
	<div style="font-size: 30px; color: #FFF">География не определена: <?=$rk?></div>
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
<script src="d_js/world.js"></script>
<!-- Карта мира -->
<script>
$(function () {
	var data = [
		<?foreach($map_world_array as $sm):
			if($sm['hc']!='unknown'){?>
				['<?=$sm['hc']?>', <?=$sm['news_count']?>],
			<?}?>
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
				enabled: true,
				 format: '<i style="font-size: 14px;">{point.value}</i>'
			},		
		}]
    });
});
</script>