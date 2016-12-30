<html>
	<head>
		<title> PHP Form </title>
		<style type="text/css">
			html{
				background-color: #CCFFCC;
			}
			h1, h2{
				color: #333333;
				font-family: Copperplate, 'Copperplate Gothic Light', fantasy;
			}
		</style>
	</head>
	<body>
		<center>
		<h1> A Simple Questionnaire </h1>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			Name: <input type="text" name="fname" style="width: 100px" placeholder="Ex: John" value="<?php echo $fname;?>" autofocus>
			<input type="text" name="mname" style="width: 25px" placeholder="F." value="<?php echo $mname;?>">
			<input type="text" name="lname" style="width: 100px" placeholder="Kennedy" value="<?php echo $lname;?>">
			<br><br>
			Phone: <input type="text" name="phone" style="width: 100px" placeholder="(xxx)-xxx-xxxx" value="<?php echo $phone;?>">
			<br><br>
			Birth Date: <input type="text" name="bday" style="width: 100px" placeholder="mm-dd-yy" value="<?php echo $bday;?>">
			<br><br>
			Favorite Spice Girl: <select name="mydropdown">
				<option value="Blank"> </option>
				<option value="Baby Spice">Baby Spice</option>
				<option value="Ginger Spice">Ginger Spice</option>
				<option value="Posh Spice">Posh Spice</option>
				<option value="Scary Spice">Scary Spice</option>
				<option value="Sporty Spice">Sporty Spice</option>
			</select>
			<br><br>
			<button name="submit" type="submit" accessKey=s value="submit"><u>S</u>ubmit</button>
		</form>

		<?php
			//phpinfo();
			if(isset($_REQUEST["submit"])){
				echo "<h2> Your Input: </h2>";
				echo "Name: " . htmlspecialchars($_REQUEST["fname"]) . " " . htmlspecialchars($_REQUEST["mname"]) . " " . htmlspecialchars($_REQUEST["lname"]);
				echo "<br>";
				echo "Phone: " . htmlspecialchars($_REQUEST["phone"]);
				echo "<br>";
				echo "Birthday: " . htmlspecialchars($_REQUEST["bday"]);
				echo "<br>";
				echo "Favorite Spice Girl: " . htmlspecialchars($_REQUEST["mydropdown"]);
				echo "<br>";
				echo "Time: " . date('Y-m-d H:i:s');
			}
		?>
		<center>
	</body>
</html>
