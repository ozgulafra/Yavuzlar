<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != '1') {
    header("Location: index.php");
    exit();
}

include 'database.php';
  
 if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $question = $_POST['question'];
  $answerA = $_POST['answer1'];
  $answerB = $_POST['answer2'];
  $answerC = $_POST['answer3'];
  $answerD = $_POST['answer4'];
  $trueanswer = $_POST['correct'];
  $diff = $_POST['diff'];

  $update = $conn->prepare('INSERT INTO questions (question, choosen1,choosen2,choosen3,choosen4, answer, difficulty) 
        VALUES(:question, :choosen1, :choosen2, :choosen3, :choosen4, :answer, :difficulty)');

$update->bindParam(':question', $question, PDO::PARAM_STR);
$update->bindParam(':choosen1', $answerA, PDO::PARAM_STR);
$update->bindParam(':choosen2', $answerB, PDO::PARAM_STR);
$update->bindParam(':choosen3', $answerC, PDO::PARAM_STR);
$update->bindParam(':choosen4', $answerD, PDO::PARAM_STR);
$update->bindParam(':answer', $trueanswer, PDO::PARAM_INT); // Doğru cevabın indeksi (1-4)
$update->bindParam(':difficulty', $diff, PDO::PARAM_INT); // Zorluk derecesi

$update->execute();

header("Location: questions.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Soru Ekle</title>

  <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="navbarContainer">
      <h2>Soru Ekle</h2>
      <form action="" method="POST">
      <div class="formBox">
        <label for="question">Soru:</label>
        <input type="text" id="question" name="question" placeholder="Soru" required>
      </div>
      <div class="formBox">
        <label for="answer1">Şık 1:</label>
        <input type="text" id="answer1" name="answer1" placeholder="Şık A" required>
      </div>
      <div class="formBox">
        <label for="answer2">Şık 2:</label>
        <input type="text" id="answer2" name="answer2" placeholder="Şık B" required>
      </div>
      <div class="formBox">
        <label for="answer3">Şık 3:</label>
        <input type="text" id="answer3" name="answer3" placeholder="Şık C" required>
      </div>
      <div class="formBox">
        <label for="answer4">Şık 4:</label>
        <input type="text" id="answer4" name="answer4" placeholder="Şık D" required>
      </div>
      <div class="formBox" >
        <label for="correct">Doğru Şık:</label>
        <select name="correct" id="correct" required>
          <option value="" selected disabled>Şık Seçiniz</option>
          <option value="1">A</option>
          <option value="2">B</option>
          <option value="3">C</option>
          <option value="4">D</option>
        </select>
      </div>
      <div class="formBox" >
        <label for="diff">Zorluk:</label>
        <select name="diff" id="diff" required>
          <option value="" selected disabled>Zorluk Seçiniz</option>
          <option value=1>Kolay</option>
          <option value=2>Orta</option>
          <option value=3>Zor</option>
        </select>
      </div>
      <div class="formBox navbar">
        <button style="width: 200px; height: 50px; " name="addQuestion" type="submit">Soru Ekle</button>
        <button style="width: 200px; height: 50px; " id="homePageButton" onclick="goToHomePage()">Anasayfa</button>
      </div>
    </form>
      
    </div>

  <script src="script.js"></script>
</body>

</html>