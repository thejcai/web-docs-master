<html>
	<head>
		<title>PHP Counter</title>
	</head>
	<body>
		<h1> Visitor count:
			<?php
				$views = 0;
				$visitors_file = "visitors.txt";
				
				if(file_exists($visitors_file)){
					$views = (int)file_get_contents($visitors_file);
				}
				$views++;
				print $views;
				file_put_contents($visitors_file, $views);
			?>
		</h1>
	</body>
</html>
