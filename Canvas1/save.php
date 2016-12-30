<?php
	phpinfo();
	$myfile = fopen("data2.txt", "w");
	fwrite($myfile, $_REQUEST["xvals"]);
	fwrite($myfile, "\n");
	fwrite($myfile, $_REQUEST["yvals"]);
	fwrite($myfile, "\n");
	fwrite($myfile, $_REQUEST["colorvals"]);
	fwrite($myfile, "\n");
	fwrite($myfile, $_REQUEST["sizevals"]);
	fwrite($myfile, "\n");
?>

