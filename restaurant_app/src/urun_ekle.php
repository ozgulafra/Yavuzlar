<?php

include_once 'sql_scripts.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
if (!isset($_SESSION['admin']))
{
    header("Location: index.php");
    exit();
}


$categories = getCategories();

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fontawseome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/form.css">
    <title>myshop kayıt ol</title>
</head>

<body>

    <div id="main">

        <?php include 'navbar.php'; ?>

        <div class="space"></div>

        <div class="form-container">
            <h2>Ürün Ekle</h2>

            <form id="registerForm" method="POST" name="add_product" action="islemler.php" enctype="multipart/form-data">

                <label for="product_name">Ürün Adı</label>
                <input id="product_name" name="product_name" type="text" placeholder="Ürün Adı" required/>

                <label for="description">Açıklama</label>
                <input id="description" name="description" placeholder="Açıklama" required></input>

                <label for="price">Fiyat</label>
                <input id="price" name="price" type="number" step="0.01" placeholder="Fiyat" required/>

                <label for="category">Kategori</label>
                <select style="margin: 5px 0 10px 0; width: auto; padding: 10px; border: 1px solid #dfdfdf; border-radius: 5px;" id="category" name="category" required>
                    <option value="">Kategori Seçin</option>
                    <?php
                    foreach ($categories as $category) {
                        echo "<option value='".$category['id']."'>".$category['kategori_adi']."</option>";
                    }
                    ?>
                </select>

                <label for="quantity">Adet</label>
                <input id="quantity" name="quantity" type="number" placeholder="Adet" min="1" value="1" required/>

                <label for="image">Ürüne Ait Resim Bilgisi</label>
                <input id="image" name="image" type="file" accept="image/*" required/>

                <div class="buttons">
                    <button type="submit" name="add_product">Ürün Ekle</button>
                    <a href="urun_yonetimi.php">İptal</a>
                </div>

            </form>
        </div>

        <div class="space"></div>

        <?php include 'footer.php';?>

    </div>



</body>

</html>