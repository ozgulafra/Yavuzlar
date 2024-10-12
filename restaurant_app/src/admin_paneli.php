<?php

include_once 'sql_scripts.php'; // SQL sorguları dosyası import edildi
session_start(); // Session başlatıldı
$urunler = []; // Son eklenen ürünleri getir


usercontrol(isset($_SESSION['user_id'])? $_SESSION['user_id'] : null, ["admin"]); // Kullanıcı yetkilerini kontrol et

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
                <h1 style="font-size: 2em;">Firma Listesi</h1>
                <br>
                <div class="table-responsive">

                    <table id="search-table">
                        <thead>
                            <tr>
                                <th>
                                    <span class="flex items-center">
                                        ID
                                    </span>
                                </th>
                             
                                <th>
                                    <span class="flex items-center">
                                        Adı
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        İşlemler
                                    </span>
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody>

<?php

foreach (getallcompany() as $row)
{
    echo '<tr>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['name'] . '</td>';
    echo '<td>';
    echo '<div class="d-flex">';
    echo '<form action="islemler.php" method="POST">';
    echo '<input type="hidden" name="company_id" value="' . $row['id'] . '">';
    echo '<button type="submit" name="delete_company" class="btn btn-danger mr-2" style="background-color: #C63D3A;">50 YIL BANLA</a>';
    echo '</form>';
    // echo '<form action="islemler.php" method="POST">';
    // echo '<input type="hidden" name="company_id" value="' . $row['id'] . '">';
    // echo '<button type="submit" name="edit_company" class="btn btn-primary mr-2" style="background-color: #0E3150;">Düzenle</a>';
    // echo '</form>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
}

?>
                        </tbody>
                    </table>


                </div>


                <br><br><br>
                <h1 style="font-size: 2em;">Müşteri Listesi</h1>
                <br>
                <div class="table-responsive">

                    <table id="search-table">
                        <thead>
                            <tr>
                                <th>
                                    <span class="flex items-center">
                                        ID
                                    </span>
                                </th>
                             
                                <th>
                                    <span class="flex items-center">
                                        Tam Adı
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        İşlemler
                                    </span>
                                </th>
                                
                            </tr>
                        </thead>
                        <tbody>

<?php

foreach (getallcustomer() as $row)
{
    echo '<tr>';
    echo '<td>' . $row['id'] . '</td>';
    echo '<td>' . $row['name'] . " " . $row['surname'] . " (". $row['username'] . ')</td>';
    echo '<td>';
    echo '<div class="d-flex">';
    echo '<form action="islemler.php" method="POST">';
    echo '<input type="hidden" name="customer_id" value="' . $row['id'] . '">';
    echo '<button type="submit" name="delete_customer" class="btn btn-danger mr-2" style="background-color: #C63D3A;">50 YIL BANLA</a>';
    echo '</form>';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
}

?>
                        </tbody>
                    </table>


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