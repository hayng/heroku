<?php
	header("Content-Type:application/json");
	$json = array("status" => 200);
	echo json_encode($json,JSON_PRETTY_PRINT+JSON_UNESCAPED_SLASHES);
?>