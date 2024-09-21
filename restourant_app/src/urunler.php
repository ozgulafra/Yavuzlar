<?php

include_once 'sql_scripts.php';
session_start();
$category = [];

if (isset($_GET['kategori'])) { // Eğer kategori parametresi varsa (Özellikle bir kategori filteresi uygulanmışsa)
    $kategori = $_GET['kategori'];
    $urunler = listProductsByCategory($kategori); // Kategoriye göre ürünleri listele
}
else {
    $urunler = []; // Tüm ürünleri listele
}
?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/main.css">
    <title>Sitem</title>
    <style>
   

        .items {
            display: flex;
            flex-wrap: wrap;
        }

        .item-box {
            border: 1px solid #ccc;
            padding: 16px;
            margin: 8px;
            width: calc(33.333% - 32px);
            box-sizing: border-box;
            text-align: center;
        }

        .item-img img {
            max-width: 100%;
            height: auto;
        }

        .ad-container img {
            width: 100%;
            height: auto;
        }

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
</head>

<body>

    <div id="main">

    <?php include 'navbar.php'; ?>

        <div class="container">
            <?php include 'sidebar.php'; ?>



            <div class="content">
<br><br><br>
                <h2 class="text-center"><?= isset($_GET['kategori']) ? getCategoriName($kategori).' Kategorisindeki Tüm Ürünler' : 'Tüm Ürünler'; ?></h2>
                <hr>
                <br><br>

                <div class="items">


                        <?php 
                        
                        foreach ($urunler as $urun) { // Ürünleri listele
                            echo '<div class="item-box" style="height:auto;">
                            <div class="item-title">
                                <h3 class="text-center">'.$urun['urun_adi'].'</h3>
                            </div>
                            <div class="item-img">
                                <img src="'.$urun['gorsel_yolu'].'" alt="Ürün 1">
                            </div>
                            <div class="item-price">'.$urun['fiyat'].' &#8378;</div>
                            <br>
                            <div class="item-price">'.$urun['adet'].' adet mevcut</div>
                           
                            <br>
                            <div class="item-go" style="margin-bottom:10px;">
                                <a href="urun_detay.php?urun='.$urun['id'].'">Ürüne Git</a>
                                
                            </div>
                            
                        </div>
                        ';
                        }

                        ?>

                </div>

            </div>

        </div>

        <?php include 'footer.php'; ?>


    </div>

</body>

</html>