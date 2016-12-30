<html>
	<head>
		<title>Stub Page</title>
		<script type="text/javascript">
			var myVar;
			function alertfunc(){
				//alert("working");
				myVar = <?php echo json_encode(file_get_contents("data.txt")); ?>;
				//alert(myVar);
				top.dealWithLoading(myVar);
			}
		</script>
	</head>
	<body onload=alertfunc()>
		<?php
			phpinfo();
			$filename = "data.txt";
			$filecontents = file_get_contents($filename);
			print $filecontents;
		?>
	</body>
</html>
