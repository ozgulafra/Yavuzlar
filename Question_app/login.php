<?php
session_start();

include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $passwd = $_POST['passwd'];

    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $passwd == $user['password']) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
    } else {
        echo "<script>
            alert('Yanlış kullanıcı adı veya Parola');
          </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="navbarContainer">
    <div class="header">
      <h1>Quiz App</h1>
    </div>
        <h2>Giriş Yap</h2>
        <div class="navbar">
        <form method="post" action="login.php">
            <div class="formBox">
                <label for="username">Kullanıcı Adı:</label>
            <input type="text" id="username" name="username" required>
            </div>
            <div class="formBox">
            <label for="passwd">Şifre:</label>
            <input type="password" id="passwd" name="passwd" required>
            </div>
            <div class="formBox navbar">
            <button type="submit" class="button">Giriş Yap</button>
            
            </div>
        </form>
        </div>
       <button class="button" id="goToLoginPageButton" onclick="goToRegisterPage()" style="height: 50px;">Kayıt Ol</button>
    </div>
    <script src="script.js"></script>
</body>
</html>
