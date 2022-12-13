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


    $host = 'localhost';
    $username = 'v-40047_conflicts';
    $password = 'Mod&b704Iwtv38*6';
    $name = 'v-40047_conflicts';
    $connect = mysqli_connect($host, $username, $password, $name) or die("Could not connect: " . mysqli_error($connect));
    mysqli_query($connect, "SET NAMES 'utf8'");
    mysqli_query($connect, "SET CHARACTER SET 'utf8'");
    mysqli_query($connect, "SET SESSION collation_connection = 'utf8_general_ci'");

    $count_query = mysqli_query($connect, "select count(n.id) as count, c.id, c.hc_key from news n inner join conflicts c on n.c_id = c.id group by c.id");
    $table1 = array();
    while ($row = mysqli_fetch_array($count_query)) {
        $table1[] = $row;
    }
    // echo "<pre>";
    // var_dump($table1);
    // echo "</pre>";
    // exit;



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
            <div class="date">

                <script>
                    function Clock_ms() {
                        var monthsArr = ["Января", "Февраля", "Марта", "Апреля", "Мая", "Июня",
                            "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря"
                        ];

                        var daysArr = ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"];


                        /* Настройки внешнего вида */
                        var c_h = '#357EBD'; // Цвет часов 
                        var c_m = '#357EBD'; // Цвет минут 
                        var c_s = '#357EBD'; // Цвет секунд 
                        var c_ms = '#357EBD'; // Цвет миллисекунд 
                        var sep = '#357EBD'; // Цвет разделителей 


                        /* Для нормальной работы скрипта ниже лучше ничего не менять! */
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
        </nav>
        <div class="col-sm-12 col-md-12 col-lg-12 padding-0">
            <div class="row">
                <div class="col-8" id="world-map-container"></div>
                <div class="col-4" style="background-color:#252326;">
                    <div class="lenta">
                        <h2 style="text-align:center; color: #666666; margin-bottom:10px;"><strong>Лента постов</strong></h2>
                        <div class="filter_datetime p-t-0 f-l">
                            <!-- v:004-92M -->
                            <div id="reportrange" class="form-control b-none">
                                <i class="fa fa-calendar p-r-5"></i>
                                <span></span>
                            </div>
                        </div>

                        <br>

                        <div class="post-container">
                            <div class="post-header">
                                <div style="display:flex; flex-direction:row;">
                                    <div class="col-2 post-img-div">
                                        <img class="post-img" src="images/cnn.png" width="40px" />
                                    </div>
                                    <h4 class="col-10">Post Title Post Title Post Title Post Title Post Title </h4>
                                </div>
                                <p style="color:grey;">16:44 12 Декабря 2022 г. Понедельник | <a href="facebook.com">Facebook</a></p>
                            </div>
                            <div class="post-body">
                                <p>This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. </p>
                                <div class="post-sentiment<?php  #echo $post_sentiment 
                                                            ?>">
                                    Позитив
                                </div>
                            </div>
                        </div>

                        <div class="post-container">
                            <div class="post-header">
                                <div style="display:flex; flex-direction:row;">
                                    <div class="col-2 post-img-div">
                                        <img class="post-img" src="images/bbc.png" width="50px" />
                                    </div>
                                    <h4 class="col-10">Post Title Post Title Post Title Post Title Post Title </h4>
                                </div>
                                <p style="color:grey;">16:44 12 Декабря 2022 г. Понедельник | <a href="facebook.com">Facebook</a></p>
                            </div>
                            <div class="post-body">
                                <p>This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. </p>
                                <div class="post-sentiment<?php  #echo $post_sentiment 
                                                            ?>">
                                    Позитив
                                </div>
                            </div>
                        </div>

                        <div class="post-container">
                            <div class="post-header">
                                <div style="display:flex; flex-direction:row;">
                                    <div class="col-2 post-img-div">
                                        <img class="post-img" src="images/bbc.png" width="50px" />
                                    </div>
                                    <h4 class="col-10">Post Title Post Title Post Title Post Title Post Title </h4>
                                </div>
                                <p style="color:grey;">16:44 12 Декабря 2022 г. Понедельник | <a href="facebook.com">Facebook</a></p>
                            </div>
                            <div class="post-body">
                                <p>This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. </p>
                                <div class="post-sentiment<?php  #echo $post_sentiment 
                                                            ?>">
                                    Позитив
                                </div>
                            </div>
                        </div>

                        <div class="post-container">
                            <div class="post-header">
                                <div style="display:flex; flex-direction:row;">
                                    <div class="col-2 post-img-div">
                                        <img class="post-img" src="images/bbc.png" width="50px" />
                                    </div>
                                    <h4 class="col-10">Post Title Post Title Post Title Post Title Post Title </h4>
                                </div>
                                <p style="color:grey;">16:44 12 Декабря 2022 г. Понедельник | <a href="facebook.com">Facebook</a></p>
                            </div>
                            <div class="post-body">
                                <p>This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. </p>
                                <div class="post-sentiment<?php  #echo $post_sentiment 
                                                            ?>">
                                    Позитив
                                </div>
                            </div>
                        </div>

                        <div class="post-container">
                            <div class="post-header">
                                <div style="display:flex; flex-direction:row;">
                                    <div class="col-2 post-img-div">
                                        <img class="post-img" src="images/bbc.png" width="50px" />
                                    </div>
                                    <h4 class="col-10">Post Title Post Title Post Title Post Title Post Title </h4>
                                </div>
                                <p style="color:grey;">16:44 12 Декабря 2022 г. Понедельник | <a href="facebook.com">Facebook</a></p>
                            </div>
                            <div class="post-body">
                                <p>This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. This is post description or text. </p>
                                <div class="post-sentiment<?php  #echo $post_sentiment 
                                                            ?>">
                                    Позитив
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/d_js/jquery-3.1.1.min.js"></script>
        <script src="world/js/moment.js"></script>
        <script src="world/js/daterangepicker.js"></script>
        <script>
            (async () => {
                const topology = await fetch(
                    'https://code.highcharts.com/mapdata/custom/world.topo.json'
                ).then(response => response.json());
                data = [
                    <?php
                    foreach ($table1 as $item) {
                        // for ($i = 0; $i < 2; $i++) {
                            $tempstr = substr($item['hc_key'], 0, -1) . '"count": ' . $item['count'] . "},";
                            echo $tempstr;
                        // }
                    }
                    ?>
                    // {
                    // "name": "Украина",
                    // "lat": 49.059504586073835,
                    // "lon": 31.375807931443198,
                    // "country": "UA",
                    // },{
                    // "name": "Сирия",
                    // "lat": 35.05808832001548, 
                    // "lon": 38.387270150683435,
                    // "country": "FR",
                    // }, {
                    // "name": "Сектор Газа",
                    // "lat": 31.499651492089765,
                    // "lon": 34.453665919906264,
                    // "country": "FR",
                    // },{
                    // "name": "Турецко-Сирийская граница",
                    // "lat": 36.20453105162421,
                    // "lon": 37.13681718884459,
                    // "country": "FR",
                    // }, {
                    // "name": "Карабахское нагорье",
                    // "lat": 39.88669024201312,
                    // "lon": 46.139479107796255,
                    // "country": "FR"
                    // }, {
                    // "name": "Линия Дюранда регион Вазиристан",
                    // "lat": 32.32124986340483, 
                    // "lon": 69.85935114573644,
                    // "country": "FR"
                    // }, {
                    // "name": "Западная Сахара",
                    // "lat": 24.66513586235691, 
                    // "lon": -13.194248481996402,
                    // "country": "FR"
                    // }, {
                    // "name": "Полуостров Корея",
                    // "lat": 37.748865762507,
                    // "lon": 127.9319676664056,
                    // "country": "BE"
                    // }, {
                    // "name": "Кашмир",
                    // "lat": 33.2808706932512,
                    // "lon": 75.35189891980443,
                    // "country": "CH"
                    // },
                    // {
                    // "name": "Баткенская область",
                    // "lat": 40.01834973863929, 
                    // "lon": 70.52962825966277,
                    // "country": "CH"
                    // }
                ];

                // console.log(data);

                Highcharts.mapChart('world-map-container', {
                    chart: {
                        map: topology,
                        backgroundColor: "#252326",
                    },
                    title: {
                        text: 'Посты на карте мира',
                        style: {
                            color: "#fff"
                        }
                    },
                    mapNavigation: {
                        enabled: true,
                        buttonOptions: {
                            alignTo: 'spacingBox',
                            x: 10
                        }
                    },
                    // tooltip: {
                    //     headerFormat: '',
                    //     pointFormat: '<b>{point.name}</b><br>Lat: {point.lat:.2f}, Lon: {point.lon:.2f}'
                    // },
                    tooltip: {
                        formatter: function() {
                            console.log(this)
                            if (this.point.clusteredData) {
                                this.point.clusterPointsAmount = 0;
                                this.point.clusteredData.forEach(element => {
                                    this.point.clusterPointsAmount += element.options.count;
                                })

                                tempname = this.point.clusteredData[0].options.name;
                                temparray = [];
                                this.point.clusteredData.forEach(element => {
                                    if (element.options.name) {
                                        if (element.options.name == tempname) {
                                            temparray.push(true);
                                        } else {
                                            temparray.push(false);
                                        }
                                    }
                                });
                                tempval = temparray.every(element => element === true);
                                if (tempval === true) {
                                    return this.point.clusteredData[0].options.name + ': ' + this.point.clusterPointsAmount;
                                }

                                return 'Clustered data: ' + this.point.clusterPointsAmount;
                            }
                            return '<b>' + this.key + "</b><br><b>Кол-во постов: " + this.point.count;
                        }
                    },
                    colorAxis: {
                        min: 1,
                        max: 1000,
                        type: 'logarithmic',
                        minColor: '#bcc8ff',
                        maxColor: '#274ef9'
                    },
                    plotOptions: {
                        mappoint: {
                            cluster: {
                                enabled: false,
                                text: "fdjsfdsa",
                                allowOverlap: false,
                                animation: {
                                    duration: 450
                                },
                                layoutAlgorithm: {
                                    type: 'grid',
                                    gridSize: 70
                                },
                                zones: [{
                                    from: 1,
                                    to: 4,
                                    marker: {
                                        radius: 13
                                    }
                                }, {
                                    from: 5,
                                    to: 9,
                                    marker: {
                                        radius: 15
                                    }
                                }, {
                                    from: 10,
                                    to: 15,
                                    marker: {
                                        radius: 17
                                    }
                                }, {
                                    from: 16,
                                    to: 20,
                                    marker: {
                                        radius: 19
                                    }
                                }, {
                                    from: 21,
                                    to: 50,
                                    marker: {
                                        radius: 21
                                    }
                                }, {
                                    from: 51,
                                    to: 70,
                                    marker: {
                                        radius: 25
                                    }
                                }, {
                                    from: 71,
                                    to: 100000,
                                    marker: {
                                        radius: 29
                                    }
                                }, ]
                            }
                        }
                    },
                    series: [{
                        name: 'World',
                        accessibility: {
                            exposeAsGroupOnly: true
                        },
                        borderColor: '#fff',
                        nullColor: 'rgba(166, 249, 202, 0.79)',
                        showInLegend: false,

                    }, {
                        type: 'mappoint',
                        enableMouseTracking: true,
                        title: "Something",

                        accessibility: {
                            point: {
                                descriptionFormatter: function(point) {
                                    if (point.isCluster) {
                                        return 'Grouping of ' + point.clusterPointsAmount + ' points.';
                                        this.point.clusterPointsAmount = 0;
                                        this.point.clusteredData.forEach(element => {
                                            this.point.clusterPointsAmount += element.options.count;
                                        })
                                    }
                                    return point.name + ', country code: ' + point.country + '.';
                                }
                            }
                        },
                        colorKey: 'clusterPointsAmount',
                        name: 'Cities',
                        data: data
                    }]
                });
            })();
        </script>
        <script>
            function addState(sdate, edate) {
                $.ajax({
                    url: '?start_date=' + sdate + '&end_date=' + edate,
                    type: 'GET',
                    success: function(data) {
                        // console.log(sdate);
                        history.pushState("", "", '?start_date=' + sdate + '&end_date=' + edate);
                        $('.wrapper-content').html(data);
                    }
                });
            }

            function do_daterangepicker_stuff(start, end, label) {
                $('#reportrange span').html(start.format('D.MM.YYYY') + ' - ' + end.format('D.MM.YYYY'));
                addState(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
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

            $(document).ready(function() {
                if (!(window.location.href).includes("?start_date")) {
                    history.pushState("", "", '?start_date=' + '<?= $start_date ?>' + '&end_date=' + '<?= $end_date ?>');
                }
                create_daterangepicker('<?= $start_date ?>', '<?= $end_date ?>');
            });
        </script>
    </body>

    </html>



<?php
}
