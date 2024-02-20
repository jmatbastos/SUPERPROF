<?php

include 'db.php';

// GET PROFS
if($_SERVER['REQUEST_METHOD'] == 'GET') {


		// ligação à base de dados
		$db = dbconnect($hostname,$db_name,$db_user,$db_passwd);
		if($db) {

			$query  = "SELECT * FROM profs";
		
			// executar a query
			if(!($result = @ mysqli_query($db, $query)))
				showerror($db);


			// vai buscar o resultado da query

			$nrows  = mysqli_num_rows($result);
			for($i=0; $i<$nrows; $i++)
			    $profs[$i] = mysqli_fetch_assoc($result);


			// fechar a ligação à base de dados
			mysqli_close($db);

			
			// convert to JSON
            $profs=mb_convert_encoding($profs,'UTF-8','ISO-8859-1');
			$json = json_encode($profs);

			// allow cross-origin requests (CORS)
			header('Access-Control-Allow-Origin: *');
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
			header("Access-Control-Allow-Headers: Authorization, Origin, User-Token, X-Requested-With, Content-Type");

			echo $json; 



		} // end if

}



// allow cross-origin requests (CORS)
if($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
	header("Access-Control-Allow-Headers: Authorization, Origin, User-Token, X-Requested-With, Content-Type");	
}
?>
