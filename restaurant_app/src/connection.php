<?php

// Veritabanı bağlantı fonksiyonu
function dbConnect() {

    $host = "db";
    $username = "user"; 
    $password = "user_password"; 
    $dbname = "restaurant_app";

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Bağlantı hatası: " . $e->getMessage());
    }
}

// Create - Veri Ekleme Fonksiyonu
function insert($table, $data) {
    $pdo = dbConnect();
    
    // Kolon adlarını backtick ile sar
    $columns = implode(", ", array_map(function($column) {
        return '`' . $column . '`';
    }, array_keys($data)));
    
    // Parametre yer tutucularını oluştur
    $placeholders = implode(", ", array_fill(0, count($data), '?'));

    // SQL sorgusu, tablo adı da backtick ile sarıldı
    $sql = 'INSERT INTO `'.$table.'` ('.$columns.') VALUES ('.$placeholders.')';
    
    $stmt = $pdo->prepare($sql);
    
    // Sorguyu çalıştır
    return $stmt->execute(array_values($data));
}



// Read - Veri Okuma Fonksiyonu
function getAll($table, $conditions = []) {
    $pdo = dbConnect();
    
    // Tablo adını backtick ile sar
    $sql = "SELECT * FROM `{$table}`";
    
    if (!empty($conditions)) {
        // Sütun adlarını backtick ile sar
        $conditionKeys = array_keys($conditions);
        $whereClause = implode(" AND ", array_map(function ($key) {
            return "`$key` = ?";
        }, $conditionKeys));
        $sql .= " WHERE $whereClause";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($conditions));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getLastInsertedRow($table, $idColumn = 'id') {
    $pdo = dbConnect();
    
    // En son eklenen satırı almak için sorgu, tablo ve sütun adlarını backtick ile sar
    $sql = "SELECT * FROM `{$table}` ORDER BY `{$idColumn}` DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC); // Tek bir satırı döndürür
}


function select($table, $conditions) {
    $pdo = dbConnect();
    
    // Koşulları oluşturma ve sütun adlarını backtick ile sarma
    $where = [];
    foreach ($conditions as $column => $value) {
        $where[] = "`$column` = ?";
    }
    $whereClause = implode(" AND ", $where);
    
    // Sorguyu hazırlama, tablo adını backtick ile sarma
    $sql = "SELECT * FROM `{$table}` WHERE $whereClause";
    $stmt = $pdo->prepare($sql);
    
    // Koşulların değerlerini ekleyerek sorguyu çalıştırma
    $stmt->execute(array_values($conditions));
    
    // Sonuçları döndürme
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


function selectFA($table, $conditions) {
    $pdo = dbConnect();
    
    // Koşulları oluşturma ve sütun adlarını backtick ile sarma
    $where = [];
    foreach ($conditions as $column => $value) {
        $where[] = "`$column` = ?";
    }
    $whereClause = implode(" AND ", $where);
    
    // Sorguyu hazırlama, tablo adını backtick ile sarma
    $sql = "SELECT * FROM `{$table}` WHERE $whereClause";
    $stmt = $pdo->prepare($sql);
    
    // Koşulların değerlerini ekleyerek sorguyu çalıştırma
    $stmt->execute(array_values($conditions));
    
    // Sonuçları döndürme
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



// Update - Veri Güncelleme Fonksiyonu
function update($table, $data, $conditions) {
    $pdo = dbConnect();
    
    // Kolon adlarını backtick ile sarma
    $setClause = implode(", ", array_map(function ($key) {
        return "`$key` = ?";
    }, array_keys($data)));
    
    // Şartlar için sütun adlarını backtick ile sarma
    $whereClause = implode(" AND ", array_map(function ($key) {
        return "`$key` = ?";
    }, array_keys($conditions)));
    
    // SQL sorgusu, tablo adı backtick ile sarıldı
    $sql = "UPDATE `{$table}` SET $setClause WHERE $whereClause";
    $stmt = $pdo->prepare($sql);
    
    // Sorguyu çalıştırma
    return $stmt->execute(array_merge(array_values($data), array_values($conditions)));
}


// Delete - Veri Silme Fonksiyonu
function delete($table, $conditions) {
    $pdo = dbConnect();
    
    // Şartlar için sütun adlarını backtick ile sarma
    $whereClause = implode(" AND ", array_map(function ($key) {
        return "`$key` = ?";
    }, array_keys($conditions)));
    
    // SQL sorgusu, tablo adı backtick ile sarıldı
    $sql = "DELETE FROM `{$table}` WHERE $whereClause";
    $stmt = $pdo->prepare($sql);
    
    // Sorguyu çalıştırma
    return $stmt->execute(array_values($conditions));
}


function usercontrol($id, $yetkililer) {

    if ($id != null) {
       $pdo = dbConnect();
    
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
   

    

    $useryetki ="";

    if ($result['role'] == 1) {
        $useryetki = 'firma';
    } elseif ($result['role'] == 0) {
        $useryetki = 'normal';
    }
    elseif ($result['role'] == 2) {
        $useryetki = 'admin';
    }
    else {
        $useryetki = 'null';
    }
 } else {
     $useryetki = 'null';
 }

    if (!in_array($useryetki, $yetkililer)) {
        header("Location: index.php");
        exit;
    }

}




// Kullanım Örnekleri
// insert('users', ['name' => 'Ali', 'email' => 'ali@example.com']);
// $users = getAll('users', ['name' => 'Ali']);
// update('users', ['email' => 'ali123@example.com'], ['name' => 'Ali']);
// delete('users', ['name' => 'Ali']);

?>
