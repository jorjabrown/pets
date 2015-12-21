<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require '../../Slim/Slim.php';
require '../includes/pdoConn.php';
\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();
$app->response->headers->set( 'Content-Type', 'application/json' );
/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */
 
$app->get('/owners', function() use ($app, $conn) {
//put a list of valid urls to show usage 
	$ret = array("/owners/list" => "GET", "/owners/add" => "POST");
	echo json_encode($ret);
	
});
 
$app->get('/owners/list', function() use ($app, $conn) {
/*
set up so if no parameters it returns all of the owners
if a parameter (oid for now) is passed it returns just that one
usage is something like http://raspberrypi/pets/api/list/pets?pid=2 in the browser window
*/
	$get = $app->request->get();  //gets the variables passed in after the ?
	if (isset($get['oid'] )
	{
		$oid = $get['oid'];
		$sql = "select * from owner where oid = ?';
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($oid) );
	}
	else
	{
		$sql = 'select * from owner';
		$stmt = $conn->query($sql);
	}
	$ret = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC) )
    {
		array_push($ret, $row);
    }
	echo json_encode($ret);
});

$app->get('/pets/list', function() use ($app, $conn) {
	$get = $app->request->get();   //get variables passed in after ? in url
	if (isset($get['pid']))
	{
		$pid = $get['pid'];
		$sql ="select o.oid,p.pid, p.name, p.type, p.breed, o.fname, o.lname from owner o, pet p, owns os where p.pid = ? and o.oid = os.oid and p.pid = os.pid order by type, breed, name";	
		$stmt = $conn->prepare($sql);
		$stmt->execute(array($pid));
	}
	else
	{
		$sql = 'select o.oid,p.pid, p.name, p.type, p.breed, o.fname, o.lname from owner o, pet p, owns os where o.oid = os.oid and p.pid = os.pid order by type, breed, name';
		$stmt = $conn->query($sql);
    }
    $ret = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC) )
    {
		array_push($ret, $row);
    }
	echo json_encode($ret);
});

$app->get('/another_endpoint', function () use ($app, $conn) {
    $ret = array("some_key" => "some value");
    echo json_encode($ret);
});


/* pass $app and $conn to the use directive to use those vars in body of anonymous function*/

$app->post('/pets/add', function () use ($app, $conn) {
/*to add a pet, make it like owners/add, but you need to include an oid 
because pets cannot be in without an owner. Which means you need to update 
the pet table and the owns table
 pet
	(pid int auto_increment primary key,
	type varchar(10),
	name varchar(25),
	dob date,
	breed varchar(20));
	
 owns
	(pid int,
	oid int,
	dop date,
	primary key(pid, oid, dop),
	foreign key(pid) references pet(pid) on delete cascade on update cascade,
	foreign key(oid) references owner(oid) on delete cascade on update cascade) ;
 so insert into the pet table, then read that record and get the pid
*/
	$post = $app->request->post();
	$ret = array();
	$oid = $post['oid'];
	$type = $post['type']; 
	$breed = $post['breed'];
	$name = $post['name'];
	$dob = $post['dob'];
	$dop = $post['dop'];
	$sql = 'insert into pet (type, name, dob, breed) values (?,?,?,?)';
	$stmt = $conn->prepare($sql);
	
});

$app->post('/owners/add', function () use ($app, $conn){
    $post = $app->request->post();
    $ret = array();
    $fname = $post['fname'];
    $lname = $post['lname'];
    error_log("first name is $fname, last name is $lname");
    $sql = 'insert into owner (fname, lname) values (?,?)';
	$stmt = $conn->prepare($sql);
	$ok = $stmt->execute(array($fname,$lname));
   
    if ($ok) 
    {
        $ret = array("status" => "success");
    }
    else
    {
        $ret['status'] = " insert failed";
    }
    echo json_encode($ret);
});



// POST route
$app->post(
    '/post',
    function () {
        echo 'This is a POST route';
    }
);

// PUT route
$app->put(
    '/put',
    function () {
        echo 'This is a PUT route';
    }
);

// PATCH route
$app->patch('/patch', function () {
    echo 'This is a PATCH route';
});

// DELETE route
$app->delete(
    '/delete',
    function () {
        echo 'This is a DELETE route';
    }
);

/**
 * Step 4: Run the Slim application
 *
 * This method should be called last. This executes the Slim application
 * and returns the HTTP response to the HTTP client.
 */
$app->run();
