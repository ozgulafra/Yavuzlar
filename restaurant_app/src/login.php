<?php

session_start(); // Session başlat

if (isset($_SESSION['user_id'])) { // Eğer kullanıcı giriş yapmışsa
    header("Location: index.php"); // index.php sayfasına yönlendir
    exit(); // işlemi sonlandır
}

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fontawseome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/form.css">
    <title>Giriş Yap</title>
</head>

<body>

    <div id="main">

    <?php include 'navbar.php'; ?>

        <div class="space"></div>

        <div class="form-container">
            <h2>Giriş Yap</h2>
            <form id="login" method="POST" action="islemler.php">

                <label for="email">Kullanıcı Adı</label>
                <input id="email" name="username" type="text" placeholder="Kullanıcı Adı" required />

                <label for="email">Şifre</label>
                <input id="password" name="password" type="password" placeholder="Şifre" required />


                <div class="buttons">
                    <button type="submit" name="login">Giriş Yap</button>
                    <a href="register.php">Kayıt Ol</a>
                </div>
            </form>
        </div>

        <div class="space"></div>

        <?php include 'footer.php'; ?>

    </div>


</body>

</html>