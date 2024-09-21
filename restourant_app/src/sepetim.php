<?php
session_start();
include_once 'sql_scripts.php';

$category = getCategories(); // Kategorileri getir
$urunler = listProducts(); // Ürünleri getir

if (isset($_SESSION['user_id'])) {
    $sepet = listSepetItems($_SESSION['user_id']);
    $userid = $_SESSION['user_id'];
    $toplam = 0;
} else {
    header("Location: login.php");
    exit();
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
    <link rel="stylesheet" href="css/sepetim.css">

    <style>
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .trash {
            background-color: crimson;
        }

        .trash:hover {
            background-color: darkred;
        }
    </style>

    <title>myshop haberler</title>

    <style>
        .slideshow-container {
            position: relative;
            max-width: 100%;
            margin: auto;
        }

        .mySlides {
            display: none;
        }

        .mySlides img {
            width: 100%;
        }

        .fade {
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @keyframes fade {
            from {
                opacity: .4
            }

            to {
                opacity: 1
            }
        }

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
            <div class="sidebar">
                <h3 id="kategoriler">Kategoriler</h3>
                <ul class="main-ul">
                    <li class="top-category">

                        <ul class="sub-category">
                            <?php

                            foreach ($category as $key => $value) {
                                echo '<li><a href="urunler.php?kategori=' . $value['id'] . '">' . $value['kategori_adi'] . '</a></li>';
                            }

                            ?>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="content" style="margin: 20px 50px;">
                <div class="sepetim-header">
                    <h3 class="text-center"><i class="fas fa-shopping-cart"></i> Sepetim</h3>
                </div>

                <div class="sepetim">

                    <?php

                    if (empty($sepet)) { // Eğer sepet boşsa
                        echo '<div class="sepet-bos">
                        <i class="far fa-times-circle"></i>
                        <p> SEPETİNİZ BOŞ </p>
                    </div>';
                    }

                    if ($login == false) { // Eğer kullanıcı giriş yapmamışsa
                        echo '<div class="sepet-bos">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p> Sepete ürün ekleyebilmek için giriş yapmanız gerekmektedir.</p>
                        <a href="login.html">Giriş Yap</a>
                    </div>';
                    }

                    if ($login == true && $sepet == true) { // Eğer kullanıcı giriş yapmış ve sepet doluysa
                        echo '<div class="sepet-table"><table><tbody>';
                        $toplam = 0;
                        foreach ($sepet as $key => $value) {
                            
                            $sepet_id = $value['sepet_id'];
                            $toplam += getUrun($value['urun_id'])['fiyat'] * $value['adet'];
                            echo '<tr><td>' . (array_search($value, $sepet) + 1) . '</td>
                            <td>' . getUrun($value['urun_id'])['urun_adi'] . '</td>
                            <td>' . $value['adet'] . ' Adet</td>
                            <td>' . (getUrun($value['urun_id'])['fiyat'] * $value['adet']) . ' &#8378;</td>
                            <td>
                            <form action="islemler.php" name="sepetten_cikar" method="GET">
                            <input type="hidden" name="item_id" value="' . $value['id'] . '">
                            <input type="hidden" name="urun_id" value="' . $value['urun_id'] . '">
                            <input type="hidden" name="sepet_id" value="' . $value['sepet_id'] . '">
                            <button type="submit" class="remove-from-cart-btn btn trash" name="sepetten_cikar" value="1" >
                            <i class="fas fa-trash-alt"></i> Sepetten Çıkar
                            </button>
                            </form>
                            </td>
                            </tr>';
                        }

                        echo '</tbody></table></div>';
                    }


                    ?>

                </div><br><br>
                <h3>Toplam: <?= $toplam; ?> &#8378;</h3>
                <?php

                if (!empty($sepet)) { // Eğer sepet doluysa
                    echo '<div style="margin-top: 50px;">
                    <form action="islemler.php" method="post">
                        <button type="submit" class="remove-from-cart-btn btn trash" name="del_cart" value="1">
                            <i class="fas fa-trash-alt"></i> Sepeti Temizle
                        </button>
                    </form>
                    <br>
                    <form action="alisveris.php" name="alisveris" method="post">
                        <input type="hidden" name="sepet_id" value="' . $sepet_id . '">
                        <button type="submit" class="remove-from-cart-btn btn" name="alisveris" value="1">Alışverişi Tamamla</button>
                    </form>
                    
                </div>';
                }
                ?>

            </div>
        </div>

        <?php include 'footer.php'; ?>

    </div>


    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // URL'den alışveriş başarılı parametresini kontrol et
            const urlParams = new URLSearchParams(window.location.search);
            const alisverisOk = urlParams.get('alisveris_ok');

            // Eğer alışveriş başarılı ise SweetAlert2 ile iletişim kutusu göster
            if (alisverisOk === '1') {
                Swal.fire({
                    icon: 'success',
                    title: 'Ödeme Başarılı!',
                    text: 'Ödemeniz başarıyla tamamlandı.',
                    confirmButtonText: 'Tamam'
                }).then((result) => {
                    // Anasayfaya yönlendirme
                    if (result.isConfirmed) {
                        window.location.href = 'sepetim.php';
                    }
                });
            }
            else if(alisverisOk === '0') {
                Swal.fire({
                    icon: 'error',
                    title: 'Ödeme Başarısız!',
                    text: 'Ödeme işlemi iptal edildi veya bir hata oluştu.',
                    confirmButtonText: 'Tamam'
                }).then((result) => {
                    // Anasayfaya yönlendirme
                    if (result.isConfirmed) {
                        window.location.href = 'sepetim.php';
                    }
                });
            }
        });
    </script>



</body>

</html>