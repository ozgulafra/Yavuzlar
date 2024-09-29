<?php

include_once 'sql_scripts.php';
session_start();


// Sayfa numarasını al
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 20;
$offset = ($page - 1) * $records_per_page;

// Toplam kayıt sayısını al
$total_result = $conn->query("SELECT COUNT(*) AS total FROM siparis_gecmisi");
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];

// Verileri çek
$query = "SELECT urunler.id , urunler.urun_adi , urunler.fiyat, kategoriler.kategori_adi AS kategori , urunler.adet FROM urunler LEFT JOIN kategoriler ON urunler.kategori = kategoriler.id LIMIT $records_per_page OFFSET $offset";
$result = $conn->query($query);

// Toplam sayfa sayısını hesapla
$total_pages = ceil($total_records / $records_per_page);

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
            border: 1px solid #0E3150;
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
            font-size: 13px;
        }

        .pagination a,input[type="submit"] {
            background-color: #0E3150;
            /* Koyu Mavi */
            color: #FCC78A;
            /* Altın Rengi */
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid #00008B;
        }

        .pagination a:hover, input[type="submit"]:hover {
            background-color: #0E3150;
            /* Koyu Mavi */
            color: #f2f2f2;
            /* Beyaz */
        }
    </style>
</head>

<body>

    <div id="main">

        <?php include 'navbar.php'; ?>

        <div class="container">
            <?php include 'admin_sidebar.php'; ?>



            <div class="content" style="padding: 15px;">

                <br><br>
                <div class="pagination" style="justify-content: end;">
                    <a href="urun_ekle.php">Yeni Ürün Ekle</a>
                </div>
                <br>
                <h1>Ürün Kayıtları</h1>
                <br>
                <table>
                    <thead>
                        <tr>
                            <th>Ürün ID</th>
                            <th>Ürün Adı</th>
                            <th>Adet</th>
                            <th>Fiyat</th>
                            <th>Kategori</th>
                            <th>İşlemler</th>
                            
                            <!-- Diğer sütunlar -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['urun_adi']); ?></td>
                                <td><?php echo htmlspecialchars($row['adet']); ?></td>
                                <td><?php echo htmlspecialchars($row['fiyat']); ?></td>
                                <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                                <td>
                                    <div class="pagination" style="margin: 0;">
                                    <form action="islemler.php" name="del_product" method="post" style="margin: 8px;">
                                        <input type="hidden" name="urun_id" value="<?php echo $row['id'];?>">
                                        <a href="urun_duzenle.php?id=<?php echo $row['id']; ?>">Düzenle</a>
                                        <input type="submit" name="del_product" value="Sil">
                                    </form>
                                        
                                    </div>
                                    
                                </td>
                                <!-- Diğer sütunlar -->
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Sayfalama -->
                <div class="pagination">
                    <?php if ($page > 1) : ?>
                        <a href="?page=<?php echo $page - 1; ?>">Önceki</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages) : ?>
                        <a href="?page=<?php echo $page + 1; ?>">Sonraki</a>
                    <?php endif; ?>
                </div>

            </div>

        </div>

        <?php include 'footer.php'; ?>


    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const urun_edit = urlParams.get('edit_ok');

            if (urun_edit === '1') {
                Swal.fire({
                    icon: 'success',
                    title: 'Ürün Verileri Güncellendi!',
                    showConfirmButton: false,
                    timer: 1500,
                    didClose: () => {
                        window.location.href = window.location.pathname;
                    }
                });
            } else if (urun_edit === '0') {
                Swal.fire({
                    icon: 'error',
                    title: 'Ürün Verileri Güncellenemedi!',
                    showConfirmButton: false,
                    timer: 1500,
                    didClose: () => {
                        window.location.href = window.location.pathname;
                    }
                });
            }

            const urun_sil = urlParams.get('del_ok');

            if (urun_sil === '1') {
                Swal.fire({
                    icon: 'success',
                    title: 'Ürün Silindi!',
                    showConfirmButton: false,
                    timer: 1500,
                    didClose: () => {
                        window.location.href = window.location.pathname;
                    }
                });
            } else if (urun_sil === '0') {
                Swal.fire({
                    icon: 'error',
                    title: 'Ürün Silinemedi!',
                    showConfirmButton: false,
                    timer: 1500,
                    didClose: () => {
                        window.location.href = window.location.pathname;
                    }
                });
            }

            const urun_ekle = urlParams.get('add_ok');

            if (urun_ekle === '1') {
                Swal.fire({
                    icon: 'success',
                    title: 'Ürün Başarıyla Eklendi!',
                    showConfirmButton: false,
                    timer: 1500,
                    didClose: () => {
                        window.location.href = window.location.pathname;
                    }
                });
            } else if (urun_ekle === '0') {
                Swal.fire({
                    icon: 'error',
                    title: 'Ürün Ekleme Başarısız!',
                    showConfirmButton: false,
                    timer: 1500,
                    didClose: () => {
                        window.location.href = window.location.pathname;
                    }
                });
            }
        });
    </script>

</body>

</html>