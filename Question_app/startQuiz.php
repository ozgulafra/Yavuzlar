<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

include 'database.php';

$userID = $_SESSION['id'];

// Kullanıcı bilgilerini çek
$stmt = $conn->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([$userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


// Kullanıcının daha önce çözdüğü soruları almak için submissions tablosunu kontrol et
$solvedQuestions = [];
$submissions = $conn->query('SELECT question_id FROM submissions WHERE user_id = ' . $userID);
foreach ($submissions as $submission) {
    $solvedQuestions[] = $submission['question_id'];
}

// Kullanıcıdan daha önce çözmediği soruları çek
$solvedQuestionsPlaceholder = implode(',', array_fill(0, count($solvedQuestions), '?'));
if (empty($solvedQuestions)) {
    $stmt = $conn->query('SELECT * FROM questions ORDER BY RANDOM() LIMIT 1');
    $stmt->execute();
} else {
    $stmt = $conn->prepare("SELECT * FROM questions WHERE id NOT IN ($solvedQuestionsPlaceholder) ORDER BY RANDOM() LIMIT 1");
    $stmt->execute($solvedQuestions);
    
}
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$question = !empty($result) || !is_null($result) ? $result[0] : null;


if ($question === null) {
    echo "<script>
            alert('Çözülecek Soru Kalmadı.');
            window.location.href = 'index.php';
          </script>";
    exit();
}

// Kullanıcının puanını ve sorunun cevabını kontrol et
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedButton'])) {
    $selectedButton = $_POST['selectedButton'];
    $questionID = $_POST['questionID']; // Formdan gelen soru ID'si
    // Soru ID'sini kullanarak doğru cevabı sorgula
    $stmt = $conn->prepare("SELECT answer, difficulty FROM questions WHERE id = ?");
    $stmt->execute([$questionID]);
    $questionData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($questionData) {
        $trueAnswer = $questionData['answer']; // Doğru cevap indeksi

        // Cevap kontrolü
        if ($selectedButton == $trueAnswer) {
            // Doğru cevap durumunda kullanıcının puanını güncelle
            $newPoint = isset($_POST['currentPoint']) ? $_POST['currentPoint'] : 0; // Mevcut puan
            $newPoint += $questionData['difficulty']; // Zorluk seviyesini puana ekle
            $update = $conn->prepare('UPDATE users SET point = :point WHERE id = :id');
            $update->bindParam(':point', $newPoint, PDO::PARAM_INT);
            $update->bindParam(':id', $userID, PDO::PARAM_INT);
            $update->execute();

            // Doğru cevabı submissions tablosuna ekle
            $insert = $conn->prepare('INSERT INTO submissions (user_id, question_id, status) VALUES (:user_id, :question_id, :status)');
            $insert->bindParam(':user_id', $userID, PDO::PARAM_INT);
            $insert->bindParam(':question_id', $questionID, PDO::PARAM_INT);
            $status = 1; // Doğru cevap
            $insert->bindParam(':status', $status, PDO::PARAM_INT);
            $insert->execute();

            echo "<script>
                alert('Doğru Cevap.');
                window.location.href = 'startQuiz.php';
              </script>";
        } else {
            echo "<script>
                alert('Yanlış Cevap.');
                window.location.href = 'startQuiz.php';
              </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sınav</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="navbarContainer">
        <h1>Quiz App</h1>
        <div><?php echo 'Puan: ' . (isset($user['point']) ? $user['point'] : 0); ?></div>
        <br>
        <div class="questionText" id="questionText"><?php echo $question['question']; ?></div>
        <div class="difficultyText" id="difficultyText"><?php
            if ($question['difficulty'] == 1) {
                echo 'Kolay';
            } else if ($question['difficulty'] == 2) {
                echo 'Orta';
            } else {
                echo 'Zor';
            } ?>
        </div>
        <form method="POST" action="">
            <div id="answerButton" class="formBox navbar">
                <select name="selectedButton" class="select" style="text-align: center;">
                    <option value="1"><?php echo $question['choosen1']; ?></option>
                    <option value="2"><?php echo $question['choosen2']; ?></option>
                    <option value="3"><?php echo $question['choosen3']; ?></option>
                    <option value="4"><?php echo $question['choosen4']; ?></option>
                </select>
            </div>
            <input type="hidden" name="currentPoint" value="<?php echo isset($user['point']) ? $user['point'] : 0; ?>">
            <input type="hidden" name="questionID" value="<?php echo $question['id']; ?>"> <!-- Soru ID'sini gizli alanla gönder -->
            <button class="submitButton btn" type="submit">Onayla</button>
        </form>
        <button style="width: 200px; height: 50px;" id="homePageButton" onclick="goToHomePage()">Anasayfa</button>
    </div>
    <script src="script.js"></script>
</body>
</html>
