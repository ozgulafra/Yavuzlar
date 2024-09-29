<?php

include_once 'sql_scripts.php'; // SQL sorguları dosyası import edildi
session_start(); // Session başlatıldı

$urunler = []; // Son eklenen ürünleri getir

usercontrol(isset($_SESSION['user_id'])? $_SESSION['user_id'] : null, ["firma"]); // Kullanıcı yetkilerini kontrol et

if (isset($_GET['edit_restaurant'])) {
    $restaurant = select('restaurants', ['id' => $_GET['edit_restaurant']]);
    $restaurant_name = $restaurant['name'];
    $description = $restaurant['description'];
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

        table {

            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th {
            background-color: #0E3150;
            /* Koyu Mavi */
            color: #FCC78A;
            /* Altın Rengi */
            padding: 8px;
        }

        td {
            padding: 8px;
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
        }

        .pagination a {
            background-color: #0E3150;
            /* Koyu Mavi */
            color: #FCC78A;
            /* Altın Rengi */
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid #00008B;
        }

        .pagination a:hover {
            background-color: #0E3150;
            /* Koyu Mavi */
            color: #f2f2f2;
            /* Beyaz */
        }
    </style>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />

</head>

<body>

    <div id="main">

        <?php include 'navbar.php'; ?> <!-- navbar import edildi -->

        <div class="container">
            <?php include 'admin_sidebar.php'; ?> <!-- admin_sidebar import edildi -->



            <div class="content" style="padding: 15px;">


                <br><br>
                <h1>Restoran İşlemleri</h1>
                <br>
                <div class="table-responsive">
                <table id="siparis-table">
    <thead>
        <tr>
            <th>
                <span class="flex items-center">ID</span>
            </th>
            <th>
                <span class="flex items-center">Yemek</span>
            </th>
            <th>
                <span class="flex items-center">Müşteri</span>
            </th>
            <th>
                <span class="flex items-center">Durum</span>
            </th>
            <!-- <th>
                <span class="flex items-center">İşlemler</span>
            </th> -->
        </tr>
    </thead>
    <tbody>
        <?php
        // Veritabanından sipariş verilerini çeken fonksiyonu kullanıyoruz
        foreach (getRestaurantOrders($_GET['edit_restaurant']) as $row) {
            echo '<tr>';
            echo '<td>' . $row['order_id'] . '</td>';
            echo '<td>' . $row['ordered_foods'] . '</td>';
            echo '<td>' . $row['customer_name'] . ' ' . $row['customer_surname'] . '</td>';

            // Sipariş durumunu seçmek için dropdown (combobox) ve bir form ekleyelim
            echo '<td>';
            echo '<form action="islemler.php" method="POST">';
            echo '<input type="hidden" name="order_id" value="' . $row['order_id'] . '">';
            echo '<input type="hidden" name="restaurant_id" value="' . $_GET['edit_restaurant'] . '">';
            echo '<select name="order_status" class="form-control">';
            echo '<option value="bekliyor" ' . ($row['order_status'] == 'bekliyor' ? 'selected' : '') . '>Beklemede</option>';
            echo '<option value="hazirlaniyor" ' . ($row['order_status'] == 'hazirlaniyor' ? 'selected' : '') . '>Hazırlanıyor</option>';
            echo '<option value="tamamlandi" ' . ($row['order_status'] == 'tamamlandi' ? 'selected' : '') . '>Tamamlandı</option>';
            echo '<option value="iptal_edildi" ' . ($row['order_status'] == 'iptal_edildi' ? 'selected' : '') . '>İptal Edildi</option>';
            echo '</select>';
            echo '<button type="submit" name="order_status_update" style="background-color:#157347;" class="btn btn-success mt-2">Kaydet</button>';
            echo '</form>';
            echo '</td>';

            // Siparişi silmek ve düzenlemek için butonlar
            // echo '<td>';
            // echo '<div class="d-flex">';
            // echo '<form action="islemler.php" method="POST">';
            // echo '<input type="hidden" name="order_id" value="' . $row['order_id'] . '">';
            // echo '<input type="hidden" name="restaurant_id" value="' . $_GET['edit_restaurant'] . '">';
            // echo '<button type="submit" name="delete_order" style="background-color:#BB2D3B" class="btn btn-danger mt-2">Sil</button>';
            // echo '</div>';
            // echo '</td>';

            echo '</tr>';
        }
        ?>
    </tbody>
</table>

</div>
                <br><br>
                <div>
    <form action="islemler.php" method="POST">
        <div>
            <div class="card-body o-hidden border-1 shadow mb-4" style="border-radius: 7px;">
                <div class="form-group m-4 mb-2">
                    <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" placeholder="Restoran Adı" value="<?= $restaurant_name ?>">
                </div>
                <br>
                <div class="form-group m-4 mt-0">
                    <input type="hidden" name="restoran_id" value="<?= $_GET['edit_restaurant'] ?>">
                    <textarea class="form-control" id="description" name="description" placeholder="Restoran Açıklaması"><?= $description ?></textarea>
                </div>
                <button class="btn btn-primary m-4 mt-1" style="background-color: #0E3150;" type="submit" name="edit_restaurant">Restoran Bilgilerini Düzenle</button>
            </div>
        </div>
    </form>
</div>

<br><br>

<h1>Yemek İşlemleri</h1>
<br>
<div>
    <form action="islemler.php" method="POST">
        <div>
            <div class="card-body o-hidden border-1 shadow mb-4" style="border-radius: 7px;">
                <div class="form-group m-4 mb-2">
                    <input type="text" class="form-control" id="food_name" name="food_name" placeholder="Yemek Adı">
                </div>
                <br>
                <div class="form-group m-4 mt-0">
                    <input type="hidden" name="restoran_id" value="<?= $_GET['edit_restaurant'] ?>">
                    <textarea class="form-control" id="description" name="description" placeholder="Yemek Açıklaması"></textarea>
                    <br>
                    <input class="form-control" type="tel" name="price" id="price" value="0">
                </div>
                <button class="btn btn-primary m-4 mt-1" style="background-color: #0E3150;" type="submit" name="add_food">Yemek Ekle</button>
            </div>
        </div>
    </form>
</div>

<br><br>

<h1>Yemekler</h1>
<br>
<div class="table-responsive">
    <table id="search-table">
        <thead>
            <tr>
                <th>
                    <span class="flex items-center">ID</span>
                </th>
                <th>
                    <span class="flex items-center">Adı</span>
                </th>
                <th>
                    <span class="flex items-center">İşlemler</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
             foreach (getfoodlistfromrestaurant($_GET['edit_restaurant']) as $row) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>';
                echo '<div class="d-flex">';
                echo '<a href="islemler.php?delete_food=' . $row['id'] . '" class="btn btn-danger mr-2">Sil</a>';
                echo '<a href="yemek.php?edit_food=' . $row['id'] . '" class="btn btn-primary">Düzenle</a>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }
            
            ?>
        </tbody>
    </table>
</div>
<br><br>


<h1>Kupon İşlemleri</h1>
<br>
<div>
    <form action="islemler.php" method="POST">
        <div>
            <div class="card-body o-hidden border-1 shadow mb-4" style="border-radius: 7px;">
                <div class="form-group m-4 mb-2">
                    <input type="text" class="form-control" id="cupon_name" name="cupon_name" placeholder="Kupon Adı" value="">
                </div>
                <br>
                <div class="form-group m-4 mt-0">
                    <input type="hidden" name="restoran_id" value="<?= $_GET['edit_restaurant'] ?>">
                    <input type="number" name="discount" id="discount" value="0">&nbsp;&nbsp;% İndirim
                </div>
                <button class="btn btn-primary m-4 mt-1" style="background-color: #0E3150;" type="submit" name="add_cupon">Kupon Ekle</button>
            </div>
        </div>
    </form>
</div>
<br><br>
<h1>Kuponlar</h1>
<br>
<div class="table-responsive">
    <table id="kupon-table">
        <thead>
            <tr>
                <th>
                    <span class="flex items-center">ID</span>
                </th>
                <th>
                    <span class="flex items-center">Adı</span>
                </th>
                <th>
                    <span class="flex items-center">İndirim Yüzdesi</span>
                </th>
                <th>
                    <span class="flex items-center">İşlemler</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
           foreach (getrestaurantscuponlist($_GET['edit_restaurant']) as $row) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td>' . $row['discount'] . '</td>';
            echo '<td>';
            echo '<div class="d-flex">';
            echo '<a href="islemler.php?delete_cupon=' . $row['id'] . '" class="btn btn-danger mr-2">Sil</a>';
            echo '<a href="kupon.php?edit_cupon=' . $row['id'] . '" class="btn btn-primary">Düzenle</a>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
        }
            ?>
        </tbody>
    </table>
</div>
<br><br>


            </div>

        </div>

        

        <?php include 'footer.php'; ?> <!-- footer kısmı import edildi -->


    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    <script>
        if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#search-table", {
                searchable: true,
                sortable: false
            });
        }
 $('#search-table').DataTable(); 


        if (document.getElementById("kupon-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#kupon-table", {
                searchable: true,
                sortable: false
            });
        }
       
        $('#kupon-table').DataTable(); 
        
        if (document.getElementById("siparis-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#siparis-table", {
                searchable: true,
                sortable: false
            });
        }
       
        $('#siparis-table').DataTable(); 
    </script>

</body>

</html>