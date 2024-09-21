<?php

// Veritabanı bağlantı fonksiyonu
function dbConnect() {

    $host = "localhost";
    $username = "root"; 
    $password = "secret"; 
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
    
    $columns = implode(", ", array_keys($data));
    $values = implode(", ", array_fill(0, count($data), '?'));

    $sql = "INSERT INTO $table ($columns) VALUES ($values)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(array_values($data));
}

// Read - Veri Okuma Fonksiyonu
function getAll($table, $conditions = []) {
    $pdo = dbConnect();
    
    $sql = "SELECT * FROM $table";
    
    if (!empty($conditions)) {
        $conditionKeys = array_keys($conditions);
        $whereClause = implode(" AND ", array_map(function ($key) {
            return "$key = ?";
        }, $conditionKeys));
        $sql .= " WHERE $whereClause";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($conditions));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getLastInsertedRow($table, $idColumn = 'id') {
    $pdo = dbConnect();
    
    // En son eklenen satırı almak için sorgu
    $sql = "SELECT * FROM $table ORDER BY $idColumn DESC LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC); // Tek bir satırı döndürür
}

function select($table, $conditions) {
    $pdo = dbConnect();
    
    // Koşulları oluşturma
    $where = [];
    foreach ($conditions as $column => $value) {
        $where[] = "$column = ?";
    }
    $whereClause = implode(" AND ", $where);
    
    // Sorguyu hazırlama
    $sql = "SELECT * FROM $table WHERE $whereClause";
    $stmt = $pdo->prepare($sql);
    
    // Koşulların değerlerini ekleyerek sorguyu çalıştırma
    $stmt->execute(array_values($conditions));
    
    // Sonuçları döndürme
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// Update - Veri Güncelleme Fonksiyonu
function update($table, $data, $conditions) {
    $pdo = dbConnect();
    
    $setClause = implode(", ", array_map(function ($key) {
        return "$key = ?";
    }, array_keys($data)));
    
    $whereClause = implode(" AND ", array_map(function ($key) {
        return "$key = ?";
    }, array_keys($conditions)));
    
    $sql = "UPDATE $table SET $setClause WHERE $whereClause";
    $stmt = $pdo->prepare($sql);
    
    return $stmt->execute(array_merge(array_values($data), array_values($conditions)));
}

// Delete - Veri Silme Fonksiyonu
function delete($table, $conditions) {
    $pdo = dbConnect();
    
    $whereClause = implode(" AND ", array_map(function ($key) {
        return "$key = ?";
    }, array_keys($conditions)));
    
    $sql = "DELETE FROM $table WHERE $whereClause";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(array_values($conditions));
}

// Kullanım Örnekleri
// insert('users', ['name' => 'Ali', 'email' => 'ali@example.com']);
// $users = getAll('users', ['name' => 'Ali']);
// update('users', ['email' => 'ali123@example.com'], ['name' => 'Ali']);
// delete('users', ['name' => 'Ali']);

?>
