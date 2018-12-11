<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Game World.">
	<link rel="icon" href="images/transparent_kon.png">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>Game World.</title>
</head>
<body>
	<header id="header">
		<div id="logo">
			<a href="index.php">GameWorld.</a>
		</div>
	</header>

	<?php include 'nav.php';?>

	<div id="container">
		<div id="main-foto">
			<img src="images/main-foto.jpg" height="450" width="1400">
			<h1>Welcome to GameWorld</h1>
			<h3>The most complete webshop!</h3>
			<div class="hz-line"></div>
			<div id="rotatedBox"></div>
			<img src="images/franklin.png" id="franklin">
		</div>
	</div>
	<div id="platforms">
		<a href="products.php?platform=ps"><div id="platform-ps">Playstation</div></a>
		<a href="products.php?platform=xbox"><div id="platform-xbox">Xbox</div></a>
		<a href="products.php?platform=pc"><div id="platform-pc">PC</div></a>
	</div>
	<?php include 'footer.php';?>
</body>
</html>