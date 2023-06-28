<!doctype html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="logincss.css">
		<title>Login</title> 
	</head>
	<body>
		<div style="float: right; position: fixed;">
			<img src="cof1.png" style="width: 190px; "><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;"><img src="cof1.png" style="width: 190px;">
		</div>
		
		<?php
			session_start();
			$error = '';

			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$username = 'admin';
				$password = 'password';

				$input_username = $_POST['username'];
				$input_password = $_POST['password'];

				if ($input_username == $username && $input_password == $password) {
					$_SESSION['loggedin'] = true;
					header('Location: index.php');
					exit();
				} else {
					$error = 'Invalid username or password.';
					echo '<script>document.addEventListener("DOMContentLoaded", function() {
							document.getElementById("modal").style.display = "block";
						});
					</script>';
				}
			}
		?>
		<div class="fullcontainer" style="position: relative; padding-top: 60px"> 
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="background-color: #fff8e7">
				<div class="imgcontainer">
					<br>
					<img src="logo.png" alt="Logo" class="logo" style="width: 300px;">
				</div>
				<div class="container">
					<label><font color="#47251e"><b>Username: </b></label></font>
					<input type="text" name="username" placeholder="Enter username" required><br>
					<label><font color="#47251e"><b>Password: </b></label></font>
					<input type="password" name="password" placeholder="Enter password" required><br>
					<button type="submit">Log in</button>
					<br><br>
				</div>
			</form>
		</div>

		<!-- Modal window -->
		<div id="modal" class="modal">
			<div class="modal-content">
				<h2>Error</h2>
				<p><?php echo $error; ?></p>
				<button onclick="closeModal()">Close</button>
			</div>
		</div>

		<script>
			function closeModal() {
				document.getElementById("modal").style.display = "none";
			}
		</script>
	</body>
</html>
