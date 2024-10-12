<?php
include_once 'sql_scripts.php';
session_start();

usercontrol(isset($_SESSION['user_id'])? $_SESSION['user_id'] : null, ["normal"]); // Kullanıcı yetkilerini kontrol et

$toplam = 0;

if (isset($_POST['alisveris'])) { // Eğer POST isteği alışverişse (Alışveriş tamamlama isteği)
    $userid = $_SESSION["user_id"]; // Kullanıcı ID'sini al
    $sepet = listSepetItems($userid); // Sepetteki ürünleri getir

    $toplam = 0; // Toplam tutarı sıfırla
    foreach ($sepet as $key => $value) { // Sepetteki ürünlerin fiyatlarını topla
        $toplam += getUrun($value['urun_id'])['fiyat'] * $value['adet']; // Ürün fiyatı * adet
    }
}

?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- CSS -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/form.css">

    <style>
        /* form.css veya uygun stil dosyasına ekleyin */
        .form-container h3 {
            margin: 20px 0;
            font-size: 1.2em;
            color: #333;
        }

        .buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .buttons .cancel-btn {
            text-decoration: none;
            color: #fff;
            background-color: #dc3545;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .buttons .cancel-btn:hover {
            background-color: #c82333;
        }
    </style>

    <title>myshop Alışveriş Tamamlama</title>
</head>

<body>

    <div id="main">

        <?php include 'navbar.php'; ?>

        <div class="space"></div>

        <div class="form-container">
            <h2>Alışverişi Tamamla</h2>

            <form id="checkoutForm" method="POST" name="ok_card" action="islemler.php" style="margin: 15px 25px;">

                <h3>Toplam Tutar: <?= $toplam ?> TL</h3>
                <br><br>
                <label for="cardname">Kart Üzerindeki İsim</label>
                <input id="cardname" name="cardname" type="text" placeholder="Kart Üzerindeki İsim" required />

                <label for="cardnumber">Kart Numarası</label>
                <input id="cardnumber" name="cardnumber" type="text" placeholder="Kart Numarası" required />

                <label for="expdate">Son Kullanma Tarihi</label>
                <input id="expdate" name="expdate" type="text" placeholder="AA/YY" required />

                <label for="cvv">CVV</label>
                <input id="cvv" name="cvv" type="text" placeholder="CVV" required />

                <div class="buttons">
                    <button type="submit" name="ok_card">Ödemeyi Tamamla</button>
                    <a href="index.php" class="cancel-btn">İptal</a>
                </div>

            </form>
        </div>

        <div class="space"></div>

        <?php include 'footer.php'; ?>

    </div>

</body>

</html>