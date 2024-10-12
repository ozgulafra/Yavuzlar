<?php



include_once 'sql_scripts.php'; // Veritabanı fonksiyonlarını içeren dosya import edildi
session_start(); // Session başlatıldı

// Login (Giriş) işlemi (POST ile gelen veriler alındı. Login adında post var mı kontrol edildi. Var ise giriş işlemi yapılacak.)
if (isset($_POST["login"])) {

    $l_usrname = $_POST["username"]; // POST ile gelen e-posta alındı
    $password = $_POST["password"]; // POST ile gelen şifre alındı
    // Şifre ve e-posta ile kullanıcının var olup olmadığını kontrol ediyoruz
    $conditions = ['username' => $l_usrname];
    $sonuc = select('users', $conditions);

    if ($sonuc && password_verify($password,$sonuc["password"]) && $sonuc["deleted_at"]!="deleted" ) { // Eğer kullanıcı bulunduysa (sorgu sonucu boş değilse)

        $_SESSION["user_id"] = $sonuc["id"]; // Session'a kullanıcı id'si eklendi

        if ($sonuc["role"] == 1) { // Eğer kullanıcı yetkisi 1 ise admin paneline yönlendirilecek
            $_SESSION["logined_user_type"] = "firma"; // Firma girişi
            echo "Giriş Başarılı 3 sn içinde yönlendiriliyorsunuz.<br>Otomatik yönlendirme olmazsa <a href='index.php'>buraya</a> tıklayın.";
            // 3 saniye sonra admin paneline yönlendirilecek
            header("Refresh: 3; url=index.php");
            exit(); // İşlemi sonlandır
        } elseif ($sonuc["role"] == 0) { // Eğer kullanıcı yetkisi 0 ise normal kullanıcı paneline yönlendirilecek
            $_SESSION["logined_user_type"] = "normal"; // Admin paneline erişim izni verildi
            echo "Giriş Başarılı 3 sn içinde anasayfaya yönlendiriliyorsunuz.<br>Otomatik yönlendirme olmazsa <a href='index.php'>buraya</a> tıklayın.";
            // 3 saniye sonra anasayfaya yönlendirilecek
            header("Refresh: 3; url=index.php");
            exit(); // İşlemi sonlandır
        } elseif ($sonuc["role"] == 2) { // Eğer kullanıcı yetkisi 0 ise normal kullanıcı paneline yönlendirilecek
            $_SESSION["logined_user_type"] = "admin"; // Admin paneline erişim izni verildi
            echo "Giriş Başarılı 3 sn içinde anasayfaya yönlendiriliyorsunuz.<br>Otomatik yönlendirme olmazsa <a href='index.php'>buraya</a> tıklayın.";
            // 3 saniye sonra anasayfaya yönlendirilecek
            header("Refresh: 3; url=index.php");
            exit(); // İşlemi sonlandır
        }
        
    } else { // Eğer sorgu sonucunda kullanıcı bulunamadıysa

        echo "Giriş Başarısız 3 sn içinde giriş ekranına yönlendiriliyorsunuz.<br>Otomatik yönlendirme olmazsa <a href='login.php'>buraya</a> tıklayın.";
        // 3 saniye sonra giriş ekranına yönlendirilecek
        header("Refresh: 3; url=login.php");
        exit(); // İşlemi sonlandır
    }
}

// Logout (Çıkış) işlemi (GET ile gelen veriler alındı. Logout adında get var mı kontrol edildi. Var ise çıkış işlemi yapılacak.)
if (isset($_GET["logout"])) {
    session_destroy(); // Session sonlandırıldı
    echo "Çıkış Yapıldı. 3 sn içinde anasayfaya yönlendiriliyorsunuz.<br>Otomatik yönlendirme olmazsa <a href='index.php'>buraya</a> tıklayın.";
    // 3 saniye sonra anasayfaya yönlendirileceği belirtildi
    header("Refresh: 3; url=index.php"); // 3 saniye sonra anasayfaya yönlendirilecek
    exit(); // İşlemi sonlandır
}

if (isset($_POST["add_restaurant"])) {
    $restaurant_name = $_POST["restaurant_name"]; // POST ile gelen restoran adı alındı
    $description = $_POST["description"]; // POST ile gelen açıklama alındı
    $company_id = $_POST["company_id"]; // POST ile gelen şirket id alındı

    $pdo = dbConnect(); // PDO bağlantısı
    $restaurant_data = [
        'name' => $restaurant_name,
        'description' => $description,
        'company_id' => $company_id,
        'image_path' => '_' // Varsayılan değer
    ];

    if (insert('restaurants', $restaurant_data)) {
        echo "New record created successfully";
    } else {
        echo "Error: Could not insert record.";
    }

    header("Location: firma_paneli.php");
    exit();
}


if (isset($_POST["add_food"])) {
    $food_name = $_POST["food_name"]; // POST ile gelen restoran adı alındı
    $description = $_POST["description"]; // POST ile gelen açıklama alındı
    $restaurant_id = $_POST["restoran_id"]; // POST ile gelen şirket id alındı
    $price = $_POST["price"]; // POST ile gelen şirket id alındı

    $pdo = dbConnect(); // PDO bağlantısı
    $food_data = [
        'name' => $food_name,
        'description' => $description,
        'restaurant_id' => $restaurant_id,
        'price' => $price,
        'image_path' => '_' // Varsayılan değer
    ];

    if (insert('food', $food_data)) {
        echo "New record created successfully";
    } else {
        echo "Error: Could not insert record.";
    }

    header("Location: restoran_paneli.php?edit_restaurant=$restaurant_id");
    exit();
}

if (isset($_POST["add_cupon"])) {
    $cupon_name = $_POST["cupon_name"];
    $discount = $_POST["discount"];
    $restaurant_id = $_POST["restoran_id"];

    $pdo = dbConnect(); // PDO bağlantısı
    $cupon_data = [
        'name' => $cupon_name,
        'discount' => $discount,
        'restaurant_id' => $restaurant_id
    ];

    if (insert('cupon', $cupon_data)) {
        echo "New record created successfully";
    } else {
        echo "Error: Could not insert record.";
    }

    header("Location: restoran_paneli.php?edit_restaurant=$restaurant_id");
    exit();
}


// Register (Kayıt) işlemi (POST ile gelen veriler alındı. Register adında post var mı kontrol edildi. Var ise kayıt işlemi yapılacak.)
if (isset($_POST["register"])) {

    $name = isset($_POST["name"]) ? $_POST["name"] : ""; // POST ile gelen tam ad alındı
    $surname = isset($_POST["surname"]) ? $_POST["surname"] : ""; // POST ile gelen telefon numarası alındı
    $username = isset($_POST["username"]) ? $_POST["username"] : ""; // POST ile gelen eposta alındı
    $usertype = isset($_POST["type"]) ? $_POST["type"] : ""; // POST ile gelen adres alındı
    $password = isset($_POST["password"]) ? $_POST["password"] : ""; // POST ile gelen şifre alındı
    $description = isset($_POST["description"]) ? $_POST["description"] : ""; // POST ile gelen açıklama alındı

    $hash = password_hash($password, PASSWORD_ARGON2ID);

    $pdo = dbConnect(); // PDO bağlantısı

    if ($usertype == "normal") {
        // Verileri hazırlayın
        $userData = [
            'name' => $name,
            'surname' => $surname,
            'username' => $username,
            'role' => 0, // Normal kullanıcı için rol
            'password' => $hash,
            'deleted_at' => '_' // Varsayılan değer
        ];

        // insert fonksiyonunu kullanarak tabloya veri ekleyin
        if (insert('users', $userData)) {
            echo "New record created successfully";
        } else {
            echo "Error: Could not insert record.";
        }
    } else {
        // Şirket bilgilerini ekleyin
        $companyData = [
            'name' => $name,
            'description' => $description,
            'logo_path' => '_', // Varsayılan değer
            'deleted_at' => '_'
        ];

        if (insert('company', $companyData)) {
            // En son eklenen şirket kaydını al
            $lastCompany = getLastInsertedRow('company', 'id'); // 'id' AUTO_INCREMENT olan sütun

            if ($lastCompany) {
                $company_id = $lastCompany['id'];

                // Şirketle birlikte kullanıcıyı ekle
                $userData = [
                    'name' => $name,
                    'surname' => $surname,
                    'username' => $username,
                    'role' => 1,
                    'password' => $password,
                    'company_id' => $company_id,
                    'deleted_at' => '_'
                ];

                if (insert('users', $userData)) {
                    echo "New record created successfully";
                } else {
                    echo "Error: Could not insert user.";
                }
            } else {
                echo "Error: Could not retrieve last inserted company.";
            }
        } else {
            echo "Error: Could not insert company.";
        }
    }

    header("Location: login.php");
    exit();
}


if (isset($_GET["delete_restaurant"])) {
    $restaurant_id = $_GET["delete_restaurant"]; // GET ile gelen restoran id alındı

    $pdo = dbConnect(); // PDO bağlantısı

    $conditions = ['id' => $restaurant_id];
    if (delete('restaurants', $conditions)) {
        echo "Record deleted successfully";

        delete('cupon', ['restaurant_id' => $restaurant_id]);
        delete('food', ['restaurant_id' => $restaurant_id]);
        delete('comments', ['restaurant_id' => $restaurant_id]);
    } else {
        echo "Error: Could not delete record.";
    }

    header("Location: firma_paneli.php");
    exit();
}

if (isset($_GET["delete_food"])) {
    $food_id = $_GET["delete_food"]; // GET ile gelen restoran id alındı
    $restaurant_id = getrestaurantidwithfoodid($food_id);
    $pdo = dbConnect(); // PDO bağlantısı

    $conditions = ['id' => $food_id];
    if (update('food', ['deleted_at' => 'deleted'], $conditions)) {
        echo "Record deleted successfully";
    } else {
        echo "Error: Could not delete record.";
    }

    header("Location: restoran_paneli.php?edit_restaurant=$restaurant_id");
    exit();
}

if (isset($_GET["delete_cupon"])) {
    $cupon_id = $_GET["delete_cupon"];
    $restaurant_id = getrestaurantidwithcuponid($cupon_id);
    $pdo = dbConnect();

    $conditions = ['id' => $cupon_id];
    if (delete('cupon', $conditions)) {
        echo "Record deleted successfully";
    } else {
        echo "Error: Could not delete record.";
    }

    header("Location: restoran_paneli.php?edit_restaurant=$restaurant_id");
    exit();
}


if (isset($_POST["edit_food"])) {
    $food_id = $_POST["food_id"];
    $restaurant_id = getrestaurantidwithfoodid($food_id);
    $pdo = dbConnect();

    echo "Record updated successfully";

    update('food',  ['name' => $_POST["food_name"]], ['id' => $food_id]);
    update('food', ['description' => $_POST["description"]], ['id' => $food_id]);
    header("Location: restoran_paneli.php?edit_restaurant=$restaurant_id");
    exit();
}

if (isset($_POST["edit_cupon"])) {
    $cupon_id = $_POST["cupon_id"]; // GET ile gelen restoran id alındı
    $restaurant_id = getrestaurantidwithcuponid($cupon_id);
    $pdo = dbConnect(); // PDO bağlantısı

    echo "Record updated successfully";

    update('cupon',  ['name' => $_POST["cupon_name"]], ['id' => $cupon_id]);
    update('cupon', ['discount' => $_POST["discount"]], ['id' => $cupon_id]);

    header("Location: restoran_paneli.php?edit_restaurant=$restaurant_id");
    exit();
}

if (isset($_POST["edit_restaurant"])) {
    $restaurant_id = $_POST["restoran_id"];

    $pdo = dbConnect();

    echo "Record updated successfully";

    update('restaurants',  ['name' => $_POST["restaurant_name"]], ['id' => $restaurant_id]);
    update('restaurants', ['description' => $_POST["description"]], ['id' => $restaurant_id]);
    header("Location: restoran_paneli.php?edit_restaurant=$restaurant_id");
    exit();
}


if (isset($_POST["order_status_update"])) {
    $conn = dbConnect();

    // POST ile gelen verileri al
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    // SQL sorgusu ile sipariş durumunu güncelle
    $sql = "UPDATE `order` SET order_status = '$order_status' WHERE id = '$order_id'";
    $stmt = $conn->prepare($sql);

    // Sorguyu çalıştır
    if ($stmt->execute()) {
        // Başarıyla güncellendiyse tekrar önceki sayfaya yönlendir
        header("Location: restoran_paneli.php?edit_restaurant=" . $_POST['restaurant_id']);
        exit;
    } else {
        echo "Hata: Sipariş durumu güncellenemedi.";
    }
}


if (isset($_POST["delete_order"])) {

    $conn = dbConnect();

    // GET ile gelen verileri al
    $order_id = $_POST['order_id'];
    $restaurant_id = $_POST['restaurant_id'];

    // 1. order_items tablosundaki ilgili kayıtları sil
    $sql_items = "DELETE FROM order_items WHERE order_id = '$order_id'";
    $stmt_items = $conn->prepare($sql_items);

    // Sorguyu çalıştır
    if ($stmt_items->execute()) {
        // 2. order tablosundaki ilgili siparişi sil
        $sql_order = "DELETE FROM `order` WHERE id = '$order_id'";
        $stmt_order = $conn->prepare($sql_order);

        // Sorguyu çalıştır ve silme işlemi başarılı ise yönlendir
        if ($stmt_order->execute()) {
            // Silme işlemi başarıyla tamamlandıysa geri yönlendir
            header("Location: restaurant_orders.php?edit_restaurant=" . $restaurant_id);
            exit;
        } else {
            echo "Hata: Sipariş silinemedi.";
        }
    } else {
        echo "Hata: Sipariş öğeleri silinemedi.";
    }
}


if (isset($_POST["delete_company"])) {
    $company_id = $_POST["company_id"]; // GET ile gelen restoran id alındı

    $pdo = dbConnect(); // PDO bağlantısı

    $conditions = ['id' => $company_id];

    if (update('company', ['deleted_at' => 'deleted'], $conditions)) {
        echo "Record deleted successfully";

        $conditions = ['company_id' => $company_id];
        $restaurants = select('restaurants', $conditions);

        foreach ($restaurants as $restaurant) {
            $restaurant_id = $restaurant['id'];
            delete('cupon', ['restaurant_id' => $restaurant_id]);
            delete('food', ['restaurant_id' => $restaurant_id]);
            delete('comments', ['restaurant_id' => $restaurant_id]);
        }

        delete('restaurants', ['company_id' => $company_id]);

        header("Location: admin_paneli.php");
        exit();
    } else {
        echo "Error: Could not delete record.";
    }
}

if (isset($_POST["delete_customer"])) {
    $customer_id = $_POST["customer_id"]; // GET ile gelen restoran id alındı

    $pdo = dbConnect(); // PDO bağlantısı

    $conditions = ['id' => $customer_id];

    if (update('users', ['deleted_at' => 'deleted'], $conditions)) {
        echo "Record deleted successfully";
        header("Location: admin_paneli.php");
        exit();
    } else {
        echo "Error: Could not delete record.";
    }
}


if (isset($_POST["add_comment"])) {
    $restaurant_id = $_POST["restoran"];
    $user_id = $_SESSION["user_id"];
    $surname = $_POST["surname"];
    $title = $_POST["title"];
    $description = $_POST["description"];
    $score = $_POST["score"];
    $urun = isset($_POST["food"]) ? $_POST["food"] : null;

    $pdo = dbConnect(); // PDO bağlantısı
    $comment_data = [
        'user_id' => $user_id,
        'restaurant_id' => $restaurant_id,
        'surname' => $surname,
        'title' => $title,
        'description' => $description,
        'score' => $score
    ];

    if (insert('comments', $comment_data)) {
        echo "New record created successfully";
    } else {
        echo "Error: Could not insert record.";
    }

    if ($urun != null) {
        header("Location: urun_detay.php?urun=$urun&restoran=$restaurant_id");
    } else {
        header("Location: restoran_detay.php?restoran=$restaurant_id");
    }
    exit();
}


if (isset($_POST["delete_comment"])) {
    $comment_id = $_POST["comment_id"];
    $restaurant_id = $_POST["restoran_id"];
    $urun = isset($_POST["food"]) ? $_POST["food"] : null;

    $pdo = dbConnect(); // PDO bağlantısı

    $conditions = ['id' => $comment_id];
    if (delete('comments', $conditions)) {
        echo "Record deleted successfully";
    } else {
        echo "Error: Could not delete record.";
    }

    if ($urun != null) {
        header("Location: urun_detay.php?urun=$urun&restoran=$restaurant_id");
    } else {
        header("Location: restoran_detay.php?restoran=$restaurant_id");
    }
    exit();
}


if (isset($_POST["update_user"])) {
    $user_id = $_POST["user_id"];
    $user_type = $_POST["user_type"];
    $name = $_POST["name"];
    $surname = isset($_POST["surname"]) ? $_POST["surname"] : null;
    $description = isset($_POST["description"]) ? $_POST["description"] : null;
    $balance = isset($_POST["balance"]) ? $_POST["balance"] : null;
    $password = $_POST["password"];

    $pdo = dbConnect(); // PDO bağlantısı

    $conditions = ['id' => $user_id];
    $userData = [
        'name' => $name,
        'surname' => $surname,
        'balance' => $balance,
        'password' => $password,
    ];

    if (update('users', $userData, $conditions)) {
        if ($user_type == "firma") {
            $company_id = select('users', $conditions)['company_id'];
            $companyData = [
                'name' => $name,
                'description' => $description,
            ];
            $conditions = ['id' => $company_id];
            $sonuc = update('company', $companyData, $conditions);

            echo "<br>$company_id<br>";
            echo "<br>$name<br>";
            echo "<br>$description<br>";
            echo "<br>$sonuc<br>";
        }
        echo "Record updated successfully";
    } else {
        echo "Error: Could not update record.";
    }

    header("Location: profile.php");
    exit();
}


if (isset($_POST['add_basket'])) {
    $user_id = $_SESSION["user_id"];
    $food_id = $_POST['urun_id'];
    $quantity = $_POST['adet'];
    $snote = $_POST['snot'];

    $pdo = dbConnect(); // PDO bağlantısı

    $basket_data = [
        'user_id' => $user_id,
        'food_id' => $food_id,
        'quantity' => $quantity,
        'note' => $snote
    ];

    if (insert('basket', $basket_data)) {
        echo "New record created successfully";
    } else {
        echo "Error: Could not insert record.";
    }

    header("Location: urun_detay.php?urun=$food_id&restoran=" . $_POST['restoran_id']);
    exit();
}

if (isset($_POST['sepetten_cikar'])) {
    $item_id = $_POST['item_id'];
    $urun_id = $_POST['urun_id'];

    $pdo = dbConnect(); // PDO bağlantısı

    $conditions = ['id' => $item_id, 'food_id' => $urun_id];
    if (delete('basket', $conditions)) {
        echo "Record deleted successfully";
    } else {
        echo "Error: Could not delete record.";
    }

    header("Location: sepetim.php");
    exit();
}

if (isset($_POST['edit_basket'])) {
    $item_id = $_POST['item_id'];
    $urun_id = $_POST['urun_id'];

    $pdo = dbConnect(); // PDO bağlantısı

    $conditions = ['id' => $item_id, 'food_id' => $urun_id];
    $data = [
        'quantity' => $_POST['quantity'],
        'note' => $_POST['snote']
    ];
    if (update('basket', $data, $conditions)) {
        echo "Record updated successfully";
    } else {
        echo "Error: Could not update record.";
    }

    print_r($data);

    header("Location: sepetim.php");
    exit();
}

if (isset($_POST['clear_basket'])) {
    $user_id = $_SESSION["user_id"];

    $pdo = dbConnect(); // PDO bağlantısı

    $conditions = ['user_id' => $user_id];
    if (delete('basket', $conditions)) {
        echo "Record deleted successfully";
    } else {
        echo "Error: Could not delete record.";
    }

    header("Location: sepetim.php");
    exit();
}

if (isset($_POST['alisveris'])) {
    $user_id = $_SESSION["user_id"];
    $pdo = dbConnect(); // PDO bağlantısı

    $conditions = ['user_id' => $user_id];
    $basket = getAll('basket', $conditions);

    foreach ($basket as $item) {
        $food_id = $item['food_id'];
        $quantity = $item['quantity'];
        $note = $item['note'];

        $food = select('food', ['id' => $food_id]);
        $price = $food['price'];
        $total_price += $_POST['total'];
    }

    $order_data[] = [
        'user_id' => $user_id,
        'total_price' => $total_price,
        'order_status' => "bekliyor"
    ];

    foreach ($order_data as $order) {
        if (insert('order', $order)) {
            $lastOrder = getLastInsertedRow('order', 'id');
            $order_id = $lastOrder['id'];

            foreach ($basket as $item) {
                $food_id = $item['food_id'];
                $quantity = $item['quantity'];
                $note = $item['note'];

                $food = select('food', ['id' => $food_id]);
                $price = $food['price'];

                $order_item_data = [
                    'order_id' => $order_id,
                    'food_id' => $food_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'note' => $note
                ];

                if (insert('order_items', $order_item_data)) {
                    echo "New record created successfully";
                } else {
                    echo "Error: Could not insert record.";
                }
            }
        } else {
            echo "Error: Could not insert record.";
        }
    }
    $conditions = ['user_id' => $user_id];
    if (delete('basket', $conditions)) {
        echo "Record deleted successfully";
    } else {
        echo "Error: Could not delete record.";
    }

    header("Location: sepetim.php");
    exit();
}



