<?php include 'includes/session.php'; ?>
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
                        <?php
                        if (isset($_SESSION['error'])) {
                            echo "
	        					<div class='alert alert-danger'>
	        						" . $_SESSION['error'] . "
	        					</div>
	        				";
                            unset($_SESSION['error']);
                        }
                        ?>
                        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                                <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                                <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
                            </ol>
                            <div class="carousel-inner">
                                <div class="item active">
                                    <img src="images/banner1.jpg" alt="First slide">
                                </div>
                                <div class="item">
                                    <img src="images/banner2.jpg" alt="Second slide">
                                </div>
                                <div class="item">
                                    <img src="images/banner3.jpg" alt="Third slide">
                                </div>
                            </div>
                            <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
                                <span class="fa fa-angle-left"></span>
                            </a>
                            <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
                                <span class="fa fa-angle-right"></span>
                            </a>
                        </div>
                        <h2>Monthly Top Sellers</h2>
                        <?php
                        $month = date('m');
                        $conn = $pdo->open();

                        try {
                            $inc = 3;
                            $stmt = $conn->prepare("SELECT *, SUM(quantity) AS total_qty FROM details LEFT JOIN sales ON sales.id=details.sales_id LEFT JOIN books ON books.id=details.product_id WHERE MONTH(sales_date) = '$month' GROUP BY details.product_id ORDER BY total_qty DESC LIMIT 6");
                            $stmt->execute();
                            foreach ($stmt as $row) {
                                $image = (!empty($row['photo'])) ? $row['photo'] : 'images/noimage.jpg';
                                $inc = ($inc == 3) ? 1 : $inc + 1;
                                if ($inc == 1) echo "<div class='row'>";
                                echo "
	       							<div class='col-sm-4'>
	       								<div class='box box-solid'>
		       								<div class='box-body prod-body'>
		       									<img src='" . $image . "' width='100%' height='230px' class='thumbnail'>
		       									<h5><a href='product.php?product=" . $row['slug'] . "'>" . $row['title'] . "</a></h5>
		       								</div>
		       								<div class='box-footer'>
		       									<b>&#36; " . number_format($row['price'], 2) . "</b>
		       								</div>
	       								</div>
	       							</div>
	       						";
                                if ($inc == 3) echo "</div>";
                            }
                            if ($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>";
                            if ($inc == 2) echo "<div class='col-sm-4'></div></div>";
                        } catch (PDOException $e) {
                            echo "There is some problem in connection: " . $e->getMessage();
                        }

                        $pdo->close();

                        ?>
                    </div>
                    <div class="col-sm-3">
                        <?php include 'includes/sidebar.php'; ?>
                    </div>

                    <div id="about">
                        <h2>About Us</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam at cum, earum esse fugiat illo, in ipsa maiores minima nisi odio officia provident repellat velit veniam veritatis, voluptatibus. Incidunt, iusto!
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Architecto cum ducimus iste iusto laudantium magni perspiciatis sunt veniam vero voluptate? Aliquid architecto assumenda atque, dolorem magni quasi ratione sunt totam!
                        </p>
                    </div>

                    <div id="contact">
                        <h2>Contact Me</h2>


                        <div>
                            <p> Chicago, US</p>
                            <p>Phone: +00 151515</p>
                            <p> Email: mail@mail.com</p>
                        </div>
                        <br>
                        <p>Let's get in touch. Send me a message:</p>

                        <form>
                            <p><input type="text" placeholder="Name" required name="Name"></p>
                            <p><input type="text" placeholder="Email" required name="Email"></p>
                            <p><input type="text" placeholder="Subject" required name="Subject"></p>
                            <p><input type="text" placeholder="Message" required name="Message"></p>
                            <p>
                                <button class="w3-button w3-light-grey w3-padding-large" type="submit">
                                    <i class="fa fa-paper-plane"></i> SEND MESSAGE
                                </button>
                            </p>
                        </form>
                        <!-- End Contact Section -->
                    </div>
                </div>
            </section>

        </div>
    </div>
    <div class="row">


    </div>

    <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>