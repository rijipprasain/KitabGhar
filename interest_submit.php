<?php
include 'includes/session.php';

$conn = $pdo->open();

$output = array('error' => false);

$category_id = $_POST['interest'];

if (isset($_SESSION['user'])){
    $stmtCurrentInterest=$conn->prepare("SELECT * from interest join category on interest.category_id=category.id having user_id=:uid");
    $stmtCurrentInterest->execute(['uid'=>$user['id']]);
    $allinterestids = array();
    if(isset($stmtCurrentInterest)){
        foreach ($stmtCurrentInterest as $interest){
            $allinterestids[]=$interest['category_id'];
        }
    }
}

if(isset($_SESSION['user'])){
    foreach ($category_id as $cat_id){
        if (!in_array($cat_id,$allinterestids)){
            $stmtInterestInsert=$conn->prepare("INSERT into interest values (NULL ,:user_id,:cat_id)");
            $stmtInterestInsert->execute(['user_id'=>$user['id'],'cat_id'=>$cat_id]);
        }
    }
    $output['refresher'] = "<meta http-equiv='refresh' content='0'>";
}
else{
    $output['error'] = true;
    $output['message'] = "You need to sign in to rate";
}
echo json_encode($output);