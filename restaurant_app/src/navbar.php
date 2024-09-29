<header>
    <?php
    if (!isset($_SESSION["user_id"])) {// Eğer kullanıcı giriş yapmamışsa login false olacak
        $login = false;
    } else {
        $login = true;
    } 
    if ($login) { // Eğer kullanıcı giriş yapmışsa
        echo '    <style>
    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #0E3150;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #051C30;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }
</style>';
    }

    ?>
    <div class="my-header">
        <div class="header-logo">
            <img src="img/logo.svg" width="75px" alt="myshop-logo" />
        </div>
        <div class="header-menu">
            <a href="index.php">Anasayfa</a>
            <?php

            if (isset($_SESSION["admin"])) {
                echo '<a href="firma_paneli.php">Admin Paneli</a>';
            } ?>
            <a href="urunler.php">Ürünler</a>
            <a href="restoran.php">Restoranlar</a>
            <a href="iletisim.php">İletişim</a>
        </div>

        <div class="header-user">

            <?php

            if ($login) { // Eğer kullanıcı giriş yapmışsa
                echo '<div class="header-sepetim">
                <div class="dropdown">
                <a href="profile.php">Hesabım</a>
                <div class="dropdown-content">
                    <a href="profile.php">Profilim</a>
                    <a href="islemler.php?logout=1">Çıkış</a>
                </div>
            </div>';
            } else { // Eğer kullanıcı giriş yapmamışsa
                echo '<div class="header-hesabim">
                <div>Hesabım
                    <span>
                        <a href="login.php">Giriş Yap</a>/
                        <a href="register.php">Kayıt Ol</a>
                    </span>
                </div>
            </div>';
            }

            ?>


        </div>


        <?php

        if ($login) { // Eğer kullanıcı giriş yapmışsa
            echo '<div class="header-sepetim" style="margin-left:25px;">
                <span><a href="sepetim.php">Sepetim</a></span></div>';
        }

        ?>


    </div>
</header>