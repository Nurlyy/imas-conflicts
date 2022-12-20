<?php

// проверка авторизации
require __DIR__ . '/auth.php';
$login = getUserLogin();

if ($login === null) {
    // если пользователь не авторизован
    header("Location: /login.php");
    exit();
} else {
    // если пользователь авторизован


    $today = date('Y-m-d', strtotime('today'));
    $month_ago = date('Y-m-d', strtotime('-30 days'));
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : $month_ago;
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : $today;

    $c_id = isset($_GET['c_id']) ? $_GET['c_id'] : null;
    $page = (isset($_GET['first_limit']) ? $_GET['first_limit'] : 1);
    $filter = (isset($_GET['filter']) ? $_GET['filter'] : 1);
    $first_limit = $page * 30;


    $host = 'localhost';
    $username = 'v-40047_conflicts';
    $password = 'Mod&b704Iwtv38*6';
    $name = 'v-40047_conflicts';
    $connect = new mysqli($host, $username, $password, $name) or die("Could not connect: " . mysqli_error($connect));
    mysqli_query($connect, "SET NAMES 'utf8'");
    mysqli_query($connect, "SET CHARACTER SET 'utf8'");
    mysqli_query($connect, "SET SESSION collation_connection = 'utf8_general_ci'");

    $count_query = mysqli_query($connect, "select n.c_id, count(n.id) as count from news n where n.date between '{$start_date} 23:59:59' and '{$end_date} 23:59:59' group by n.c_id");
    // var_dump("select n.c_id, count(n.id) as count from news n where n.date between '{$start_date}' and '{$end_date}' group by n.c_id");
    // exit;
    $table3 = array();
    while ($row = mysqli_fetch_array($count_query)) {
        $table3[] = $row;
    }

    $conflicts_query = mysqli_query($connect, "select id, name, hc_key from conflicts");
    $table2 = array();
    while ($row = mysqli_fetch_array($conflicts_query)) {
        $table2[] = $row;
    }

    $posts_count = mysqli_query($connect, "select count(id), c_id from news group by c_id");
    $countArray = [];
    while ($row = mysqli_fetch_array($posts_count)) {
        $countArray[] = $row;
    };

    $table1 = [];

    foreach ($table3 as $table) {
        $table1[$table['c_id']] = $table;
    }

    foreach ($table2 as $table) {
        $table1[$table['id']]['name'] = $table['name'];
        $table1[$table['id']]['hc_key'] = $table['hc_key'];
        $table1[$table["id"]]["c_id"] = $table['id'];
    }

    $query = "select c_id, title, text, link, date, sentiment, res_type, resource_name, resource_link, resource_logo from news where"
        . (isset($c_id) ? " c_id={$c_id}" : "")
        . (isset($first_limit) ? " limit {$first_limit}, 30" : "");
    $posts_query = mysqli_query($connect, $query);

    $total_count_between_dates = 0;

    foreach ($table3 as $table) {
        $total_count_between_dates += $table['count'];
    }

    // $pages = ceil($total_count_between_dates / 30);


    // foreach ($table1 as $key=>$item) {
    //     $tempstr = substr($item['hc_key'], 0, -1) . '"z": ';
    //     $tempstr .=  ($item['count']>0)?$item['count']:0;
    //     $tempstr .=  "},";
    //     echo $tempstr . "....";
    // }

    // $posts_query = mysqli_query($connect, "select ")

    // echo "<pre>";
    // var_dump($table1[$c_id]['name']);
    // echo "</pre>";
    // exit;


    mysqli_close($connect);


?>

    <!DOCTYPE html>
    <html lang="en">

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
        <link rel="stylesheet" href="world/css/bootstrap.min.css">
        <link rel="stylesheet" href="world/css/style.css">
        <link rel="stylesheet" href="world/css/datepicker3.css">

        <script src="/d_js/jquery-3.1.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.3.6/proj4.js"></script>
        <script src="https://code.highcharts.com/maps/highmaps.js"></script>
        <script src="https://code.highcharts.com/stock/modules/data.js"></script>
        <script src="https://code.highcharts.com/modules/marker-clusters.js"></script>
        <script src="https://code.highcharts.com/modules/coloraxis.js"></script>
        <script src="https://code.highcharts.com/maps/modules/accessibility.js"></script>
    </head>

    <body style="background: #252326;">
        <!-- Навигационное меню -->

        <nav class="nav_top col-sm-12 col-md-12 col-lg-12 padding-0">
            <a class="navbar-brand" href="http://imas.kz"><img class="img-responsive col-xs-12" src="https://cabinet.imas.kz/media/img/imas_logo_en_blue.png" style="width:240px;"></a>
            <div class="text">
                <span>Самая мощная казахстанская система мониторинга и анализа <br>информационных потоков в режиме реального времени</span>
            </div>

            <div class="madeinkz">
                <img class="img-responsive" src="/images/madeinkz.png" style="zoom: 70%;">
            </div>



            <div class="number_one">
                <img src="/icon/number_one.jpg" style="height: 50px; float: left;">
                <div class="text">
                    <span id="system_text">Система в Казахстане <br>в режиме реального времени</span>
                </div>
            </div>
            <div class="date" style="left: -50px;">

                <script>
                    function Clock_ms() {
                        var monthsArr = ["Января", "Февраля", "Марта", "Апреля", "Мая", "Июня",
                            "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"
                        ];

                        var daysArr = ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"];

                        var c_h = '#357EBD'; // Цвет часов 
                        var c_m = '#357EBD'; // Цвет минут 
                        var c_s = '#357EBD'; // Цвет секунд 
                        var c_ms = '#357EBD'; // Цвет миллисекунд 
                        var sep = '#357EBD'; // Цвет разделителей 

                        var data = new Date();

                        var year = data.getFullYear();
                        var month = data.getMonth();
                        var numDay = data.getDate();
                        var day = data.getDay();

                        var hour = data.getHours();
                        var min = data.getMinutes();
                        var sec = data.getSeconds();
                        var ms = (data.getTime() / 10).toFixed(0).substr(10);
                        if (hour < 10) {
                            hour = '0' + hour
                        };
                        if (min < 10) {
                            min = '0' + min
                        };
                        if (sec < 10) {
                            sec = '0' + sec
                        };
                        var time = '<div id="time"><span id="hour" style="color:' + c_h + '">' + hour + '</span>' + '<span style="color:' + sep + '">:</span>' + '<span id="min" style="color:' + c_m + '">' + min + '</span>' + '<span style="color:' + sep + '">:</span>' + '<span  id="sec" style="color:' + c_s + '">' + sec + '</span>' + '<span style="color:' + sep + '">.</span>' + '<span style="color:' + c_ms + '">' + ms + '</span></div>' + '<div id="date"><span id="day_arr" style="color:' + c_h + '">' + daysArr[day] + ', ' + '<br></span>' + '<span id="day_num" style="color:' + c_h + '">' + numDay + ' ' + '</span>' + '<span id="month_arr" style="color:' + c_h + '">' + monthsArr[month] + ' ' + '</span>' + '<span id="year" style="color:' + c_h + '">' + year + ' г. ' + '</span></div>';
                        document.getElementById('date_time').innerHTML = time;
                        setTimeout("Clock_ms()", 1);
                        /* ========================== /END ========================== */
                    }
                    onload = Clock_ms;
                </script>
                <span id="date_time"></span>
            </div>

            <div class="filter_posts">
                <i style="margin-left:5px; margin-right:-8px;" class="fa fa-arrow-up"></i>
                <select id="filter_posts_select">
                    <option value="1">Сначала новые</option>
                    <option value="2">Сначала старые</option>
                    <option value="3">Только позитивные</option>
                    <option value="4">Только нейтральные</option>
                    <option value="5">Только негативные</option>
                </select>
            </div>

            <div class="filter_datetime p-t-0 f-l" style="position:absolute;float:right; right:0px; margin-right:70px; margin-top:7px;">
                <!-- v:004-92M -->
                <div id="reportrange" class="form-control b-none">
                    <i class="fa fa-calendar p-r-5"></i>
                    <span></span>
                </div>
            </div>


        </nav>
        <div class="col-sm-12 col-md-12 col-lg-12 padding-0">
            <div class="row">
                <div class="col-8" id="world-map-container"></div>
                <div id="lenta-container" class="col-4" style="background-color:#252326;">

                </div>

            </div>
        </div>
        <div class="loading">Loading&#8230;</div>
        <script src="/d_js/jquery-3.1.1.min.js"></script>
        <script src="world/js/plugins/twbs/jquery.twbsPagination.js"></script>
        <script src="world/js/moment.js"></script>
        <script src="world/js/daterangepicker.js"></script>

        <script>
            function gotopage(count) {
                if (count >= 1) {
                    total = <?= $total_count_between_dates ?>;
                    pages = total / 30;
                    // console.log(start_date, end_date, c_id, count)
                    getLenta(start_date, end_date, c_id, count, $("#filter_posts_select").val())
                }
            }

            (async () => {
                const topology = await fetch(
                    'https://code.highcharts.com/mapdata/custom/world.topo.json'
                ).then(response => response.json());
                data = [
                    <?php
                    foreach ($table1 as $key => $item) {
                        // $tempstr = substr($item['hc_key'], 0, -1) . '"z": ' . ($item['count']>0)?$item['count']:0 . "},";
                        $tempstr = substr($item['hc_key'], 0, -1) . '"z": ';
                        $tempstr .=  ($item['count'] > 0) ? $item['count'] . "," : 0 . ", ";
                        $tempstr .= '"c_id": ';
                        $tempstr .= $item['c_id'] . ",";
                        $tempstr .=  "},";
                        echo $tempstr;
                    }
                    ?>
                ];
                Highcharts.mapChart('world-map-container', {
                    chart: {
                        map: topology,
                        backgroundColor: "#252326",
                    },
                    title: {
                        text: ''
                    },
                    legend: {
                        enabled: false
                    },
                    mapNavigation: {
                        enabled: true,
                        buttonOptions: {
                            verticalAlign: 'bottom'
                        }
                    },
                    mapView: {
                        fitToGeometry: {
                            type: 'MultiPoint',
                            coordinates: [
                                // Alaska west
                                [-164, 54],
                                // Greenland north
                                [-35, 84],
                                // New Zealand east
                                [179, -38],
                                // Chile south
                                [-68, -55]
                            ]
                        }
                    },
                    series: [{
                        name: 'World',
                        accessibility: {
                            exposeAsGroupOnly: true
                        },
                        // borderColor: '#fff',
                        // nullColor: "#18894f",
                        showInLegend: false,
                    }, {
                        type: 'mapbubble',
                        name: 'Публикаций',
                        joinBy: ['iso-a3', 'point.country'],
                        data: data,
                        color: '#ff543a',
                        title: "CJFIDOS",
                        minSize: 4,
                        maxSize: '12%',
                        tooltip: {
                            pointFormat: '{point.name}: {point.z}'
                        },
                        point: {
                            events: {
                                click: function(e) {
                                    $("#conflict_title").text(this.name)
                                    c_id = this.c_id;
                                    getLenta(start, end, this.c_id, 1, $("#filter_posts_select").val());
                                    // $(".loading").css('display', 'block');
                                }
                            }
                        }
                    }]
                });
            })();
        </script>
        <script>
            var start = '<?= $start_date ?>';
            var end = '<?= $end_date ?>';
            c_id = '<?php echo $c_id ?>';


            function do_daterangepicker_stuff(start, end, label) {
                $('#reportrange span').html(start.format('D.MM.YYYY') + ' - ' + end.format('D.MM.YYYY'));
                history.pushState("", "", '?start_date=' + start.format('YYYY-MM-DD') + '&end_date=' + end.format('YYYY-MM-DD') +
                    ((c_id) ? "&c_id=" + c_id : "")
                );
                start = start.format('YYYY-MM-DD');
                end = end.format('YYYY-MM-DD');
                location.reload();
                window.reload();
            }

            function create_daterangepicker(start, end) {
                // v:004-92M
                // if(start==null && end==null){
                let edate = new Date(end);
                let sdate = new Date(start);

                start_date = sdate.getDate() + '.' + parseInt(sdate.getMonth() + 1) + '.' + sdate.getFullYear();
                end_date = edate.getDate() + '.' + parseInt(edate.getMonth() + 1) + '.' + edate.getFullYear();

                console.log(start_date);
                console.log(end_date);

                const daterangepicker_setting = {
                    format: 'DD.MM.YYYY',
                    startDate: start_date,
                    endDate: end_date,
                    minDate: '01.01.2021',
                    maxDate: '31.12.2022',
                    showDropdowns: true,
                    // showWeekNumbers: true,
                    timePicker: false,
                    timePickerIncrement: 1,
                    timePicker12Hour: true,

                    opens: 'left',
                    drops: 'down',
                    buttonClasses: ['btn', 'btn-sm'],
                    applyClass: 'btn-primary daterangepicker-apply-button',
                    cancelClass: 'btn-default daterangepicker-cancel-button',
                    separator: ' to ',
                    locale: {
                        applyLabel: 'Ок',
                        cancelLabel: 'Отмена',
                        fromLabel: 'от',
                        toLabel: 'по',
                        customRangeLabel: 'Период',
                        daysOfWeek: [
                            "Вс",
                            "Пн",
                            "Вт",
                            "Ср",
                            "Чт",
                            "Пт",
                            "Сб"
                        ],
                        monthNames: [
                            "Январь",
                            "Февраль",
                            "Март",
                            "Апрель",
                            "Май",
                            "Июнь",
                            "Июль",
                            "Август",
                            "Сентябрь",
                            "Октябрь",
                            "Ноябрь",
                            "Декабрь"
                        ],
                        firstDay: 1
                    }
                };
                // Формирование календаря для больших экаранов
                $('#reportrange span').html(start_date + ' - ' + end_date);
                $('#reportrange').daterangepicker(daterangepicker_setting, do_daterangepicker_stuff);
                // Формирование календаря для малых экаранов
                // $('#reportrange-header span').html(string_date);
                $('#reportrange-header span').html(start_date + ' - ' + end_date);
                $('#reportrange-header').daterangepicker(daterangepicker_setting, do_daterangepicker_stuff);
            }

            function getLenta(start, end, c_id, first_limit = 1, filter = $("#filter_posts_select").val()) {
                $(".loading").css('display', 'block');
                // tmp1 = new Date(start);
                // tmp2 = new Date(end);
                // console.log(tmp1.getFullYear())
                // console.log("posts.php?start_date=" + (!start.includes("-") ? (start.split(".")[2] + "-" + start.split(".")[1] + "-" + start.split(".")[0]) : start) + '&end_date=' + (!end.includes("-") ? (end.split(".")[2] + "-" + end.split(".")[1] + "-" + end.split(".")[0]) : end) + ((c_id !== "" && c_id != undefined) ? "&c_id=" + c_id : "") + ((first_limit != null) ? "&first_limit=" + first_limit : ""));
                $.ajax({
                    url: "posts.php?start_date=" + (!start.includes("-") ? (start.split(".")[2] + "-" + start.split(".")[1] + "-" + start.split(".")[0]) : start) + '&end_date=' + (!end.includes("-") ? (end.split(".")[2] + "-" + end.split(".")[1] + "-" + end.split(".")[0]) : end) + ((c_id !== "" && c_id != undefined) ? "&c_id=" + c_id : "") + ((first_limit != null) ? "&first_limit=" + first_limit : "") + "&filter="+filter,
                    method: "GET",
                    success: function(data) {
                        document.getElementById("lenta-container").innerHTML = data;
                        history.pushState("", "", 'world.php?start_date=' + (!start.includes("-") ? (start.split(".")[2] + "-" + start.split(".")[1] + "-" + start.split(".")[0]) : start) + '&end_date=' + (!end.includes("-") ? (end.split(".")[2] + "-" + end.split(".")[1] + "-" + end.split(".")[0]) : end) + ((c_id !== "" && c_id != undefined) ? "&c_id=" + c_id : "") + ((first_limit != null) ? "&first_limit=" + first_limit : "") + "&filter="+filter);
                        $(".loading").css('display', 'none');
                        counter = 0;
                        bul = false;
                        console.log(first_limit)
                        $('#pagination-demo').twbsPagination({
                            totalPages: $("#pages").val(),
                            visiblePages: 5,
                            startPage: first_limit,
                            initiateStartPageClick: false,
                            prev: "",
                            next: "",
                            last: "",
                            first: "",
                            onPageClick: function(event, page) {
                                // if (counter > 0) {
                                    gotopage(page);
                                // } else {
                                    // counter++;
                                // }
                                // counter += 1;
                                // if(counter == 1){

                                // } else {
                                //     gotopage(page)
                                // }
                                // alert(page)
                                // if(page == 1){
                                //     bul = true;
                                // }else {
                                //     bul = false;
                                // }
                                // if(!bul){
                                //     gotopage(page)
                                // }
                            }
                        });
                    }
                })
                // runpagination();
            }

            $("#filter_posts_select").on("change", function(){
                getLenta(start, end, c_id, 1, $("#filter_posts_select").val())
            })

            $(document).ready(function() {
                if (!(window.location.href).includes("?start_date")) {
                    if (!(window.location.href).includes("&c_id")) {
                        history.pushState("", "", 'world.php?start_date=' + '<?= $start_date ?>' + '&end_date=' + '<?= $end_date ?>' +
                            ((c_id !== "") ? "&c_id=" + c_id : ""));
                    }
                    history.pushState("", "", 'world.php?start_date=' + '<?= $start_date ?>' + '&end_date=' + '<?= $end_date ?>');
                }
                create_daterangepicker('<?= $start_date ?>', '<?= $end_date ?>');
                getLenta('<?= $start_date ?>', '<?= $end_date ?>', <?= $c_id ?>);
                $(".loading").css('display', 'block');
            });
        </script>
    </body>

    </html>
<?php
}
