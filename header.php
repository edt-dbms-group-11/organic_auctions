<?php
session_start();
if (!isset($_SESSION) || $_SESSION == null) {
	$_SESSION['is_logged_in'] = false;
	$_SESSION['email'] = null;
	$_SESSION['username'] = null;
	$_SESSION['display_name'] = null;
} elseif ($_SESSION['username'] != null) {
	$username = $_SESSION['username'];
	$display_name = $_SESSION['display_name'];
	$email = $_SESSION['email'];
	$is_logged_in = true;
}
?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap and FontAwesome CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- Custom CSS file -->
	<link rel="stylesheet" href="css/custom.css">

	<title>BidBae</title>
</head>

<body>

	<!-- Navbars -->
	<nav class="navbar navbar-expand navbar-light bg-light mx-2">
		<a class="navbar-brand" href="#">BidBae</a>
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<?php
				if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == true) {
					echo "<div class='container'>
						<div class='row'>
							<div class='col-6'>
									<div class='dropdown'>
											<button class='btn btn-outline-secondary dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
													Hi, $display_name
											</button>
											<div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
											<a class='dropdown-item' href='create_item.php'>Create New Item</a>
												<a class='dropdown-item' href='create_auction.php'>Create New Auction</a>
												<a class='dropdown-item' href='create_category.php'>Add New Category</a>
											</div>
									</div>
							</div>
							<div class='col-6'>
									<button class='btn btn-outline-secondary'>
										<a class='text-secondary' href='logout.php'>Logout</a>
									</button>
							</div>
						</div>
					</div>";
				} else {
					echo "<button type='button' class='btn nav-link' data-toggle='modal' data-target='#loginModal'>Login</button>";
				}
				?>
			</li>
		</ul>
	</nav>
	<nav class="navbar navbar-expand navbar-dark bg-dark">
		<ul class="navbar-nav align-middle">
			<li class="nav-item mx-1">
				<a class="nav-link" href="browse.php">Browse</a>
			</li>            
			<?php
				if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] == true) {
					echo "<li class='nav-item mx-1'><a class='nav-link' href='mybids.php'>My Bids</a>
					</li>
					<li class='nav-item mx-1'>
						<a class='nav-link' href='item_list.php'>My Items</a>
					</li>
					<li class='nav-item mx-1'>
						<a class='nav-link' href='mylistings.php'>My Listings</a>
					</li>
					<li class='nav-item mx-1'>
						<a class='nav-link' href='recommendations.php'>Recommendation</a>
					</li>";
					} 
			  ?>
		</ul>
	</nav>

	<!-- Login modal -->
	<div class="modal fade" id="loginModal">
		<div class="modal-dialog">
			<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Login</h4>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<form method="POST" action="login_result.php">
						<div class="form-group">
							<label for="email">Email</label>
							<input type="text" class="form-control" id="email" name="email" placeholder="Email">
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input type="password" class="form-control" id="password" name="password"
								placeholder="Password">
						</div>
						<button type="submit" class="btn btn-primary form-control">Sign in</button>
					</form>
					<div class="text-center">or <a href="register.php">create an account</a></div>
				</div>

			</div>
		</div>
	</div> <!-- End modal -->

	<!-- Bootstrap and jQuery JS -->
	<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>