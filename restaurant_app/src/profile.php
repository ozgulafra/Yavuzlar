<?php
include_once 'sql_scripts.php'; // SQL işlemleri için gerekli dosya
session_start(); // Session başlatma

if (isset($_SESSION["user_id"])) { // Eğer kullanıcı giriş yapmışsa
    $userid = $_SESSION["user_id"]; // Kullanıcı id'sini al
    $user_type =  $_SESSION["logined_user_type"];

    usercontrol(isset($_SESSION['user_id'])? $_SESSION['user_id'] : null, ["normal", "admin", "firma"]); // Kullanıcı yetkilerini kontrol et
    
    $userdata = getUserDetails($userid); // Kullanıcı verilerini al


} else {
    header("Location: login.php"); // Kullanıcı giriş yapmamışsa login sayfasına yönlendir
    exit(); // İşlemi sonlandır
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
    <link rel="stylesheet" href="css/sepetim.css">
    <title>Profilim</title>
</head>

<body>

    <div id="main">

        <?php include 'navbar.php'; ?>

        <div class="space"></div>

        <div class="form-container">
            <h2>Bilgilerim</h2>

            <form id="update_info"  action="islemler.php" method="post">


                <div style="display: flex;  grid-template-columns: repeat(2, 1fr); grid-gap: 30px;">
                    <div>
                        <input type="hidden" name="user_id" value="<?= $userid ?>">
                        <input type="hidden" name="user_type" value="<?= $user_type ?>">
                        <label style="display: block;" for="fullname">Ad</label>
                        <input style="display: block;" id="fullname" name="name" type="text" placeholder="Ad" value="<?= $userdata['name'] ?>" required />
                        
                        <?php
                        
                        
                        if ($user_type == 'firma') {
                           
                            echo '<label style="display: block;" for="description">Açıklama</label>
                            <input style="display: block;" id="description" name="description" type="text" placeholder="Açıklama" value="' . $userdata['description'] . '" required />';
                        }
                        else
                        {
                            echo '<label style="display: block;" for="fullname">Soyad</label>
                        <input style="display: block;" id="fullname" name="surname" type="text" placeholder="Soyad" value="'.$userdata["surname"].'" required />';
                        }


                        ?>
                        
                    </div>

                    <div>
                        <?php 
                        
                        if ($user_type != 'firma') {
                          
                            
                            echo '
                        <label style="display: block;" for="bakiye">Bakiye</label>
                        <input style="display: block;" id="bakiye" name="balance" type="number" placeholder="Bakiye" value="'.$userdata["balance"].'" required />';
                        }
                        ?>
                       

                        <label style="display: block;" for="password">Şifre</label>
                        <input style="display: block;" id="password" name="password" type="password" placeholder="Şifre" value="<?= $userdata['password'] ?>" required />

                    </div>
                </div>



                <div class="buttons">
                    <button type="submit" name="update_user">Güncelle</button>
                </div>

            </form>
<form action="islemler.php"   method="post">
                <?php 
                if ($user_type == 'firma') {
                    echo '<div class="buttons">
                    <input type="hidden" name="company_id" value="'.$userdata["company_id"].'">
                    <button type="submit" name="delete_company" style="background-color:#BB2D3B; padding:10px; color:white; border: 1px solid red; border-radius: 7px;">Firmamı Sil</button></div>';
                }
                else
                {
                    echo '<div class="" >
                    <input type="hidden" name="customer_id" value="'.$userid.'">
                    <button type="submit" name="delete_customer" style="background-color:#BB2D3B; padding:10px; color:white; border: 1px solid red; border-radius: 7px;">Hesabımı Sil</button></div>';
                }

                ?>
            </form>
            
        </div>

        <div class="space"></div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script> // Profil güncelleme işlemi sonucu mesajı gösterme
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const updateOk = urlParams.get('update_ok');

        if (updateOk === '1') {
            Swal.fire({
                icon: 'success',
                title: 'Profil Güncelleme Başarılı!',
                showConfirmButton: false,
                timer: 1500,
                didClose: () => {
                    window.location.href = 'profile.php'; // Profil sayfasına yönlendir
                }
            });
        } else if (updateOk === '0') {
            Swal.fire({
                icon: 'error',
                title: 'Profil Güncelleme Başarısız!',
                showConfirmButton: false,
                timer: 1500,
                didClose: () => {
                    window.location.href = 'profile.php'; // Profil sayfasına yönlendir
                }
            });
        }
    });
</script>



</body>

</html>