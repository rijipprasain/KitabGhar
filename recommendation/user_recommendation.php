<?php
include 'recommend.php';
$conn = $pdo->open();
$stmtrating = $conn->prepare("SELECT * FROM ratings");
$stmtrating->execute();
$matrix = array();
$flag = false;
if (isset($_SESSION['user'])) {
    foreach ($stmtrating as $rating) {
        $stmtuser = $conn->prepare("select * from users where id=:user_id");
        $stmtuser->execute(['user_id' => $rating['user_id']]);
        $username = $stmtuser->fetch();

        if ($rating['user_id'] == $user['id'])
            $flag = true;

        $matrix[$username['firstname']][$rating['isbn']] = $rating['book_rating'];
    }
    if ($flag) {
        $recval = getRecommendation($matrix, $user['firstname']);
    }

}
