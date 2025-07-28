<?php
	$conn = new mysqli('localhost', 'root', '', 'brgy_balas');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
?>