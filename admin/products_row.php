<?php 
	include 'includes/session.php';

	if(isset($_POST['id'])){
		$id = $_POST['id'];
		
		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT *, books.id AS bookid, books.title AS booktitle, books.author as bookauthor, books.publisher as bookpublisher, books.publication_year as bookpubyear, books.isbn as bookisbn, category.name AS catname FROM books LEFT JOIN category ON category.id=books.category_id WHERE books.id=:id");
		$stmt->execute(['id'=>$id]);
		$row = $stmt->fetch();
		
		$pdo->close();

		echo json_encode($row);
	}
?>