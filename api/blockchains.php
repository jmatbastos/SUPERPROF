<?php

include 'db.php';

// NECESSARY WHEN VUEJS RUNS IN DEV MODE
if(isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];
    session_id($session_id);
}

session_start();

// GET BLOCKCHAINS
if($_SERVER['REQUEST_METHOD'] == 'GET') {

		// ligação à base de dados
		$db = dbconnect($hostname,$db_name,$db_user,$db_passwd);
		if($db) {
			// criar query numa string
			$query  = "SELECT * FROM blockchains";
		
			// executar a query
			if(!($result = @ mysqli_query($db, $query)))
				showerror($db);


			// vai buscar o resultado da query

			$nrows  = mysqli_num_rows($result);
			for($i=0; $i<$nrows; $i++)
			$blockchains[$i] = mysqli_fetch_assoc($result);


			// fechar a ligação à base de dados
			mysqli_close($db);

			
			// convert to JSON
			$JSON = json_encode($blockchains);

			// allow cross-origin requests (CORS)
			header('Access-Control-Allow-Origin: *');
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
			header("Access-Control-Allow-Headers: Authorization, Origin, User-Token, X-Requested-With, Content-Type");

			echo $JSON; 



		} // end if
	
}

// ADD A NEW BLOCKCHAIN
if($_SERVER['REQUEST_METHOD'] == 'POST') {

	$json=file_get_contents('php://input');
	$data = json_decode($json, true);
	
	if(isset($_SESSION['user_id'])) {

		// ligacao base de dados
		$db = dbconnect($hostname,$db_name,$db_user,$db_passwd);
		if($db) {

			// CHECK if USER CAN VOTE

			$query  = "SELECT voted FROM users WHERE id='" . $_SESSION['user_id'] . "'";

			// executar a query
			if(!($result = @ mysqli_query($db, $query)))
					showerror($db);

			$user = mysqli_fetch_assoc($result);

			if ($user['voted'] == NULL)  {
				// USER CAN VOTE
				$voted_at = date("Y-m-d H:i:s", mktime()-3600);
				// START TRANSACTION
				mysqli_begin_transaction($db);
				try {					
					// ADD BLOCKCHAIN
					// get last blockchain
					$query  = "SELECT * FROM blockchains order by id desc limit 1";
					$result = mysqli_query($db, $query);
					$blockchain = mysqli_fetch_assoc($result);					

					//generate new blockchain
					$blockchain = md5($blockchain['blockchain'] . $_SESSION['user_id'] . $data['prof_id'] . $voted_at);

					$query  = "INSERT INTO blockchains SET blockchain='$blockchain',voted_at='$voted_at',prof_id='" . $data['prof_id'] . "',user_id='" . $_SESSION['user_id'] . "'";				
					mysqli_query($db, $query);

					// get the new blockchain
					$query  = "SELECT * FROM blockchains order by id desc limit 1";
					$result = mysqli_query($db, $query);
					$blockchain = mysqli_fetch_assoc($result);
					$json=json_encode($blockchain);	
				
					// UPDATE PROF
					$query  = "UPDATE profs SET votes = votes + 1, last_voted_at='$voted_at' WHERE id='" . $data['prof_id']  . "'";
					mysqli_query($db, $query);

					// UPDATE USER
					$query  = "UPDATE users SET voted='1', voted_at='$voted_at' WHERE id='" . $_SESSION['user_id'] . "'";
					mysqli_query($db, $query);

					// EVERYTHING OK, COMMIT 
					mysqli_commit($db);
				} 
				catch (mysqli_sql_exception $exception) {
					mysqli_rollback($db);				
					throw $exception;
				}
				
			}
			else 
				$json = '{"error":"user already voted"}';	
			
				// fechar a ligação à base de dados
			mysqli_close($db);

		} // end if

	}
	// allow cross-origin requests (CORS)
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	header("Access-Control-Allow-Headers: Authorization, Origin, User-Token, X-Requested-With, Content-Type");
	echo $json;
}

// allow cross-origin requests (CORS)
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	header("Access-Control-Allow-Headers: Authorization, Origin, User-Token, X-Requested-With, Content-Type");	
}
?>
