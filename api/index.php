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
$app->get('/list', function() use ($app, $conn) {
    $sql = 'select * from owner';
    $stmt = $conn->query($sql);
    $ret = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC) )
    {
		array_push($ret, $row);
    }
	echo json_encode($ret);
});

$app->get('/listPets', function() use ($app, $conn) {
    $sql = 'select o.oid,p.pid, p.name, p.type, p.breed from owner o, pet p, owns os where o.oid = os.oid and p.pid = os.pid order by type, breed, name';
    $stmt = $conn->query($sql);
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

// pass $app and $conn to the use directive to use those vars in body of anonymous function

$app->post('/owners/add', function () use ($app, $conn){
    $post = $app->request->post();
    $ret = array();
    $fname = $post['fname'];
    $lname = $post['lname'];
    error_log("first name is $fname, last name is $lname");
    $sql = 'insert into owner (fname, lname) values ("' . $fname . '", "' . $lname . '")';
    $stmt = $conn->query($sql);
    if ($stmt) 
    {
        $ret = array("status" => "success");
    }
    else
    {
        $ret['status'] = "failed";
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
