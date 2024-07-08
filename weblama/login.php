<?php
session_start();
require_once "function.php";

$login_error = false;
if (isset($_POST["login"])) {
    $login = login_akun();
    if (!$login) {
        $login_error = true;
    }
    if($login){
        echo 'Login berhasil';
        header("Location: index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
</head>

<body>
    <div class="container">
        <div class="card mx-auto p-5">
            <div id="judul-form" class="text-center h1 mb-4">LOGIN</div>
            <!-- Jika Username & Password Login Salah -->
            <?php if ($login_error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    * Username/Password salah
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
                </div>
            <?php endif; ?>

            <!-- Form Login -->
            <form id="form-login" action="login.php" method="POST">
                <input class="form-control mb-3" type="text" autocomplete="off" name="username" placeholder="Username" required>
                <input class="form-control mb-3" type="password" autocomplete="off" name="password" placeholder="Password" required>
                <button class="btn btn-primary w-100" name="login">Login</button>
            </form>
        </div>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.2.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
