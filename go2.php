<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ---- Image URL submitted by user ---- */
$req_image_uri = (isset($_POST['url'])) ? $_POST['url'] : null; 

/* ---- Load Database ---- */
$mysqli = new mysqli('localhost', 'generaluser', 'generalpass', 'memcon');
if ($mysqli->connect_errno) { 
    echo 'Error: Failed to make a MySQL connection.';
    exit;
}

/* ---- Set up CURL ---- */
function getCURL($req_body, $req_url) {
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $req_url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $req_body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($req_format_body)) );
        $output = curl_exec($ch); 
        curl_close($ch);
    return $output;
}



/* ---- If image isn't registered ---- */
if(!imageExists($req_image_uri)):
    /* 1. Ask Googe for image info */
    $imageInfo = getImageInfo($req_image_uri);
endif;

getMemcon($req_image_uri);



/* ---- Get Image Info from Memcon ---- */
function getMemcon($req_image_uri) {
	global $mysqli;
	
	/* 1. Get image info from DB */
	$sql = 'SELECT `imgURI` FROM `images` WHERE `imgURI` = "' . $req_image_uri . '";';
	$result = $mysqli->query($sql);
    if (!$result = $mysqli->query($sql)) { echo "Error searching for image."; exit;}
    $image = $result->fetch_assoc();
    echo '<img src="'. $image['imgURI'] .'">';
}


/* ---- Get Image Info from Google ---- */
function getImageInfo($req_image_uri) {
    /* Set Google Details */
    require_once('/var/www/apikey.php');
    $req_url = 'https://vision.googleapis.com/v1/images:annotate?key=' . $apikey;
}


/* ---- Check if Image is registered ---- */
function imageExists($req_image_uri) {
    global $mysqli;
    /* 1. See if image is in DB */
    $sql = 'SELECT `imgURI` FROM `images` WHERE `imgURI` = "' . $req_image_uri . '";';
    $result = $mysqli->query($sql);
    if (!$result = $mysqli->query($sql)) { echo "Error searching for image."; exit;}
    $image = $result->fetch_assoc();
    $imageExists = (isset($image['imgURI'])) ? true : false;
    return $imageExists;
}