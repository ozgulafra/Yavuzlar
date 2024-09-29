<?php

$name = $_POST["name"]; // POST ile gelen tam ad alındı
    $surname = $_POST["surname"]; // POST ile gelen telefon numarası alındı
    $username = $_POST["username"]; // POST ile gelen eposta alındı

    $password = $_POST["password"]; // POST ile gelen şifre alındı



   echo "Ad: ".$name."<br>";
    echo "Soyad: ".$surname."<br>";
    echo "Kullanıcı Adı: ".$username."<br>";
    echo "Kullanıcı Şifresi: ".$password."<br>";

?>