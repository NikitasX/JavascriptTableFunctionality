<?php 

	$conn = mysqli_connect("Host","User","Password","Database");

	if(isset($_POST['id']) && $_POST['id'] !== null && $_POST['id'] !== '') {
		$conn -> query("DELETE FROM ergasia_table WHERE id = '$_POST[id]'");
	}
?>