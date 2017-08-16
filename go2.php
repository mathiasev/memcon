<?php 

/* ---- Image URL submitted by user ---- */
$req_image_uri = (isset($_POST['url'])) ? $_POST['url'] : null; 

/* ---- Load Database ---- */
$mysqli = new mysqli('localhost', 'generaluser', 'generalpass', 'memcon');
if ($mysqli->connect_errno) { 
    echo 'Error: Failed to make a MySQL connection.';
    exit;
}

/* ---- Set up CURL ---- */


/* ---- If image isn't registered ---- */
if(!imageExists($req_image_uri)):
    /* 1. Ask Googe for image details */
    $imageDetails = getImageDetails($req_image_uri);
endif;

/* ---- Get Image Details from Google ---- */
function getImageDetails($req_image_uri) {
    /* 

}


/* ---- Check if Image is registered ---- */
function imageExists($req_image_uri) {
    /* 1. See if image is in DB */
    $sql = 'SELECT id FROM images WHERE imageURI = "' . $req_image_uri . '"';
    if (!$result = $mysqli->query($sql)) { echo "Error searching for image."; exit;}
    $image = $result->fetch_assoc();
    $imageExists = (isset($image['imageURI'])) ? true : false;
    return $imageExists;
}