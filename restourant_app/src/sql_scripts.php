<?php

include_once 'connection.php';



function getrestaurantslist()
{
    $conn = dbConnect();
    $sql = "SELECT * FROM restaurants";
    $result = $conn->query($sql);
    return $result;
}

function getrestaurantscuponlist($restaurant_id)
{
    $conn = dbConnect();
    $sql = "SELECT * FROM cupon where restaurant_id = $restaurant_id";
    $result = $conn->query($sql);
    return $result;
}

function getfoodlistfromrestaurant($restaurant_id)
{
    $conn = dbConnect();
    $sql = "SELECT * FROM food where restaurant_id = $restaurant_id AND deleted_at != 'deleted' ";
    $result = $conn->query($sql);
    return $result;
}


function getrestaurantidwithfoodid($food_id)
{
    $conn = dbConnect();
    $sql = "SELECT restaurant_id FROM food where id = $food_id";
    $result = $conn->prepare($sql);
    $result->execute();
    return $result->fetch(PDO::FETCH_ASSOC)["restaurant_id"];
}

function getrestaurantidwithcuponid($cupon_id)
{
    $conn = dbConnect();
    $sql = "SELECT restaurant_id FROM cupon where id = $cupon_id";
    $result = $conn->prepare($sql);
    $result->execute();
    return $result->fetch(PDO::FETCH_ASSOC)["restaurant_id"];
}

function getRestaurantOrders($restaurant_id)
{
    // Veritabanı bağlantısını kur
    $conn = dbConnect();
    
    // SQL sorgusu
    $sql = "
        SELECT 
            o.id AS order_id,
            GROUP_CONCAT(f.name SEPARATOR ', ') AS ordered_foods,
            u.name AS customer_name,
            u.surname AS customer_surname
        FROM `order` o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN food f ON oi.food_id = f.id
        JOIN users u ON o.user_id = u.id
        WHERE f.restaurant_id = '$restaurant_id'
        GROUP BY o.id, u.name, u.surname;
    ";
    
    // SQL sorgusunu hazırlayın
    $stmt = $conn->prepare($sql);
    
    // Sorguyu çalıştır
    $stmt->execute();
    // Sonuçları diziye dönüştür
    $orders = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $orders[] = [
            'order_id' => $row['order_id'],
            'ordered_foods' => $row['ordered_foods'],
            'customer_name' => $row['customer_name'],
            'customer_surname' => $row['customer_surname'],
            'customer_address' => $row['customer_address'],
        ];
    }
    // Sonuçları döndür
    return $orders;
}


function getallfoodswithrestaurant()
{
    $conn = dbConnect();
    $sql = "SELECT f.id, f.name, f.price, f.restaurant_id, r.name as restaurant_name FROM food f JOIN restaurants r ON f.restaurant_id = r.id WHERE f.deleted_at != 'deleted' ORDER BY f.id DESC";
    $result = $conn->query($sql);
    return $result;
}

function getfood($food_id)
{
    $conn = dbConnect();
    $sql = "SELECT * FROM food where id = $food_id";
    $result = $conn->prepare($sql);
    $result->execute();
    return $result->fetch(PDO::FETCH_ASSOC);
}

function getallcompany()
{
    $conn = dbConnect();
    $sql = "SELECT * FROM company WHERE deleted_at != 'deleted'";
    $result = $conn->query($sql);
    return $result;
}

function getRestaurantScore($restaurant_id) {
    // Veritabanı bağlantısı
    $conn = dbConnect();

    // SQL sorgusu: restoranın ortalama skorunu al
    $sql = "SELECT AVG(score) as average_score 
            FROM comments 
            WHERE restaurant_id = '$restaurant_id'";
    
    // Sorguyu hazırlama ve parametreyi bağlama
    $stmt = $conn->prepare($sql);
    
    // Sorguyu çalıştırma
    $stmt->execute();
    

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Ortalama puan
    $average_score = $row['average_score'];
    
    // Eğer hiç puan yoksa 0 döndür
    if (is_null($average_score)) {
        return 0;
    }

    // Skoru 10 üzerinden döndür
    return round($average_score, 1);
}


function getRestaurantComments($restaurant_id) {
    // Veritabanı bağlantısı
    $conn = dbConnect();

    // SQL sorgusu: restoranın yorumlarını al
    $sql = "SELECT * 
            FROM comments 
            WHERE restaurant_id = '$restaurant_id'";
    
    // Sorguyu hazırlama ve parametreyi bağlama
    $stmt = $conn->prepare($sql);
    
    // Sorguyu çalıştırma
    $stmt->execute();
    
    // Sonuçları diziye dönüştür
    $comments = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $comments[] = [
            'title' => $row['title'],
            'description' => $row['description'],
            'score' => $row['score'],
            'created_at' => $row['created_at'],
            'surname' => $row['surname'],
            'user_id' => $row['user_id'],
            'id' => $row['id']

        ];
    }
    
    // Sonuçları döndür
    return $comments;
}

function getUserDetails($id)
{
    $conn = dbConnect();
    $sql = "SELECT u.*, c.description 
FROM users u
LEFT JOIN company c ON u.company_id = c.id
WHERE u.company_id IS NOT NULL OR u.company_id IS NULL AND u.id = $id";
    $result = $conn->prepare($sql);
    $result->execute();
    return $result->fetch(PDO::FETCH_ASSOC);
}
