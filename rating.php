<?php
    include 'includes/session.php';

    $conn = $pdo->open();

    $output = array('error' => false);

    $isbn = $_POST['id'];
    $rating = $_POST['rating'];
    $old_rating = $_POST['old_rating'];
    $slug = $_POST['slug'];
    $location = "http://localhost/ecommerce/product.php?product=$slug";

if(isset($_SESSION['user'])){
    if(isset($old_rating)&& !empty($old_rating)){
        $stmt = $conn->prepare("UPDATE ratings set book_rating= :rating where isbn=:isbn");
        $stmt->execute(['isbn'=>$isbn, 'rating'=>$rating]);
        $output['message'] = 'Rating modified successfully';
        $output['refresher'] = "<meta http-equiv='refresh' content='0'>";
    }else{
        $stmt = $conn->prepare("INSERT INTO ratings (user_id,isbn,book_rating) VALUES  (:user_id,:isbn,:rating)");
        $stmt->execute(['user_id'=>$user['id'], 'isbn'=>$isbn, 'rating'=>$rating]);
        $output['message'] = 'Thank you for rating';
        $output['refresher'] = "<meta http-equiv='refresh' content='0'>";
    }
}
else{
    $output['error'] = true;
    $output['message'] = "You need to sign in to rate";
}
echo json_encode($output);