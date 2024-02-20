<?php

include 'db.php';

// NECESSARY WHEN VUEJS RUNS IN DEV MODE
if(isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];
    session_id($session_id);
}

session_start();


// LOGIN USER (POPULATE SESSION)
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['password'])) {

	// ligacao base de dados
	$db = dbconnect($hostname,$db_name,$db_user,$db_passwd);


	$password = md5($_GET['password']);


	if($db) {
		// criar query numa string
		$query  = "SELECT id, voted, voted_at FROM users WHERE password_digest='$password'";

		// executar a query
		if(!($result = @ mysqli_query($db, $query)))
				showerror($db);

		$user = mysqli_fetch_assoc($result);

		if (mysqli_num_rows($result) == 0) 
			//login failed
			unset($_SESSION['user_id']);
		else {
			//login success
			$_SESSION['user_id']=$user['id'];
            // NECESSARY FOR VUEJS IN DEV MODE
			$user['session_id']= session_id();
		}

		// fechar a ligaço base de dados
		mysqli_close($db);
		
		$json=json_encode($user);

		// allow cross-origin requests (CORS)
		header('Access-Control-Allow-Origin: *');
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Headers: Authorization, Origin, User-Token, X-Requested-With, Content-Type");
		
		echo $json;	
	
	} // end if

}

// DESTROY SESSION
if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['session_id'])) {

    session_destroy();

    // allow cross-origin requests (CORS)
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Access-Control-Allow-Headers: Authorization, Origin, User-Token, X-Requested-With, Content-Type");

	echo '{"message":"Session destroyed"}';

}

// allow cross-origin requests (CORS)
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	header("Access-Control-Allow-Headers: Authorization, Origin, User-Token, X-Requested-With, Content-Type");	
}

?>
