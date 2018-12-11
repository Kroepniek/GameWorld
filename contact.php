<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Game World.">
	<link rel="icon" href="images/transparent_kon.png">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>Game World - Contact</title>
	<style>
		body
		{
			background-image: url("images/background.jpg");
			background-size: 100% 1079px;
			background-repeat: repeat;
		}
	</style>
	<?php

		if(isset($_POST['submit']))
		{
			$name_sender = $_POST['name'];
			$email_sender = $_POST['email'];
			$message = $_POST['message'];

			print($message);

			if(strlen($name_sender) > 3 && (strlen($email_sender) > 4 && filter_var($email_sender, FILTER_VALIDATE_EMAIL)))
			{
				mail("danieqn307@kroepniek.nl", "[Game World - Contact][".$name_sender."][".$email_sender."]", $message);
				header('Location: contact-success.php');

			}
		}

	?>
</head>
<body>
	<header id="header">
		<div id="logo">
			<a href="index.php">GameWorld.</a>
		</div>
	</header>
	
	<?php include 'nav.php';?>

	<div id="content-container">
		<form method="POST">
			<div id="contact-form">
				<div>
					<input class="contact-labels" type="text" name="name" placeholder="Name">
					<input class="contact-labels" type="text" name="email" placeholder="E-mail">
				</div>
				<div>
					<textarea class="contact-labels contact-textarea"type="textarea" name="message" placeholder="Message"></textarea>
				</div>
				<div>
					<input class="contact-button" type="submit" name="submit" value="Send">
				</div>
			</div>
		</form>
	</div>
	<?php include 'footer.php';?>
</body>
</html>