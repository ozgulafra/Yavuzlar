<?php
session_start(); // Session başlat
include_once 'sql_scripts.php'; // SQL işlemleri için gerekli dosyayı dahil et



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
    <link rel="stylesheet" href="css/iletisim.css">

    <style>
        


        .sidebar {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
        }

        .main-ul {
            list-style-type: none;
            padding: 0;
        }

        .top-category {
            margin-bottom: 10px;
        }

        .top-category span {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .top-category span span {
            margin-right: 10px;
        }

        .sub-category {
            list-style-type: none;
            padding-left: 20px;
            display: none;
        }

        .sub-category li {
            margin-bottom: 5px;
        }

        .sub-category a {
            text-decoration: none;
            color: #FCCF95;
            transition: color 0.3s;
        }

        .sub-category a:hover {
            color: #f8f9fa;
        }
    </style>

    <title>myshop iletisim</title>
</head>

<body>

    <div id="main">

        <?php include 'navbar.php'; ?>

        <div class="container">
           
 <?php include 'sidebar.php'; ?>

            <div class="content">
                <div class="contact"style="width: auto;">
                    <div class="info">
                        <h2>İletişim</h2>
                        <hr />
                        <p><i>İnternette güvenli alışverişin adresi myshop.com'a aşağıdaki iletişim adreslerinden
                                kolayca ulaşabilirsiniz.</i></p>
                        <br />
                        <h3> Şirket Bilgileri </h3>
                        <hr />
                        <h4><i class="fas fa-building"></i> Ünvanı</h4>
                        <p> myshop Elektronik Hizmetler ve Tic. A.Ş.(myshop.com)</p>
                        <h4><i class="fas fa-info"></i> Ticari Sicil Numarası</h4>
                        <p> 632732</p>
                        <h4><i class="fas fa-map-marker-alt"></i> Genel Müdürlük</h4>
                        <p> Kuştepe Mah. Mecidiyeköy Yolu Cad. Trump Towers Kule 2 Kat:2 No:12 34387 Şişli/İstanbul</p>
                        <h4><i class="fas fa-map-marker-alt"></i> Operasyon Merkezi</h4>
                        <p> İnönü Mah. Mimar Sinan Cad. No: 3 Gebze Güzeller OSB Gebze/Kocaeli</p>
                        <h4><i class="fas fa-envelope"></i> Mail Adresi</h4>
                        <p> myshop@info.com</p>
                        <h4><i class="fas fa-phone"></i> Telefon Numarası</h4>
                        <p> 0850 252 44 12</p>
                    </div>
                </div>


            </div>
        </div>


        <?php include 'footer.php'; ?>

    </div>




</body>

</html>