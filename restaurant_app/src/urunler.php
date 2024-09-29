<?php

include_once 'sql_scripts.php';
session_start();

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $urunler = searchfood($search);
} else {
    $urunler = getallfoodswithrestaurant(); // Tüm ürünleri getir
}
?>


<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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

        <div class="container p-0">
            <?php include 'sidebar.php'; ?>



            <div class="content">
                <br><br><br>
                <div class="search-bar">
                    <form action="urunler.php" method="GET">
                        <div class=" d-flex justify-content-end">
                            <div class="form-group d-flex">
                                <input type="text" class="form-control" name="search" placeholder="Ara..." value="<?= isset($_GET['search']) ? $_GET['search']: "" ?>">
                                <button type="submit" class="btn btn-primary ml-2"><i class="fas fa-search"></i> Ara</button>
                            </div>
                        </div>



                    </form>
                </div>
                <br><br><br>
                <h2 class="text-center">Tüm Ürünler</h2>
                <hr>
                <?php
                 $totalItems = count($urunler); // Toplam ürün sayısını hesapla
                 echo '<h5 class="d-flex justify-content-end">' . $totalItems . ' adet ürün bulundu.</h5><br>';
                ?>
                <br><br>


                <div class="items">

                <div class="container">
    <div class="row d-flex justify-content-between p-2">
        <?php
       
        foreach ($urunler as $index => $urun) { // Ürünleri listele
            // Eğer tek kartsa, tam genişlik; birden fazla kart varsa, yarım genişlik
            $colClass = ($totalItems === 1) ? 'col-12' : 'col-md-6';
            echo '
            <div class="' . $colClass . ' mb-4 d-flex justify-content-center"> <!-- Tek kartı ortalamak için d-flex kullanıldı -->
                <div class="card w-100"> <!-- Kart genişliğini tam yapacak şekilde ayarlandı -->
                    <div class="row g-0 d-flex flex-column flex-md-row p-3"> <!-- Mobilde alt alta, büyük ekranlarda yan yana -->
                        <!-- Görsel kısmı -->
                        <div class="col-md-6 d-flex justify-content-center align-items-center">
                            <div class="item-img">
                                <img src="' . $urun['image_path'] . '" class="rounded img-fluid" alt="' . $urun['name'] . '">
                            </div>
                        </div>
                        
                        <!-- Yazı kısmı -->
                        <div class="col-md-6 d-flex flex-column justify-content-center">
                            <div class="text-center text-md-left"> <!-- Küçük ekranlarda ortalanmış, büyük ekranlarda sola hizalı -->
                                <h5>' . $urun['restaurant_name'] . '</h5>
                                <div class="item-title mb-2">
                                    <h6>' . $urun['name'] . '</h6>
                                </div>
                                <div class="item-price mb-2" style="font-size:2em;">
                                    ' . $urun['price'] . ' &#8378;
                                </div>
                                <div class="item-go mb-3">
                                    <a style="background-color: #051C30; padding:10px; border-radius:7px; color: white;" href="urun_detay.php?urun=' . $urun['id'] . '&restoran=' . $urun['restaurant_id'] . '">Ürüne Git</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
</div>


                </div>
            </div>

        </div>

        <?php include 'footer.php'; ?>


    </div>

    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>