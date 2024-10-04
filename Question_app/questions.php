<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

// Tüm soruları çek
$result = $conn->query('SELECT * FROM questions');
$result = $result->fetchAll(PDO::FETCH_ASSOC);
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

// Soru silme işlemi
if(isset($_GET['id'])) {
    $id = (int)$_GET['id']; // id'yi güvenlik için integer'a çevir
    $del = $conn->prepare('DELETE FROM questions WHERE id = ?');
    $del->execute([$id]);
    header("Location: questions.php");
}

?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sorular</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>
  <div class="navbarContainer">
    <h1>Quiz App</h1>
    <div class="navbar">
    <a class="button" href="index.php" style="border-radius:7px; text-decoration:none;">Anasayfa</a>
    <?php if ($_SESSION['role'] == '1'): ?>
      <button class="button" id="addQuestionButton" onclick="goToAddQuestion()">Soru Ekle</button>
    <?php endif; ?>
    </div>
  <div class="questionList">
    <table>
      <thead>
        <tr>
        
          <th>Soru</th>
          <th>Zorluk</th>
          <?php if ($_SESSION['role'] == '1'): ?>
          <th>İşlemler</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody id="questionList">
        <?php foreach ($questions as $index => $question): ?>
          <tr>
            
            <td><?php echo $question['question']; ?></td>
            <td>
              <?php
              if ($question['difficulty'] == 1) {
                echo 'Kolay';
              } 
              else if ($question['difficulty'] == 2) {
                echo 'Orta';
              }
              else{
                echo 'Zor';
              }
              ?>
            </td>
            <?php if ($_SESSION['role'] == '1'): ?>
            <td>
                <button class="button" onclick="window.location.href='modifyQuestion.php?id=<?php echo $question['id']; ?>'"><i class="fas fa-pen"></i></button>
                <button class="button" onclick="window.location.href='questions.php?id=<?php echo $question['id']; ?>'"><i class="fas fa-trash"></i></button>
            </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  </div>
  <script src="script.js"></script>
</body>
</html>
