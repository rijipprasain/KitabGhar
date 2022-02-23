<?php
include 'includes/session.php';
include 'recommendation/user_recommendation.php';
include 'barcode/autoload.php';
$Bar = new Picqer\Barcode\BarcodeGeneratorHTML();
?>
<?php
	$conn = $pdo->open();

	$slug = $_GET['product'];

	try{
		 		
	    $stmt = $conn->prepare("SELECT *, books.title AS prodname, category.name AS catname, books.id AS prodid FROM books LEFT JOIN category ON category.id=books.category_id WHERE slug = :slug");
	    $stmt->execute(['slug' => $slug]);
	    $product = $stmt->fetch();
        if(isset($_SESSION['user'])){
            $stmtrate = $conn->prepare("SELECT * FROM ratings WHERE isbn = :isbn AND user_id= :uid");
            $stmtrate->execute(['isbn' => $product['isbn'], 'uid'=>$user['id']]);
            $rating = $stmtrate->fetch();
        }
		
	}
	catch(PDOException $e){
		echo "There is some problem in connection: " . $e->getMessage();
	}

	//page view
	$now = date('Y-m-d');
	if($product['date_view'] == $now){
		$stmt = $conn->prepare("UPDATE books SET counter=counter+1 WHERE id=:id");
		$stmt->execute(['id'=>$product['prodid']]);
	}
	else{
		$stmt = $conn->prepare("UPDATE books SET counter=1, date_view=:now WHERE id=:id");
		$stmt->execute(['id'=>$product['prodid'], 'now'=>$now]);
	}

?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<script>
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.12';
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">
	        <div class="row">
	        	<div class="col-sm-9">
	        		<div class="callout" id="callout" style="display:none">
	        			<button type="button" class="close"><span aria-hidden="true">&times;</span></button>
	        			<span class="message"></span>
	        		</div>
		            <div class="row">
		            	<div class="col-sm-6">
		            		<img src="<?php echo (!empty($product['photo'])) ? $product['photo'] : 'images/noimage.jpg'; ?>" width="100%" class="zoom" data-magnify-src="images/large-<?php echo $product['photo']; ?>">
		            		<br><br>
		            		<form class="form-inline" id="productForm">
		            			<div class="form-group">
			            			<div class="input-group col-sm-5">
			            				
			            				<span class="input-group-btn">
			            					<button type="button" id="minus" class="btn btn-default btn-flat btn-lg"><i class="fa fa-minus"></i></button>
			            				</span>
							          	<input type="text" name="quantity" id="quantity" class="form-control input-lg" value="1">
							            <span class="input-group-btn">
							                <button type="button" id="add" class="btn btn-default btn-flat btn-lg"><i class="fa fa-plus"></i>
							                </button>
							            </span>
							            <input type="hidden" value="<?php echo $product['prodid']; ?>" name="id">
							        </div>
			            			<button type="submit" class="btn btn-primary btn-lg btn-flat"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
			            		</div>
		            		</form>
		            	</div>
		            	<div class="col-sm-6">
		            		<h1 class="page-header"><?php echo $product['prodname']; ?></h1>
                            <h3><b>ISBN:</b> <p><?php echo $product['isbn']; ?></p><p><?php
                                    $code = $Bar->getBarcode($product['isbn'], $Bar::TYPE_CODE_128);
                                    echo $code;
                                    ?></p></h3>
		            		<h3><b>&#36; <?php echo number_format($product['price'], 2); ?></b></h3>
		            		<p><b>Category:</b> <a href="category.php?category=<?php echo $product['cat_slug']; ?>"><?php echo $product['catname']; ?></a></p>
		            		<p><b>Description:</b></p>
		            		<p><?php echo $product['overview']; ?></p>
                            <p><b>Rating: <?php
                                    if(isset($rating['book_rating'])&&isset($_SESSION['user'])){
                                        echo $rating['book_rating'];
                                        }else{
                                        echo ' -- ';
                                    } ?></b></p>
		            	</div>
		            </div>
		            <br>
				    <div class="fb-comments" data-href="http://localhost/ecommerce/product.php?product=<?php echo $slug; ?>" data-numposts="10" width="100%"></div>
                    <form id="ratingForm" method="post">
                        <div class="form-group">
                            <label for="rating" class="col-sm-1 control-label">Rating</label>

                            <div class="col-sm-5">
                                <select class="form-control" id="rating" name="rating">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>

                            <input type="hidden" value="<?php
                            if(isset($rating['book_rating'])){
                                echo $rating['book_rating'];
                            }
                            ?>" name="old_rating">
                            <input type="hidden" value="<?php echo $product['isbn']; ?>" name="id">
                            <input type="hidden" value="<?php echo $slug; ?>" name="slug">
                            <button type="submit" class="btn btn-primary btn-md  btn-flat">Submit</button>
                            <span class="refresher"></span>
                        </div>
                    </form>
                    <div>
                        <?php
					
                        $limit=0;
                        if (isset($rating['book_rating'])&&isset($recval)){
                            ?>
                        
                        <?php
                        foreach ($recval as $key=>$value){
                            $stmtbook = $conn->prepare("SELECT * FROM books WHERE isbn=:isbn");
                            $stmtbook->execute(['isbn' => $key]);
                            $book = $stmtbook->fetch();
                            if(isset($book)&& !empty($book)){
                        ?>
                            
							<?php 
									echo "
	       							<div class='col-sm-4'>
	       								<div class='box box-solid'>
		       								<div class='box-body prod-body'>
		       									<img src='".$book['photo']."' width='100%' height='230px' class='thumbnail'>
		       									<h5><a href='product.php?product=".$book['slug']."'>".$book['title']."</a></h5>
		       									
		       								</div>
		       								<div class='box-footer'>
		       									<b>&#36; ".number_format($book['price'], 2)."</b>
		       								</div>
	       								</div>
	       							</div>
	       						";
								   ?>
							
                            <?php
                            }
                            if($limit==10)
                                break;
                            $limit++;
                            }
                        }
                        ?>
                          
                    </div>
                </div>
	        	<div class="col-sm-3">
	        		<?php include 'includes/sidebar.php'; ?>
	        	</div>
	        </div>
			
	      </section>
	     
	    </div>
	  </div>
  	<?php $pdo->close(); ?>
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
	$('#add').click(function(e){
		e.preventDefault();
		var quantity = $('#quantity').val();
		quantity++;
		$('#quantity').val(quantity);
	});
	$('#minus').click(function(e){
		e.preventDefault();
		var quantity = $('#quantity').val();
		if(quantity > 1){
			quantity--;
		}
		$('#quantity').val(quantity);
	});

});
</script>
</body>
</html>

<!--<h5>Distance: ".($value/5)."</h5>-->