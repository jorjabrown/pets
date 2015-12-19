<?php   #pet database project petlist.php
    $page_title = 'Owner list of pets  ';
	include ('includes/header.html');
	require ('includes/pdoConn.php');
	echo 'List of Owners<br>';
	$sql = 'select * from owner';
	$stmt = $conn->query($sql);
	echo '<form action="petlist.php" method="post">';
	echo '<select name= "own">';
	foreach($conn->query($sql) as $row)
	{
		echo '<option value = "';
		echo $row['oid'];
		echo ' "> ';
		echo $row['lname'];
		echo '</option>';
	}// end foreach
	echo '</select>';
	echo '<br><input type="submit" name="submit" value="Show Pets"><br>';
	echo '</form>';
	//now to check if the submit button has been clicked
	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$oidIn = $_POST['own'];
		echo 'The owner number is '.$oidIn;
		echo '<br><br>';
		$sql2 = "select  pet.name, pet.breed from pet, owns where pet.pid = owns.pid and owns.oid = ?";
		$stmt = $conn->prepare($sql2);
		$stmt->execute(array($oidIn));
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach($rows as $r)
		{
			echo $r['name'].' '.$r['breed'].' <br>';
		}//end foreach
	}//end if request method
	
	include ('includes/footer.html');
?>	