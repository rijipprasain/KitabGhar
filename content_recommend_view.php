<?php
include 'includes/session.php';
include 'content_recommend.php';
?>
<?php
	$conn = $pdo->open();


?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
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

                    <div>
                        <?php
					
                        $limit=0;
                        if (isset($recommend_result)&&isset($_SESSION['user'])){
                            ?>
                        
                        <?php

                        foreach ($recommend_result as $key=>$value){
                            $stmtbook = $conn->prepare("SELECT * FROM books join category on books.category_id=category.id having isbn=:isbn");
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
		       									<h5>Category: ".$book['name']."</h5>
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
                        }else{
                            echo "<h2>You need to be signed in for customized recommendation OR $error</h2>";
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
</body>
</html>

<!--<h5>Similarity Distance:".$value."</h5>-->