<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
        $isbn = $_POST['isbn'];
        $title = $_POST['title'];
        $slug = slugify($title);
        $category = $_POST['category'];
        $price = $_POST['price'];
        $overview = $_POST['overview'];
        $publisher = $_POST['publisher'];
        $author = $_POST['author'];
        $publication_year = $_POST['publication_year'];

		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("UPDATE books SET title=:title, isbn=:isbn, publisher=:publisher, publication_year=:publication_year, author=:author, slug=:slug, category_id=:category, price=:price, overview=:overview WHERE id=:id");
			$stmt->execute(['title'=>$title, 'publisher'=>$publisher, 'author'=>$author,'isbn'=>$isbn, 'publication_year'=>$publication_year, 'slug'=>$slug, 'category'=>$category, 'price'=>$price, 'overview'=>$overview, 'id'=>$id]);
			$_SESSION['success'] = 'Product updated successfully';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}
		
		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up edit product form first';
	}

	header('location: products.php');

?>