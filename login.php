<?php
session_start();
$host = "localhost";
$user = "root";
$pass = "";
$db   = "akademik";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Gagal koneksi ke database");
}

$error = "";
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5(mysqli_real_escape_string($koneksi, $_POST['password']));

    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $query = mysqli_query($koneksi, $sql);
    if (mysqli_num_rows($query) > 0) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-container { width: 400px; margin: 100px auto; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="card">
            <div class="card-header text-center">Login</div>
            <div class="card-body">
                <?php if ($error) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                <form method="POST">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
