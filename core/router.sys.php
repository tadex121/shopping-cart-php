<?php
include_once('config/mapper.php');


$u = explode("?", REQUEST_URI);
$URL = $u[0];

/// IMPORTANT: "/admin" SHOULD NEVER BE MAPPER WITH ARGUMENTS, NEVER!!!!
if($URL == "/admin") {
	$directory = 'admin';
	$controller = 'login';
	$action = 'index';
} else {
	$directory = "";
	$controller = "";
	$action = "";
	$args = "";		
	$check = true;

	$nodes = explode("/", $URL);  
	$num_nodes = sizeof($nodes);  
	$maps = false;					

	$url = "/" . $nodes[1];

	if(isset($mapper[$url])) {
		// If URL Is Find In $mapper Array, Set $maps To True
		$maps = true;

		if($num_nodes > 2) {
			// If Number Of Nodes Bigger Then 2, Arguments Exists
			$args_exists = true;
			$URL = "/" . $nodes[1];
		} else {
			$args_exists = false;
		}
	} else {
		$maps = false;
	}

	if($maps) {
		$directory = $mapper[$URL]['directory'];
		$controller = $mapper[$URL]['controller'];
		$action = $mapper[$URL]['action'];

		if($args_exists) {
			$args = "";
			$i = 2;
			while($i < $num_nodes) {
				if($i == ($num_nodes - 1)) {
					$args .=  $nodes[$i];
				} else {
					$args .=  $nodes[$i] . "/";
				}
				$i++;
			}
		}
	} else {
		//$nodes = explode("/", $URL);
		//$max = sizeof($nodes);

		//var_dump($nodes);
		$directory = $nodes[1];
		$controller = @$nodes[2];
		$action = @$nodes[3];

		if($num_nodes > 4) {
			$args = "";
			for($i = 4; $i < $num_nodes; $i++) {
				if($i == ($num_nodes - 1)) {
					$args .= $nodes[$i];
				} else {
					$args .= $nodes[$i] . "/";
				}
			}
		}
	} 
}
//echo $directory . "<br />";
//echo $controller . "<br />";
//echo $action . "<br />";
//if(isset($args)) echo $args . "<br />";



if(($directory == 'admin') && ($controller != 'login') && (!isset($_SESSION["UserID"]) || (isset($_SESSION["UserID"]) && !$AdminRole))) {
	header("location: " . BASE_URL);
}
// --------------------------------------------------------------------------- //

//
//Q::table("products")
//        ->select()
//        ->where("OldUrl")
//
//exit;

$controller_file = CONTROLLERS_PATH . '/' . $directory . '/' . $controller . '.class.php';
//echo $controller_file . "<br/>";
if(!file_exists($controller_file)) {
	$controller = "index";
	$controller_file = CONTROLLERS_PATH . '/portal/index.class.php';
	$action = "category";
	//$args = REQUEST_URI;
	$args = $URL;
}
//echo $controller . "<br/>";
//echo $action . "<br/>";

if($directory == "library") {
	echo "Do not steal from our library, thief!";
	exit;
}

require $controller_file;

$controller_name = ucfirst($controller);

//$c = new $controller_name();
//$c = new $controller_name();
$action_name = str_replace('-', '_', $action);

// Call action 
if(!method_exists($controller_name, $action_name)) {
    //http_response_code(404); die();
    Helper::redirect(BASE_URL);
} else {
	//echo "Action call!";
	if(!empty($args)) {
		$args_array[] = null;
		$args_array = explode('/', $args);

		#echo $c . "<br />";
		#echo $action_name;
		//call_user_func_array(array($c, $action_name), $args_array);
		forward_static_call_array(array($controller_name::init(), $action_name), $args_array);
	} else {
		$controller_name::init()->$action_name();
	}
}
?>