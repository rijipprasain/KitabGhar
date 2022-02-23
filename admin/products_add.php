<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	if(isset($_POST['add'])){
	    $isbn = $_POST['isbn'];
		$title = $_POST['title'];
		$slug = slugify($title);
		$category = $_POST['category'];
		$price = $_POST['price'];
		$overview = $_POST['overview'];
		$publisher = $_POST['publisher'];
		$author = $_POST['author'];
		$publication_year = $_POST['publication_year'];
		$filename = $_FILES['photo']['name'];

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM books WHERE slug=:slug");
		$stmt->execute(['slug'=>$slug]);
		$row = $stmt->fetch();

		if($row['numrows'] > 0){
			$_SESSION['error'] = 'Product already exist';
		}
		else{
			if(!empty($filename)){
				$ext = pathinfo($filename, PATHINFO_EXTENSION);
				$new_filename = $slug.'.'.$ext;
				move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$new_filename);	
			}
			else{
				$new_filename = '';
			}

			try{
				$stmt = $conn->prepare("INSERT INTO books (category_id, isbn, title, author, publisher, publication_year, overview, slug, price, photo) VALUES (:category, :isbn,:title, :author, :publisher, :publication_year, :overview, :slug, :price, :photo)");
				$stmt->execute(['category'=>$category, 'title'=>$title, 'isbn'=>$isbn, 'publisher'=>$publisher,'author'=>$author, 'publication_year'=>$publication_year,'overview'=>$overview, 'slug'=>$slug, 'price'=>$price, 'photo'=>$new_filename]);
				$_SESSION['success'] = 'Product added successfully';

			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up product form first';
	}

	header('location: products.php');

?>