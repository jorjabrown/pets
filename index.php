<?php   #pet database project index.php
    $page_title = 'Owner list  ';
	include ('includes/header.html');
	require ('includes/pdoConn.php');
	echo 'List of Owners<br>';
	$sql = 'select * from owner';
	$stmt = $conn->query($sql);
	echo '<table border="1">';
	while($row = $stmt->fetch(PDO::FETCH_ASSOC) ) 
	{
		echo '<tr><td>',$row['oid'].'</td><td>'.$row['fname'].'</td><td>'.$row['lname'].'</td></tr>';		
	}
	echo '</table>';
	
	include ('includes/footer.html');
?>	