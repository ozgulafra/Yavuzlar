<footer>
            <div class="my-footer" style="margin-top:50px;">


                <div class="footer-content">
                    <div class="footer-logo"><img width="75px" src="img\white_logo.svg" alt="myshop" /></div>
                    
                    <div class="footer-menu">
                        <a href="index.php">Anasayfa</a>
                        <a href="urunler.php">Ürünler</a>
                        <a href="iletisim.php">İletişim</a>
                    </div>

                    <div class="footer-user">
                        <?php if (!isset($_SESSION['user_id']))  { echo '<a href="login.php">Giriş Yap</a>
                        <a href="register.php">Kayıt Ol</a>';} 
                        else {
                            if (isset($_SESSION['admin'])) {
                                echo '<a href="firma_paneli.php">Admin Paneli</a>';
                            }
                            echo ' <a href="sepetim.php">Sepete Git</a>';
                            }?>
                        
                       
                    </div>

                    <div class="footer-social-media">
                        <a href="http://www.facebook.com" target="_blank"><i class="fab fa-facebook"></i></a>
                        <a href="http://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                        <a href="http://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="http://www.pinterest.com" target="_blank"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>

                <div class="footer-copyright">
                    <span>&copy; Copyright 2021 - Tüm hakları saklıdır.</span>
                </div>
            </div>
        </footer>