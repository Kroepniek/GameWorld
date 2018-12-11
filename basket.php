<?php

	session_start();

	if(isset($_GET['add']))
	{
		if($_GET['add'] == "true")
		{
			CodeURL("add");
		}
		else
		{
			DecodeURL();
			$Url = "basket.php?reload=true&ordered=".$_GET['ordered'];
			header("Location: $Url");
			exit();
		}
	}
	
	if(isset($_GET['reload']) && $_GET['reload'] == "true")
	{
		session_unset();
		$newUrl = "basket.php?add=true&ordered=".$_GET['ordered'];
		header("Location: $newUrl");
		exit();
	}

	$basket_ordered = array();
	$json_basket_ordered_array = array();
	$json_basket_ordered_amount_array = array();

	if(isset($_SESSION['ordered']))
	{
		if(!isset($_GET['ready']))
		{
			CodeURL("");
		}
		$json_basket_ordered_array = ToArray("IDs");
		$json_basket_ordered_amount_array = ToArray("Amounts");
	}
	else
	{
		if($_GET['ordered'] != "")
		{
			DecodeURL();
			$json_basket_ordered_array = ToArray("IDs");
			$json_basket_ordered_amount_array = ToArray("Amounts");
		}
		else
		{
			$_SESSION['ItemID_Size'] = 0;
		}
	}

	function DecodeURL()
	{
		$url = $_GET['ordered'];
		$IDs = array();

		for($i = 0; $i < strlen($url)-1; $i++)
		{
			if($url[$i] == 's')
			{
				if($url[$i-1] != 'a')
				{
					if(is_numeric($url[$i-1]))
					{
						if(is_numeric($url[$i-2]))
						{
							array_push($IDs, array($url[$i-2].$url[$i-1], $url[$i+1].$url[$i+2]));
						}
						else
						{
							array_push($IDs, array($url[$i-1], $url[$i+1].$url[$i+2]));
						}
					}
				}
			}
		}

		$size = sizeof($IDs);
		$_SESSION['ItemID_Size'] = $size;
		$_SESSION['Basket_Url'] = $url;

		for($j = 0; $j < $size; $j++)
		{
			$_SESSION['ItemID_'.$j] = $IDs[$j];
		}
		$_SESSION['ordered'] = true;
		CodeURL("");
	}

	function CodeURL($type)
	{
		$IDs = array();
		$curretUrl = $_GET['ordered'];

		$url = "";

		$size = $_SESSION['ItemID_Size'];

		for($j = 0; $j < $size; $j++)
		{
			$IDs[$j][0] = $_SESSION['ItemID_'.$j][0];
		}

		sort($IDs);

		for($j = 0; $j < $size; $j++)
		{
			$IDs[$j][1] = $_SESSION['ItemID_'.$j][1];
		}

		for($i = 0; $i < $size; $i++)
		{
			$url = $url.$IDs[$i][0]."s".$IDs[$i][1]."as";
		}

		$url = $_SESSION['Basket_Url'];
		
		if($type == "add")
		{
			$url = "basket.php?add=false&ordered=".$url.$curretUrl;
			header("Location: $url");
			exit();
		}
		else
		{
			$url = "basket.php?ready=true&ordered=".$url;
			header("Location: $url");
			exit();
		}
	}

	function ToArray($type)
	{
		if($type == "IDs")
		{
			for($i = 0; $i < $_SESSION['ItemID_Size']; $i++)
			{
				$basket_ordered[] = $_SESSION['ItemID_'.$i][0];
			}

			if(isset($basket_ordered))
			{
				return $basket_ordered;
			}
			else
			{
				return;
			}
		}
		else if($type == "Amounts")
		{
			for($i = 0; $i < $_SESSION['ItemID_Size']; $i++)
			{
				$basket_ordered[] = $_SESSION['ItemID_'.$i][1];
			}
			
			if(isset($basket_ordered))
			{
				return $basket_ordered;
			}
			else
			{
				return;
			}
		}
		else
		{
			return "Wrong type.";
		}
	}

	function Load($jsons, $jsons_amounts) 
	{
		try
		{
			require_once "connect.php";

			if ($db = mysqli_connect($host, $db_username, $db_password, $db_name))
			{
				$sql = "SELECT * FROM games WHERE ID IN (".implode(',',$jsons).")";
				$result = mysqli_query($db, $sql);

				if ($result->num_rows > 0) 
				{
					$loadedData = Array();
					while($row = $result->fetch_assoc())
					{
						$loadedData[] = $row;
					}
				}

			if(isset($loadedData))
			{
				$json_array = json_encode($loadedData);
				$json_amount_array = json_encode($jsons_amounts);
				echo '<script type="text/javascript">var basketItems = '. $json_array .'; '."\n\n".'var basketItemsAmounts = '. $json_amount_array .';</script>';
			}
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
			exit();
		}
	}

	if($_SESSION['ItemID_Size'] >= 1)
	{
		Load($json_basket_ordered_array, $json_basket_ordered_amount_array);
	}
	else
	{
		echo '<script type="text/javascript">var isBasketEmpty = true;</script>';
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
	<title>Game World - Basket</title>

	<script type="text/javascript">	
	var products = 0;

	function LoadItems()
	{
		if(typeof basketItems !== 'undefined')
		{
			for(var i = 0; i < basketItems.length; i++)
			{
				let item = new BasketItem(basketItems[i]['ID'], basketItems[i]['gameName'], "images/games/" + basketItems[i]['gameImg'], "€ " + basketItems[i]['gamePrice'], basketItems[i]['gamesLeft'], basketItems[i]['PS3'] + "|" + basketItems[i]['XBOX'] + "|" + basketItems[i]['PC'], basketItemsAmounts[i]);
				item.Append();
			}
		}
	}
	
	function ReloadTotal()
	{
		var som = 0;

		for(var i = 1; i <= basketItems.length; i++)
		{
			var toAdd = document.getElementById('Item_CurretID_' + i).innerHTML;
			toAdd = parseFloat(toAdd.substr(2));
			som += toAdd;
		}

		document.getElementById('basket-total').innerHTML = "€ " + som;
		document.getElementById('basket-total').innerHTML = ("€ " + ((Math.round(som * 100) / 100).toFixed(2)).toString());
	}

	function AmountChanged(gameID, amountID)
	{
		var amount = document.getElementById(amountID).value;
		var ordered = window.location.href;
		var newOrdered = ordered.removeFromA(0, ordered.indexOf("=s")+1);
		var gameIDlen = (gameID.toString()).length;

		if(amount.toString().length < 2)
		{
			amount = "0" + amount;
		}

		newOrdered = newOrdered.removeFromA(newOrdered.indexOf("s" + gameID + "s") + 2 + gameIDlen, 4);
		newOrdered = newOrdered.substr(0, newOrdered.indexOf("s" + gameID + "s") + 2 + gameIDlen) + amount + "as" + newOrdered.substr(newOrdered.indexOf("s" + gameID + "s") + 2 + gameIDlen);

		window.location.href = "basket.php?reload=true&ordered=" + newOrdered;
	}

	function RemoveFromBasket(ItemID)
	{
		var ordered = window.location.href;
		var newOrdered = ordered.removeFromA(0, ordered.indexOf("=s")+1);
		var itemIDlen = (ItemID.toString()).length;

		newOrdered = newOrdered.removeFromA(newOrdered.indexOf("s" + ItemID + "s"), itemIDlen + 5);

		newOrdered = (newOrdered == "s" ? "" : newOrdered);

		window.location.href = "basket.php?reload=true&ordered=" + newOrdered;
	}

	String.prototype.removeFromA = function(indexFrom, amountToRemove) 
	{
		return this.substr(0, indexFrom) + this.substr(indexFrom + amountToRemove);
	}

	class BasketItem {

  		constructor(gameID, gameName, gameImg, gamePrice, gamesLeft, gamePlatforms, orderedAmount) {
    		this.gameID = gameID;
    		this.gameName = gameName;
    		this.gameImg = gameImg;
    		this.gamePrice = gamePrice;
    		this.gamesLeft = gamesLeft;
			this.gamePlatforms = gamePlatforms;
			this.orderedAmount = orderedAmount;
  		}
		
		Append()
		{
			products++;

			var item_tr = document.createElement('tr');
			var name_td = document.createElement('td');
			var name = document.createElement('span');
			var img = document.createElement('img');
			var platforms_td = document.createElement('td');
			var platforms = document.createElement('span');
			var amount_td = document.createElement('td');
			var amount = document.createElement('input');
			var left = document.createElement('span');
			var remove = document.createElement('div');
			var price_td = document.createElement('td');
			var price = document.createElement('span');
			var curret_price = document.createElement('span');

			item_tr.setAttribute('id', "Item_tr_" + products);

			item_tr.className = 'basket-item';
			name.className = 'basket-item-name';
			img.className = 'basket-item-img';
			platforms.className = 'basket-item-platforms';
			amount.className = 'basket-item-input';
			left.className = 'basket-item-games-left';
			remove.className = 'basket-item-remove';
			price.className = 'basket-item-price';
			curret_price.className = 'basket-item-curret-price';

			img.setAttribute('src', this.gameImg);

			var itemID_AmountID = "Item_AmountID_" + products;
			var itemID_CurretID = "Item_CurretID_" + products;
			
			var amount_value = parseInt(this.orderedAmount);
			amount.setAttribute('type', "text");
			amount.setAttribute('id', itemID_AmountID);
			amount.setAttribute('value', amount_value);
			amount.setAttribute('onchange', "AmountChanged(" + this.gameID + ", '" + itemID_AmountID + "')");

			var CurretPrice = parseFloat(this.gamePrice.slice(2)) * amount_value;
			var CurretPriceTxt = document.createTextNode("€ " + ((Math.round(CurretPrice * 100) / 100).toFixed(2)).toString());
			curret_price.appendChild(CurretPriceTxt);

			curret_price.setAttribute('id', itemID_CurretID);

			var priceTxt = document.createTextNode(this.gamePrice);
			price.appendChild(priceTxt);

			var nameTxt = document.createTextNode(this.gameName);
			name.appendChild(nameTxt);

			var platformsT = "";
			platformsT += (this.gamePlatforms[0] == 1 ? " PS3" : "");
			platformsT += (this.gamePlatforms[2] == 1 ? " XBOX" : "");
			platformsT += (this.gamePlatforms[4] == 1 ? " PC" : "");

			var platformsTxt = document.createTextNode(platformsT);
			platforms.appendChild(platformsTxt);
			
			var leftTxt = document.createTextNode(this.gamesLeft + " games left");
			left.appendChild(leftTxt);

			var removeTxt = document.createTextNode("Remove");
    		remove.appendChild(removeTxt);
			remove.setAttribute('onclick', "RemoveFromBasket(" + this.gameID + ")");

			document.getElementById('basket-body').appendChild(item_tr);
			item_tr.appendChild(name_td);
			name_td.appendChild(img);
			name_td.appendChild(name);
			item_tr.appendChild(platforms_td);
			platforms_td.appendChild(platforms);
			item_tr.appendChild(amount_td);
			amount_td.appendChild(amount);
			amount_td.appendChild(remove);
			amount_td.appendChild(left);
			item_tr.appendChild(price_td);
			price_td.appendChild(curret_price);
			price_td.appendChild(price);
		}
	}

	window.onload = function() {
		LoadItems();

		if(typeof isBasketEmpty !== 'undefined' && isBasketEmpty)
		{
			document.getElementById('basket-tf').style.display = "none";
			document.getElementById("empty-basket").style.display = "block";
		}
		else
		{
			ReloadTotal();
		}
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

	<div id="basket-container">
		<table id="basket-table">
			<thead>
				<td>
					Product name
				</td>
				<td>
					Platforms
				</td>
				<td>
					Amount
				</td>
				<td>
					Price
				</td>
			</thead>
			<tbody id="basket-body">
			</tbody>
			<tfoot id="basket-tf">
				<tr>
					<td colspan="3">
						Total:
					</td>
					<td id="basket-total">
						
					</td>
				</tr>
			</tfoot>
		</table>
		<div id="empty-basket">Your basket is empty.</div>
	</div>
	<?php include 'footer.php';?>
</body>
</html>