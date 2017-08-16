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
    /* 1. Ask Googe for image details */
    $imageDetails = getImageDetails($req_image_uri);
endif;

/* ---- Get Image Details from Google ---- */
function getImageDetails($req_image_uri) {
    /* Set Google Details */
    require_once('/usr/ubuntu/apikey.php');
    $req_key ;
$req_url = 'https://vision.googleapis.com/v1/images:annotate?key=' . $req_key;


}


/* ---- Check if Image is registered ---- */
function imageExists($req_image_uri) {
    global $mysqli;
    /* 1. See if image is in DB */
    $sql = 'SELECT id FROM images WHERE imageURI = "' . $req_image_uri . '";';
    $result = $mysqli->query($sql);
    print_r($result);
    if (!$result = $mysqli->query($sql)) { echo "Error searching for image."; exit;}
    $image = $result->fetch_assoc();
    $imageExists = (isset($image['imageURI'])) ? true : false;
    return $imageExists;
}