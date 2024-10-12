<?php

include_once 'sql_scripts.php'; // SQL sorguları dosyası import edildi
session_start(); // Session başlatıldı
$urunler = []; // Son eklenen ürünleri getir

usercontrol(isset($_SESSION['user_id'])? $_SESSION['user_id'] : null, ["firma"]); // Kullanıcı yetkilerini kontrol et

if (isset($_GET['edit_cupon'])) {
    $cupon_id = $_GET['edit_cupon'];
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


                <br><br><br>
                <h1>Kupon Düzenle</h1>
                <br>
                <div>

                    <form action="islemler.php" method="POST">
                        <div>
                            <div class="card-body o-hidden border-1 shadow mb-4" style="border-radius: 7px;">
                                
                                <div class="form-group m-4 mb-2">
                                    <input type="text" class="form-control" id="cupon_name" name="cupon_name" placeholder="Kupon Adı" value="<?= select("cupon",['id' => $cupon_id])['name'] ?>">
                                </div>
                                <br>
                                <div class="form-group m-4 mt-0">
                                    <input type="hidden" name="cupon_id" value="<?= $cupon_id ?>">
                                    <input type="number" name="discount" id="discount" value="<?= select("cupon",['id' => $cupon_id])['discount'] ?>">&nbsp;&nbsp;% İndirim
                                </div>

                                <button class="btn btn-primary m-4 mt-1" style="background-color: #0E3150;" type="submit" name="edit_cupon">Kupon Düzenle</button>
                                
                            </div>
                        </div>
                        
                    </form>
                </div>
                




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
    </script>

</body>

</html>