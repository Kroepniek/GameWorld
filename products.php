<?php

	session_start();

	if(!isset($_GET['platform']))
	{
		header('Location: products.php?platform=all');
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="description" content="Game World.">
	<link rel="icon" href="images/transparent_kon.png">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" href="css/fontello.css">
	<title>Game World - Products</title>

	<script type="text/javascript">
	<?php

		function Load() 
		{
			try
			{
				require_once "connect.php";

				if ($db = mysqli_connect($host, $db_username, $db_password, $db_name))
				{
					switch ($_GET['platform']) {
						case 'ps':
							$sql = "SELECT * FROM games WHERE PS3 = 1";
							break;
						
						case 'xbox':
							$sql = "SELECT * FROM games WHERE XBOX = 1";
							break;
						
						case 'pc':
							$sql = "SELECT * FROM games WHERE PC = 1";
							break;

						case 'all':
							$sql = "SELECT * FROM games";
							break;
						
						default:
							header('Location: products.php?platform=all');
							break;
					}

					$result = mysqli_query($db, $sql);

					if ($result->num_rows > 0) 
					{
						$loadedData = Array();
						while($row = $result->fetch_assoc())
						{
							$loadedData[] = $row;
						}
					}

					$json_array = json_encode($loadedData);
					echo "var games = ". $json_array . ";\n";

					mysqli_close($db);
				}
				else
				{
					throw new Exception('Connection Error :: Read the file README.txt to fix it.');
				}
			}
			catch(Exception $e)
			{
				header('Location: README.txt');
			}

			
		}
		Load();
	?>
	
	var curretPlatform = "";
	var products = 0;
	var IDs = [];
	var ordered = "s";

	function GetPlatform()
	{
		curretPlatform = "<?php echo $_GET['platform']; ?>";

		switch (curretPlatform) {
			case 'ps':
				document.getElementById('curret-platform').style.backgroundColor = "#003791";
				document.getElementById('curret-platform').style.boxShadow = "0 0 1px #003791";

				document.getElementById('platform-span-ps').style.fontSize = "30px";
				document.getElementById('platform-span-ps').style.color = "#FFFFFF";
				document.getElementById('platform-span-xbox').style.fontSize = "18px";
				document.getElementById('platform-span-xbox').style.color = "#447bd4";
				document.getElementById('platform-span-pc').style.fontSize = "18px";
				document.getElementById('platform-span-pc').style.color = "#447bd4";
				document.getElementById('platform-span-all').style.fontSize = "18px";
				document.getElementById('platform-span-all').style.color = "#447bd4";
				break;

			case 'xbox':
				document.getElementById('curret-platform').style.backgroundColor = "#108C10";
				document.getElementById('curret-platform').style.boxShadow = "0 0 1px #108C10";

				document.getElementById('platform-span-ps').style.fontSize = "18px";
				document.getElementById('platform-span-ps').style.color = "#54cf54";
				document.getElementById('platform-span-xbox').style.fontSize = "30px";
				document.getElementById('platform-span-xbox').style.color = "#FFFFFF";
				document.getElementById('platform-span-pc').style.fontSize = "18px";
				document.getElementById('platform-span-pc').style.color = "#54cf54";
				document.getElementById('platform-span-all').style.fontSize = "18px";
				document.getElementById('platform-span-all').style.color = "#54cf54";
				break;

			case 'pc':
				document.getElementById('curret-platform').style.backgroundColor = "#000000";
				document.getElementById('curret-platform').style.boxShadow = "0 0 1px #000000";

				document.getElementById('platform-span-ps').style.fontSize = "18px";
				document.getElementById('platform-span-ps').style.color = "#444444";
				document.getElementById('platform-span-xbox').style.fontSize = "18px";
				document.getElementById('platform-span-xbox').style.color = "#444444";
				document.getElementById('platform-span-pc').style.fontSize = "30px";
				document.getElementById('platform-span-pc').style.color = "#FFFFFF";
				document.getElementById('platform-span-all').style.fontSize = "18px";
				document.getElementById('platform-span-all').style.color = "#444444";
				break;
			
			case 'all':
				document.getElementById('curret-platform').style.backgroundColor = "#560091";
				document.getElementById('curret-platform').style.boxShadow = "0 0 1px #560091";

				document.getElementById('platform-span-ps').style.fontSize = "18px";
				document.getElementById('platform-span-ps').style.color = "#9a44d5";
				document.getElementById('platform-span-xbox').style.fontSize = "18px";
				document.getElementById('platform-span-xbox').style.color = "#9a44d5";
				document.getElementById('platform-span-pc').style.fontSize = "18px";
				document.getElementById('platform-span-pc').style.color = "#9a44d5";
				document.getElementById('platform-span-all').style.fontSize = "30px";
				document.getElementById('platform-span-all').style.color = "#FFFFFF";
				break;
		
			default:
				header('Location: products.php?platform=all');
				break;
		}
	}

	function LoadItems()
	{
		for(var i = 0; i < games.length; i++)
		{
			let item = new ShopItem(games[i]['ID'], games[i]['gameName'], "images/games/" + games[i]['gameImg'], "€ " + games[i]['gamePrice'], games[i]['gamesLeft'], games[i]['PS3'] + "|" + games[i]['XBOX'] + "|" + games[i]['PC']);
			item.Append();
		}
	}

	function AddToBasket(ID, itemid)
	{
		document.getElementById(itemid).innerHTML = 'Order <i class="icon-basket"></i>';
		document.getElementById(itemid).style.color = "#6EC47A";

		var previousItem = "";

		if(!IDs.includes(ID))
		{	
			if(IDs.length > 0)
			{
				for(var i = 0; i < IDs.length+1; i++)
				{
					if(ID > IDs[i])
					{
						IDs.splice(i, 0, ID);
						break;
					}
					else
					{
						IDs.splice(i-1, 0, ID);
						break;
					}
				}
			}
			else
			{
				IDs.push(ID);
			}
			
			IDs.sort(comparator);

			ordered = "s";

			for(var j = 0; j < IDs.length; j++)
			{
				ordered += IDs[j] + "s01as";
			}

		}
		else
		{
			var IDlenght = (ID % 10 >= 1 ? 2 : 1);
			ordered = ordered.replaceFromTo(ordered.indexOf("s" + ID + "s"), ordered.indexOf("s" + ID + "s") + IDlenght + 4, "");
			document.getElementById(itemid).innerHTML = 'Order';
			document.getElementById(itemid).style.color = "";
		}

		document.getElementById('basket').href = "basket.php?add=true&ordered=" + ordered;
	}

	function comparator(a, b)
	{
		return a - b;
	}

	String.prototype.replaceFromTo=function(indexFrom, indexTo, replacement)
	{
		return this.substr(0, indexFrom) + replacement + this.substr(indexTo+1, this.length);
	}

	function Scrolling() 
	{
		if (document.body.scrollTop > 150 || document.documentElement.scrollTop > 150)
		{
			document.getElementById("curret-platform").style.position = "fixed";
			document.getElementById("curret-platform").style.top = "25px";
			
		} else 
		{
			document.getElementById("curret-platform").style.position = "absolute";
			document.getElementById("curret-platform").style.top = "175px";
		}
	}

	function FocusOnSpan(ID, platform)
	{
		var fadeOutColor = '#000000';
		switch (platform) {
			case 'ps':
				fadeOutColor = "#447bd4";
				break;
		
			case 'xbox':
				fadeOutColor = "#54cf54";
				break;
		
			case 'pc':
				fadeOutColor = "#444444";
				break;
		
			case 'all':
				fadeOutColor = "#9a44d5";
				break;
		
			default:
				fadeOutColor = '#000000';
				break;
		}

		document.getElementById('platform-span-ps').style.fontSize = "18px";
		document.getElementById('platform-span-ps').style.color = fadeOutColor;
		document.getElementById('platform-span-xbox').style.fontSize = "18px";
		document.getElementById('platform-span-xbox').style.color = fadeOutColor;
		document.getElementById('platform-span-pc').style.fontSize = "18px";
		document.getElementById('platform-span-pc').style.color = fadeOutColor;
		document.getElementById('platform-span-all').style.fontSize = "18px";
		document.getElementById('platform-span-all').style.color = fadeOutColor;
		
		document.getElementById(ID).style.fontSize = "30px";
		document.getElementById(ID).style.color = "#FFFFFF";
	}

	function FocusBack(platform)
	{
		var fadeOutColor = '#000000';
		switch (platform) {
			case 'ps':
				fadeOutColor = "#447bd4";
				break;
		
			case 'xbox':
				fadeOutColor = "#54cf54";
				break;
		
			case 'pc':
				fadeOutColor = "#444444";
				break;
		
			case 'all':
				fadeOutColor = "#9a44d5";
				break;
		
			default:
				fadeOutColor = '#000000';
				break;
		}

		document.getElementById('platform-span-ps').style.fontSize = "18px";
		document.getElementById('platform-span-ps').style.color = fadeOutColor;
		document.getElementById('platform-span-xbox').style.fontSize = "18px";
		document.getElementById('platform-span-xbox').style.color = fadeOutColor;
		document.getElementById('platform-span-pc').style.fontSize = "18px";
		document.getElementById('platform-span-pc').style.color = fadeOutColor;
		document.getElementById('platform-span-all').style.fontSize = "18px";
		document.getElementById('platform-span-all').style.color = fadeOutColor;

		document.getElementById('platform-span-' + platform).style.fontSize = "30px";
		document.getElementById('platform-span-' + platform).style.color = "#FFFFFF";
	}

	class ShopItem {

  		constructor(gameID, gameName, gameImg, gamePrice, gamesLeft, gamePlatforms) {
    		this.gameID = gameID;
    		this.gameName = gameName;
    		this.gameImg = gameImg;
    		this.gamePrice = gamePrice;
    		this.gamesLeft = gamesLeft;
    		this.gamePlatforms = gamePlatforms;
  		}
		
		Append()
		{
			products++;

			var item = document.createElement('div');
			var img = document.createElement('img');
			var price = document.createElement('div');
			var name = document.createElement('span');
			var left = document.createElement('span');
			var order = document.createElement('div');

			if(this.gamePlatforms[0] == '1') 
			{
				var platformPS3 = document.createElement('img');
				platformPS3.className = 'shop-item-platforms';
				platformPS3.setAttribute('src', "images/platforms/ps3.ico");
				if(this.gamePlatforms[2] != '1' || this.gamePlatforms[4] != '1')
				{
					platformPS3.setAttribute('style', "left: -120px;");
				}
			}
			if(this.gamePlatforms[2] == '1') 
			{
				var platformXBOX = document.createElement('img');
				platformXBOX.className = 'shop-item-platforms';
				platformXBOX.setAttribute('src', "images/platforms/xbox.ico");
				if(this.gamePlatforms[0] != '1')
				{
					platformXBOX.setAttribute('style', "left: -140px;");
				}
				else if(this.gamePlatforms[4] != '1')
				{
					platformXBOX.setAttribute('style', "left: -120px;");
				}
			}
			if(this.gamePlatforms[4] == '1') 
			{
				var platformPC = document.createElement('img');
				platformPC.className = 'shop-item-platforms';
				platformPC.setAttribute('src', "images/platforms/pc.ico");
				if(this.gamePlatforms[0] != '1' && this.gamePlatforms[2] != '1')
				{
					platformPC.setAttribute('style', "left: -140px;");
				}
				else if(this.gamePlatforms[0] == '1' && this.gamePlatforms[2] != '1')
				{
					platformPC.setAttribute('style', "left: -120px;");
				}
				else if(this.gamePlatforms[2] != '1')
				{
					platformPC.setAttribute('style', "left: -180px;");
				}
				
			}

			item.className = 'shop-item';
			img.className = 'shop-item-img';
			price.className = 'shop-item-price';
			name.className = 'shop-item-name';
			left.className = 'shop-item-games-left';
			order.className = 'shop-item-order';

			if ((products % 4 == 0) && (products > 0)) 
			{
				item.setAttribute('style', "margin-right: 0px");
			}

			img.setAttribute('src', this.gameImg);
			
			var priceTxt = document.createTextNode(this.gamePrice);
			price.appendChild(priceTxt);

			var nameTxt = document.createTextNode(this.gameName);
			name.appendChild(nameTxt);
			
			var leftTxt = document.createTextNode(this.gamesLeft + " games left");
			left.appendChild(leftTxt);

			var orderTxt = document.createTextNode("Order");
			order.appendChild(orderTxt);
			
			var itemID = "Item_" + products;
    		order.setAttribute('id', itemID);
			order.setAttribute('onclick', "AddToBasket(" + this.gameID + ", '" + itemID + "')");

			document.getElementById('products-container').appendChild(item);
			item.appendChild(img);
			item.appendChild(price);

			if(this.gamePlatforms[0] == '1') 
			{
				item.appendChild(platformPS3);
			}
			if(this.gamePlatforms[2] == '1') 
			{
				item.appendChild(platformXBOX);
			}
			if(this.gamePlatforms[4] == '1') 
			{
				item.appendChild(platformPC);
			}

			item.appendChild(name);
			item.appendChild(left);
			item.appendChild(order);

			document.getElementById('products-container').style.height = (Math.ceil(products/4) * 525) + 120 + "px";
		}
	}

	window.onload = function() {
		GetPlatform();
		LoadItems();
	};

	window.onscroll = function() {
		Scrolling();
	};

</script>
<style>
	body
	{
		background-image: url("images/background.jpg");
		background-size: 100% 1079px;
   	 	background-repeat: repeat;
	}
</style>
</head>
<body>
	<header id="header">
		<div id="logo">
			<a href="index.php">GameWorld.</a>
		</div>
	</header>

	<?php include 'nav.php';?>

	<div id="curret-platform">
		<a href="products.php?platform=ps" onmouseout="FocusBack('<?= $_GET['platform'] ?>')" onmouseover="FocusOnSpan('platform-span-ps','<?= $_GET['platform'] ?>')"><span class="platform-spans" id="platform-span-ps">Playstation</span></a>
		<a href="products.php?platform=xbox" onmouseout="FocusBack('<?= $_GET['platform'] ?>')" onmouseover="FocusOnSpan('platform-span-xbox','<?= $_GET['platform'] ?>')"><span class="platform-spans" id="platform-span-xbox">Xbox</span></a>
		<a href="products.php?platform=pc" onmouseout="FocusBack('<?= $_GET['platform'] ?>')" onmouseover="FocusOnSpan('platform-span-pc','<?= $_GET['platform'] ?>')"><span class="platform-spans" id="platform-span-pc">PC</span></a>
		<a href="products.php?platform=all" onmouseout="FocusBack('<?= $_GET['platform'] ?>')" onmouseover="FocusOnSpan('platform-span-all','<?= $_GET['platform'] ?>')"><span class="platform-spans" id="platform-span-all">All games</span></a>
		<a href="basket.php?ordered=" id="basket"><i class="icon-basket"></i></a>
	</div>
	<div id="products-container">
		
	</div>
	<?php include 'footer.php';?>
</body>
</html>