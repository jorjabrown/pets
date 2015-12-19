<?PHP
	//these are for development on my home machine, they will need to be changed
	$host = 'localhost';
	$user = 'root';
	$password='root';
	$db = 'pets';

	// UTF-8 is a character encoding scheme that allows you to conveniently store
    // a wide varienty of special characters, like ¢ or €, in your database.
    // By passing the following $options array to the database connection code we
    // are telling the MySQL server that we want to communicate with it using UTF-8
    // See Wikipedia for more information on UTF-8:
    // http://en.wikipedia.org/wiki/UTF-8
	  $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

	$conn = new PDO("mysql:host=$host;dbname=$db",$user,$password);
	try
	{
    // This statement configures PDO to throw an exception when it encounters
    // an error.  This allows us to use try/catch blocks to trap database errors.
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  	    // This statement configures PDO to return database rows from your database using an associative
	    // array.  This means the array will have string indexes, where the string value
	    // represents the name of the column in your database.
		$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	}
	catch(PDOException $e)
	{
		echo 'ERROR: ' . $e->getMessage();
	}
?>
