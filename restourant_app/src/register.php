<?php

error_reporting(E_ALL);

// Hataları ekrana yazdır
ini_set('display_errors', 1);

include_once 'sql_scripts.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
 
if (isset($_GET['type']) && $_GET['type'] == 'firma') {
    $usrtype = 'firma';
}
    else {
        $usrtype = 'normal';
    }



?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fontawseome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/form.css">
    <title>myshop kayıt ol</title>
</head>

<body>

    <div id="main">

        <?php include 'navbar.php'; ?>

        <div class="space"></div>

        <div class="form-container">
            <h2>Kayıt Ol</h2>

            <?php
            
            if ($usrtype == "normal")
            {
                echo '          <form id="registerForm" method="POST" action="islemler.php">

                <label for="name">Ad</label>
                <input id="name" name="name" type="text" placeholder="Ad" required/>

                <div>
                    <label for="surname">Soyad</label>
                    <br>
                <input id="surname" name="surname" type="text" placeholder="Soyad" required/>
                </div>
                
               
                <label for="username">Kullanıcı Adı</label>
                <input id="username" name="username" type="text" placeholder="Kullanıcı Adı" required/>
                
                <label for="password">Şifre</label>
                <input id="password" name="password" type="password" placeholder="Şifre" required/>
                
                <input type="hidden" name="type" id="type" value="'.$usrtype.'"/>
                
                <div class="buttons" style="margin-top:15px; margin-bottom:20px;">
                    <button type="submit" name="register" >Kayıt Ol</button>
                    <a href="login.php">Giriş Yap</a>
                </div>
              

            </form>';
            }
            else{
                echo '          <form id="registerForm" method="POST" action="islemler.php">

                <label for="name">Ad</label>
                <input id="name" name="name" type="text" placeholder="Ad" required/>
                
                <div>
                    <label for="description">Açıklama</label>
                    <br>
                <input id="description" name="description" type="text" placeholder="Açıklama" required/>
                </div>
                
                <label for="username">Kullanıcı Adı</label>
                <input id="username" name="username" type="text" placeholder="Kullanıcı Adı" required/>
                
                <label for="password">Şifre</label>
                <input id="password" name="password" type="password" placeholder="Şifre" required/>
                
                <input type="hidden" name="type" id="type" value="'.$usrtype.'"/>
                

                <div class="buttons" style="margin-top:15px; margin-bottom:20px;">
                    <button type="submit" name="register" >Kayıt Ol</button>
                    <a href="login.php">Giriş Yap</a>
                </div>


            </form>';
            }

if ($usrtype == 'normal') {
                    echo '<a style="display:flex; justify-content:center; align-items:center;" href="register.php?type=firma">Firma olarak kayıt ol</a>';
                }
                else {
                    echo '<a style="display:flex; justify-content:center; align-items:center;" href="register.php">Bireysel olarak kayıt ol</a>';
                }
            ?>

        </div>

        <div class="space"></div>

        <?php include 'footer.php';?>

    </div>



</body>

</html>