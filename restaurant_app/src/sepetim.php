<?php
session_start();
include_once 'sql_scripts.php';


if (isset($_SESSION['user_id'])) {
    $sepet = listSepetItems($_SESSION['user_id']);
    $userid = $_SESSION['user_id'];
    $toplam = 0;
} else {
    header("Location: login.php");
    exit();
}

if (isset($_GET['cupon'])) {
    $alisverisOk = $_GET['cupon'];
}

usercontrol(isset($_SESSION['user_id'])? $_SESSION['user_id'] : null, ["normal"]); // Kullanıcı yetkilerini kontrol et

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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


                            $toplam += getfood($value['food_id'])['price'] * $value['quantity'];
                            echo '<tr>
                            
                            <td>' . getfood($value['food_id'])['name'] . '</td>
                            <td>' . $value['quantity'] . ' Adet</td>
                            <td>' . (getfood($value['food_id'])['price'] * $value['quantity']) . ' &#8378;</td>
                            <td>
                            <form action="islemler.php" method="POST">
                            <input type="hidden" name="item_id" value="' . $value['id'] . '">
                            <input type="hidden" name="urun_id" value="' . $value['food_id'] . '">
                            <button type="submit" class="remove-from-cart-btn btn trash" name="sepetten_cikar" value="1" >
                           
                            <i class="fas fa-trash-alt"></i> Sepetten Çıkar
                            </button>
                            <button type="button" class="edit-from-cart-btn btn" data-toggle="modal" data-target="#exampleModal" data-item="' . $value['id'] . '" data-urun="' . $value['food_id'] . '"  data-whatever="'.$value['quantity'].'" data-note="'.$value['note'].'" data-title="'.getfood($value['food_id'])['name'].'">
                                <i class="fas fa-edit"></i> Düzenle
                            </button>
                            </form>
                            </td>
                            </tr>';
                        }

                        echo '</tbody></table></div>';
                    }


                    ?>

                </div><br><br>


                <?php
                
                if (isset($alisverisOk)) {
                    $cupon_yuzde = isset(selectFA('cupon', ['name' => $alisverisOk])[0]['discount']) ? selectFA('cupon', ['name' => $alisverisOk])[0]['discount'] : 0;
                    if ($cupon_yuzde == null || $cupon_yuzde == 0) {
                        $cupon_yuzde = 0;
                    }
                    else
                    {
                        $toplam = $toplam - ($toplam * $cupon_yuzde / 100);
                    }
                    
                }

                ?>



                <h3>Toplam: <?= $toplam; ?> &#8378;</h3>
                <?php

                if (!empty($sepet)) { // Eğer sepet doluysa
                    echo '<div style="margin-top: 50px;">
                    <form action="islemler.php" method="post">
                        <button type="submit" class="remove-from-cart-btn btn trash" name="clear_basket" value="1">
                            <i class="fas fa-trash-alt"></i> Sepeti Temizle
                        </button>
                    </form>
                    <br>
                    <form action="sepetim.php" method="GET">
                        <div class="form-group">
                            <label for="cupon">Kupon Kodu:</label>
                            <input type="text" class="form-control" id="cupon" name="cupon" placeholder="Kupon kodunu girin">
                        </div>
                        <button type="submit" class="btn btn-primary">Kuponu Uygula</button>
                    </form>
                    <br>
                    <form action="islemler.php" name="alisveris" method="post">
                        <input type="hidden" name="user_id" value="' . $_SESSION['user_id'] . '">
                        <input type="hidden" name="total" value="' . $toplam . '">
                        <button type="submit" class="remove-from-cart-btn btn" name="alisveris" value="1">Alışverişi Tamamla</button>
                    </form>
                    
                </div>';
                }
                ?>


                <!-- MODAL -->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div> 
                            <form action="islemler.php" method="post">
                            <div class="modal-body">
                               
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">Adet:</label>
                                        <input class="form-control" style="text-align: center;" type="number" id="adet" name="quantity" min="1" max="100" value="" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="message-text" class="col-form-label">Sipariş Notu:</label>
                                        <textarea class="form-control" id="snote" name="snote"></textarea>

                                        <input type="hidden" name="item_id" value="">
                                        <input type="hidden" name="urun_id" value="">
                                    </div>
                                
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">İptal</button>
                                <button type="submit" class="btn btn-primary" name="edit_basket">Değişiklikleri Onayla</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
                <!-- MODAL -->


                <div class="sepetim-header mt-4">
                    <h3 class="text-center"><i class="fas fa-shopping-cart"></i> Sipariş Geçmişim</h3>
                </div>
                <div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Ürün Adı</th>
                                <th scope="col">Adet</th>
                                <th scope="col">Toplam</th>
                                <th scope="col">Sipariş Notu</th>
                                <th scope="col">Sipariş Tarihi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $siparisler = listOrders($userid);
                            foreach ($siparisler as $key => $value) {
                                echo '<tr>
                                <td>' . $value['name'] . '</td>
                                <td>' . $value['quantity'] . ' Adet</td>
                                <td>' . $value['total'] . ' &#8378;</td>
                                <td>' . $value['note'] . '</td>
                                <td>' . $value['siparis_tarihi'] . '</td>
                            </tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>


            </div>
        </div>

        <?php include 'footer.php'; ?>

    </div>


    <!-- JS -->
    <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var recipient = button.data('whatever'); // Extract info from data-* attributes
            var oldnote = button.data('note');
            var title = button.data('title');
            var adet = document.getElementById('adet');
            adet.value = recipient;
            var note = document.getElementById('snote');
            note.value = oldnote;
            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this)
            modal.find('.modal-title').text(title);

            var item_id = button.data('item');
            var urun_id = button.data('urun');

            modal.find('.modal-body input[name="item_id"]').val(item_id);
            modal.find('.modal-body input[name="urun_id"]').val(urun_id);
            
        })
    </script>


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
            } else if (alisverisOk === '0') {
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