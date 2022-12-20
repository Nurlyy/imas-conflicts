<?php

// проверка авторизации
require __DIR__ . '/auth.php';
$login = getUserLogin();
setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian');

if ($login === null) {
    // если пользователь не авторизован
    header("Location: /login.php");
    exit();
} else {
    $today = date('Y-m-d', strtotime('today'));
    $month_ago = date('Y-m-d', strtotime('-30 days'));
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : $month_ago;
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : $today;

    $c_id = isset($_GET['c_id']) ? $_GET['c_id'] : null;
    $page = (isset($_GET['first_limit']) ? $_GET['first_limit'] : 1);
    $first_limit = $page * 30;
    $filter = (isset($_GET['filter']) ? $_GET['filter'] : 1);


    $host = 'localhost';
    $username = 'v-40047_conflicts';
    $password = 'Mod&b704Iwtv38*6';
    $name = 'v-40047_conflicts';
    $connect = new mysqli($host, $username, $password, $name) or die("Could not connect: " . mysqli_error($connect));
    mysqli_query($connect, "SET NAMES 'utf8'");
    mysqli_query($connect, "SET CHARACTER SET 'utf8'");
    mysqli_query($connect, "SET SESSION collation_connection = 'utf8_general_ci'");

    $count_query = mysqli_query($connect, "select n.c_id, count(n.id) as count from news n where " . ((isset($c_id) && $c_id != "") ? " n.c_id={$c_id}  and" : "") . " n.date between '{$start_date} 23:59:59' and '{$end_date} 23:59:59' group by n.c_id");
    // var_dump("select n.c_id, count(n.id) as count from news n where " . ((isset($c_id) && $c_id != "") ? " n.c_id={$c_id}" : "") . " and n.date between '{$start_date} 23:59:59' and '{$end_date} 23:59:59' group by n.c_id");
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
    }

    $query = "select c_id, title, text, link, date, sentiment, res_type, resource_name, resource_link, resource_logo from news"
        . ((isset($c_id) && $c_id != "" || (isset($first_limit)) || (isset($start_date) && (isset($end_date)))) ? " where" : "")
        . (isset($start_date) && (isset($end_date)) ? " date between '{$start_date} 23:59:59' and '{$end_date} 23:59:59'" : "")
        . ((isset($c_id) && $c_id != "") ? " and c_id=" . $c_id : "")
        . ((isset($filter))?(($filter==1)?" order by date desc":""):"")
        . ((isset($filter))?(($filter==2)?" order by date asc":""):"")
        . ((isset($filter))?(($filter==3)?" and sentiment=1":""):"")
        . ((isset($filter))?(($filter==4)?" and sentiment=0":""):"")
        . ((isset($filter))?(($filter==5)?" and sentiment=-1":""):"")

        . (isset($first_limit) ? " limit {$first_limit}, 30" : "");
    // var_dump($query);
    // exit;
    $posts_query = mysqli_query($connect, $query);
    $postsArray = [];
    while ($row = mysqli_fetch_array($posts_query)) {
        $postsArray[] = $row;
    }
    $stamp = strtotime($post['date']);

    $_monthsList = array(
        "0" => "января", "02" => "февраля",
        "03" => "марта", "04" => "апреля", "05" => "мая", "06" => "июня",
        "07" => "июля", "08" => "августа", "09" => "сентября",
        "10" => "октября", "11" => "ноября", "12" => "декабря"
    );

    $total_count_between_dates = 0;

    foreach ($table3 as $table) {
        $total_count_between_dates += $table['count'];
    }

    $pages = (ceil($total_count_between_dates / 30))-1;

?>

    <div class="lenta" style="overflow:hidden;">
        <div style="box-shadow: 0px 0.5px 10px black; display: flex; justify-content:center; flex-direction:column; align-items:center; z-index:11; position:relative;">
            <h2 style="text-align:center; color: #666666; margin-bottom:10px;"><strong id="conflict_title"><?php echo (!empty($c_id)) ? $table1[$c_id]['name'] : "Все конфликты" ?></strong></h2>
            <ul class="pagination-md" id="pagination-demo" style="z-index:10 !important;">
            </ul>
            <br>
        </div>
        <div id="posts-lenta" class="lenta" style="padding-bottom: 120px; background-color: rgb(232, 232, 232);">
            <input id="pages" type="hidden" name="pages" value="<?= $pages ?>" />
            <input id="page" type="hidden" name="page" value="<?= $page ?>" />
            <div style="margin-top:10px;"></div>
            <?php
            foreach ($postsArray as $post) { ?>
                <div class="post-container">
                    <div class="post-header">
                        <div style="display:flex; flex-direction:row;">
                            <div class="col-2 post-img-div">
                                <img class="post-img" src="<?= $post['resource_logo'] ?>" width="40px" />
                            </div>
                            <h4 class="col-10"><?= $post['title'] ?></h4>
                        </div>
                        <p style="color:grey;"><?= date("d", strtotime($post['date'])) . " " . $_monthsList[date("m", strtotime($post['date']))] . " " . date("Y", strtotime($post['date'])) . " , " . date("H:i:s", strtotime($post['date'])) ?> | <a href="<?= $post['link'] ?>">Facebook</a></p>
                        <?php

                        ?>
                    </div>
                    <div class="post-body">
                        <p><?= $post['text'] ?></p>
                        <div class="post-sentiment<?php echo $post['sentiment']
                                                    ?>">
                            <?php echo (($post['sentiment'] == 1) ? 'Позитив' : (($post['sentiment'] == 0) ? 'Нейтрал' : (($post['sentiment'] == -1) ? 'Негатив' : null))) ?>
                        </div>
                    </div>
                </div>

            <?php }
            ?>
            <div style="margin-bottom:100px;"></div>
        </div>
    </div>


<?php
}
?>