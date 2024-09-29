<?php
include_once 'sql_scripts.php';


session_start(); // Session başlatma
if (isset($_GET['restoran'])) { // Eğer urun parametresi varsa
    $data = getrestaurantdetail($_GET['restoran']); // Ürün bilgilerini getir

    $urun = [
        'id' => $data['id'],
        'ad' => $data['name'],
        'aciklama' => $data['description'],
        'img' => $data['image_path']
    ]; // Ürün bilgilerini diziye ata

    $restoran_score = getRestaurantScore($_GET['restoran']); // Restoran puanını getir



} else {
    header("Location: index.php"); // Eğer urun parametresi yoksa anasayfaya yönlendir
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
    <link rel="stylesheet" href="css/iletisim.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@9.0.3"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.css" rel="stylesheet" />

    <style>
        .custom-button {
            display: inline-block;
            padding: 7px 20px;
            background-color: #0E3150;
            color: white;
            text-align: center;
            font-size: 14px;
            border: 2px solid #0E3150;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            transition: color 0.3s ease;
        }

        .custom-button:hover {
            background-color: white;
            color: #051C30;
        }

        .custom-del-button {
            display: inline-block;
            padding: 7px 20px;
            background-color: rgb(195, 0, 0);
            color: white;
            text-align: center;
            font-size: 14px;
            border: 2px solid rgb(195, 0, 0);
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            transition: color 0.3s ease;
        }

        .custom-del-button:hover {
            background-color: white;
            color: rgb(195, 0, 0);
        }

        .checked {
            color: orange;
        }

        .custom-input {
            padding: 7px;
            margin-left: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .custom-input:focus {
            border-color: #4CAF50;
            outline: none;
            /* Odaklandığında mavi çerçeveyi kaldırır */
        }
    </style>

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

    <title>
        <?= $urun['ad'] ?> Detayları</title>
</head>

<body>

    <div id="main">

        <?php include 'navbar.php'; ?>

        <div class="container">
            <?php include 'sidebar.php'; ?>
            <div class="content">

                <div class="card" style="display: grid; grid-template-columns: repeat(2, 1fr); ">
                    <div class="card-body">
                        <img src="<?= $urun['img'] ?>" class="w-100 h-100"  style="background-color: black; object-fit: fill;" alt="">
                    </div>

                    <div style="display: flex; justify-content: center; align-items: center;">

                        <div class="p-4 d-flex justify-content-center w-100 h-100">
                            <div class="info">
                            <h1 style="font-size: 2em;">
                                    <?= $urun['ad'] ?>
                                </h1>
                                <hr />
                                <br>
                                <p>
                                    <?= $urun['aciklama'] ?>
                                </p>
                                <br>
                                <div>
                                    <hr>
                                    <h3>Restoran Puanı</h3>
                                    <br>
                                    <?php

                                    foreach (getstar($restoran_score) as $star) {
                                        echo $star;
                                    }

                                    ?>

                                    <hr>
                                </div>

                            </div>


                        </div>
                    </div>




                </div>

                <div>

                </div>

                <div class="mt-4">
                    <h1 style="font-size: 2em;">Yorum Ekle</h1>
                    <hr>
                    <br>
                    <?php


                    if (isset($_SESSION['user_id'])) {

                        echo '<form action="islemler.php" method="POST">
                        <div class="form-group">
                            <!--<label for="surname">Soyad:</label>-->
                            <input type="hidden" class="form-control" id="surname" name="surname" value="' . getUserDetails($_SESSION["user_id"])["surname"] . '" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="title">Başlık:</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="description">Yorum:</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <br>
                        <div class="row">
                        <div class="form-group col-9">
                            <label for="score">Puan:</label>
                            <select class="form-control" id="score" name="score" required>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                                <option value="10">Müq ve Ötesi</option>
                            </select>
                        </div>
                        <div class="col-3 d-flex justify-content-end align-items-end">
                        <input type="hidden" name="restoran" value="' . $_GET['restoran'] . '">
                        <br>
                        <button type="submit" class="custom-button" name="add_comment">Yorum Ekle</button>
                        </div>
                       
                        </div>
                       
                    </form>';
                    } else {
                        echo '<br><p>Yorum yapabilmek için <a href="giris.php">giriş yapmanız</a> gerekmektedir.</p><br>';
                    }


                    ?>

                </div>
                <hr>
                <br><br><br>

                <div class="table-responsive">

                    <table id="search-table">
                        <thead>
                            <tr>
                                <th>
                                    <span class="flex items-center">
                                        Soyad
                                    </span>
                                </th>

                                <th>
                                    <span class="flex items-center">
                                        Başlık
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        Yorum
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        Puan
                                    </span>
                                </th>
                                <th>
                                    <span class="flex items-center">
                                        Yorum Tarihi
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

                            foreach (getRestaurantComments($_GET['restoran']) as $row) {
                                echo '<tr>';
                                echo '<td>' . $row['surname'] . '</td>';
                                echo '<td>' . $row['title'] . '</td>';
                                echo '<td>' . $row['description'] . '</td>';
                                echo '<td>';
                                foreach (getstar($row["score"]) as $star) {
                                    echo $star;
                                }
                                echo '</td>';
                                echo '<td>' . $row['created_at'] . '</td>';
                                echo '<td>';
                                if (isset($_SESSION['user_id'])) {
                                    if ($_SESSION['user_id'] == $row['user_id']) {

                                        echo '<form action="islemler.php" method="POST">';
                                        echo '<input type="hidden" name="comment_id" value="' . $row['id'] . '">';
                                        echo '<input type="hidden" name="restoran_id" value="' . $_GET['restoran'] . '">';
                                        echo '<input type="hidden" name="food" value="">';
                                        echo  '<input type="submit" class="custom-del-button" name="delete_comment" value="Sil">';
                                        echo '</form>';
                                    }
                                }
                                echo '</td>';
                                echo '</tr>';
                            }

                            ?>
                        </tbody>
                    </table>


                </div>

            </div>


        </div>


    </div>


    <?php include 'footer.php'; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const sepete = urlParams.get('sepete_ok');

            if (sepete === '1') {
                Swal.fire({
                    icon: 'success',
                    title: 'Ürün Sepete Eklendi!',
                    showConfirmButton: false,
                    timer: 1500,
                    didClose: () => {
                        window.history.back(); // Bir önceki sayfaya geri dön
                    }
                });
            } else if (sepete === '0') {
                Swal.fire({
                    icon: 'error',
                    title: 'Ürün Sepete Eklenemedi!',
                    showConfirmButton: false,
                    timer: 1500,
                    didClose: () => {
                        window.history.back(); // Bir önceki sayfaya geri dön
                    }
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
    <script>
        if (document.getElementById("search-table") && typeof simpleDatatables.DataTable !== 'undefined') {
            const dataTable = new simpleDatatables.DataTable("#search-table", {
                searchable: false,
                sortable: false
            });
        }
    </script>

</body>

</html>