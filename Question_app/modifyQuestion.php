<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != '1') {
    header("Location: index.php");
    exit();
}

$id = '';
if(isset($_GET['id'])) {
  $id = $_GET['id'];
}

include 'database.php';


$result = $conn->query('SELECT * FROM questions WHERE id='.$id);
$questions = [];
foreach ($result as $row) {
  $correctAnswerIndex = $row['answer']; // Doğru cevap indeksi (1, 2, 3 veya 4)
  
  // Soruları diziye ekle
  $questions[] = [
      'id' => $row['id'],
      'question' => $row['question'],
      'difficulty' => $row['difficulty'],
      'answers' => [
          ['text' => $row['choosen1'], 'correct' => ($correctAnswerIndex == 1)],
          ['text' => $row['choosen2'], 'correct' => ($correctAnswerIndex == 2)],
          ['text' => $row['choosen3'], 'correct' => ($correctAnswerIndex == 3)],
          ['text' => $row['choosen4'], 'correct' => ($correctAnswerIndex == 4)],
      ]
  ];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $question = $_POST['question'];
  $answerA = $_POST['answer1'];
  $answerB = $_POST['answer2'];
  $answerC = $_POST['answer3'];
  $answerD = $_POST['answer4'];
  $trueanswer = $_POST['correct'];
  $diff = $_POST['diff'];

  $update = $conn->prepare('UPDATE questions 
  SET question = :question, choosen1 = :choosen1, choosen2 = :choosen2, 
  choosen3 = :choosen3, choosen4 = :choosen4, answer = :answer, difficulty = :difficulty 
  WHERE id = :id');

$update->bindParam(':question', $question, PDO::PARAM_STR);
$update->bindParam(':choosen1', $answerA, PDO::PARAM_STR);
$update->bindParam(':choosen2', $answerB, PDO::PARAM_STR);
$update->bindParam(':choosen3', $answerC, PDO::PARAM_STR);
$update->bindParam(':choosen4', $answerD, PDO::PARAM_STR);
$update->bindParam(':answer', $trueanswer, PDO::PARAM_INT); // Doğru cevabın indeksi (1-4)
$update->bindParam(':difficulty', $diff, PDO::PARAM_INT); // Zorluk derecesi
$update->bindParam(':id', $id, PDO::PARAM_INT);

$update->execute();

header("Location: questions.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Soru Değiştir</title>

  <link rel="stylesheet" href="style.css">

  <style>

  </style>

</head>

<body>
  <div class="navbarContainer">
    <h2 >Soru Düzenle</h2>
    <form action="" method="POST">
      <div class="formBox">
        <input type="text" id="question" name="question" placeholder="Soru" value="<?php echo $questions[0]['question'];?>" required>
      </div>
      <div class="formBox">
        <input type="text" id="answer1" name="answer1" placeholder="Şık A" value="<?php echo $questions[0]['answers'][0]['text'];?>" required>
      </div>
      <div class="formBox">
        <input type="text" id="answer2" name="answer2" placeholder="Şık B" value="<?php echo $questions[0]['answers'][1]['text'];?>" required>
      </div>
      <div class="formBox">
        <input type="text" id="answer3" name="answer3" placeholder="Şık C" value="<?php echo $questions[0]['answers'][2]['text'];?>" required>
      </div>
      <div class="formBox">
        <input type="text" id="answer4" name="answer4" placeholder="Şık D" value="<?php echo $questions[0]['answers'][3]['text'];?>" required>
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
        </select><br><br>
      </div>
      <div class="formBox">
        <button style="width: 200px; height: 50px; " name="modifyQuestion" type="submit">Soru Düzenle</button>
      </div>
    </form>
    <div class="navbar">
      <a style=" border-radius:7px; text-decoration:none;" href="index.php" class="button">Anasayfa</a>
    </div>
    
  </div>

<script src="script.js"></script>
</body>

</html>