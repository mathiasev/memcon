<?php 

/* ---- Image URL submitted by user ---- */
$req_image_uri = (isset($_POST['url'])) ? $_POST['url'] : null; 

/* ---- Load Database ---- */
$mysqli = new mysqli('localhost', 'generaluser', 'generalpass', 'memcon');
if ($mysqli->connect_errno) { 
    echo 'Error: Failed to make a MySQL connection.';
    exit;
}

/* ---- Check if Image is registered ---- */
checkImageExists($req_image_uri);

function checkImageExists($req_image_uri) {
    /* 1. See if image is in DB */
    $sql = 'SELECT id FROM images WHERE imageURI = "' . $req_image_uri . '"';
    if (!$result = $mysqli->query($sql)) { echo "Error searching for image.";
    exit;
}

}