<?php
if (!empty($_POST)) {
    require __DIR__ . '/auth.php';

    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    if (checkAuth($login, $password)) {
        setcookie('login', $login, 0, '/');
        setcookie('password', $password, 0, '/');
        header('Location: /index.php');
    } else {
        $error = 'Ошибка авторизации';
    }
}
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Авторизация</title>

    <link href="/inspinia/css/bootstrap.min.css" rel="stylesheet">
    <link href="/inspinia/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="/inspinia/css/animate.css" rel="stylesheet">
    <link href="/inspinia/css/style.css" rel="stylesheet">

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div> <img class="img-responsive col-xs-12" src="https://cabinet.imas.kz/media/img/imas_logo_en_blue.png" style="width:240px;"></div>
            
            <form class="m-t" role="form" action="/login.php" method="post">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" required="" name="login" id="login">
                </div>
                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="">
                </div>
                <input type="submit" value="Войти" class="btn btn-primary block full-width m-b">
				<?php if (isset($error)): ?>
					<span style="color: red;">
						<?= $error ?>
					</span>
				<?php endif; ?>

            </form>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="/inspinia/js/jquery-3.1.1.min.js"></script>
    <script src="/inspinia/js/popper.min.js"></script>
    <script src="/inspinia/js/bootstrap.js"></script>

</body>

</html>
