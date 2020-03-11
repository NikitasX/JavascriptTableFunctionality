<?php  

	$_POST = $_POST['SaveArray'];

	$conn = mysqli_connect("Host","User","Password","Database");

	$table = "ergasia_table";

	
	$conn -> query("CREATE TABLE IF NOT EXISTS $table (
	id INT(255) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	Height VARCHAR(255) NOT NULL,
	Score VARCHAR(255) NOT NULL,
	Income VARCHAR(255) NOT NULL,
	Age VARCHAR(255) NOT NULL
	)");
		
	if(isset($_POST['id']) && $_POST['id'] !== null && $_POST['id'] !== '') {
		$conn -> query("UPDATE ergasia_table SET Height = '$_POST[Height]', Score = '$_POST[Score]',
		Income = '$_POST[Income]', Age = '$_POST[Age]' WHERE id = '$_POST[id]'");
	} else {
		$conn -> query("INSERT INTO ergasia_table (Height, Score, Income, Age) 
		VALUES ('$_POST[Height]', '$_POST[Score]', '$_POST[Income]', '$_POST[Age]')");
	}
	
?>