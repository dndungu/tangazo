<?php

date_default_timezone_set('Africa/Nairobi');

ini_set('display_errors', 0);

ini_set('log_errors', 1);

ini_set('error_log', getcwd() . "/logs/error");

require_once('config.php');

function dbConnect(){
	global $dbConnection, $config;
	if(is_object($dbConnection)) return;
	$dbConnection = mysqli_connect($config['DB_HOST'], $config['DB_USER'], $config['DB_PASSWORD'], $config['DB_SCHEMA']) or die(mysqli_connect_error());
}
function dbQuery($query = NULL){
	dbConnect();
	global $dbConnection;
	$result = mysqli_query($dbConnection, $query) or die(mysqli_error($dbConnection));
	return $result;
}

function dbMultiQuery($query = NULL){
	dbConnect();
	global $dbConnection;
	$response = mysqli_multi_query($dbConnection, $query) or die(mysqli_error($dbConnection));
	do {
		$result = mysqli_store_result($dbConnection);
		if(!$result) continue;
		$rows = mysqli_affected_rows($dbConnection);
		mysqli_free_result($result);
	} while (mysqli_next_result($dbConnection));
	return $response;
}

function dbFetch($results){
	if(!($results instanceof \mysqli_result)) return NULL;
	while($row = $results->fetch_assoc()){
		$rows[] = $row;
	}
	return isset($rows) ? $rows : NULL;
}

function dbAffectedRows(){
	dbConnect();
	global $dbConnection;
	return mysqli_affected_rows($dbConnection);
}

function dbInsertId(){
	global $dbConnection;
	return mysqli_insert_id($dbConnection);
}

function createJSON($value){
	return json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
}

function dbEscapeString($subject){
	dbConnect();
	global $dbConnection;	
	return mysqli_real_escape_string($dbConnection, $subject);
}

function dbClose(){
	global $dbConnection;
	if(!is_object($dbConnection)) return;
	mysqli_close($dbConnection);
}

function postString($key){
	return filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
}

function postInteger($key){
	return filter_var(filter_input(INPUT_POST, $key, FILTER_SANITIZE_NUMBER_INT), FILTER_VALIDATE_INT);
}

function getString($key){
	return filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
}

function getInteger($key){
	return filter_var(filter_input(INPUT_GET, $key, FILTER_SANITIZE_NUMBER_INT), FILTER_VALIDATE_INT);
}

function createGuid(){
	$microTime = microtime();
	list($a_dec, $a_sec) = explode(" ", $microTime);

	$dec_hex = dechex($a_dec* 1000000);
	$sec_hex = dechex($a_sec);

	ensure_length($dec_hex, 5);
	ensure_length($sec_hex, 6);

	$guid = "";
	$guid .= $dec_hex;
	$guid .= createGuidSection(3);
	$guid .= '-';
	$guid .= createGuidSection(4);
	$guid .= '-';
	$guid .= createGuidSection(4);
	$guid .= '-';
	$guid .= createGuidSection(4);
	$guid .= '-';
	$guid .= $sec_hex;
	$guid .= createGuidSection(6);

	return $guid;

}

function createGuidSection($characters) {
	$return = "";
	for($i=0; $i<$characters; $i++)
	{
		$return .= dechex(mt_rand(0,15));
	}
	return $return;
}

dbConnect();
