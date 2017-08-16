<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);
/* Short SQL */
$mysqli = new mysqli('localhost', 'generaluser', 'generalpass', 'memcon');
if ($mysqli->connect_errno) {
    echo "Error: Failed to make a MySQL connection, here is why: \n";
    echo "Errno: " . $mysqli->connect_errno . "\n";
    echo "Error: " . $mysqli->connect_error . "\n";
    exit;
}

function postURL() {
	global $mysqli;
$req_key = 'AIzaSyCzu8cvbYjW4Q5HOTO1kB18ZQ3oH6o_I98';
$req_url = 'https://vision.googleapis.com/v1/images:annotate?key=' . $req_key;
$req_img_uri = $_POST['url'];

$req_body = '{
  "requests":[
    {
      "image":{
        "source":{
          "imageUri":
            "' . $req_img_uri . '"
        }
      },
      "features":[
       {
          "type": "WEB_DETECTION",
          "maxResults": 200
        }
      ]
    }
  ]
}';

$req_format_body = $req_body;
//pr($req_format_body);

        // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $req_url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POST,1);

		curl_setopt($ch, CURLOPT_POSTFIELDS, $req_format_body );
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
    'Content-Length: ' . strlen($req_format_body))                                                                       
);                                                                                                                   


        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   
//pr($output);

$html = '';
$domains = getDomains($req_img_uri, $output);
$len = count($domains['domains']);
$html .= '<table class="table"><thead><tr><th>Domain</th><th>Count</th><th>Value</th><th>Total</th></tr></thead><tbody>';
for ($i = 0; $i < $len; $i++) :
	$html .= '<tr><td>' . $domains['domains'][$i] . '</td><td id="' . $i . '-qty">' . $domains['count'][$i] . '</td><td><input type="number" value="0.00" id="' . $i . '-value"></td><td id="' . $i . '-total"></th></tr>';
endfor;
$html .= '</tbody><tfoot><tr><td></td><td>' . $len . '</td><td></td><td id="tot-val"></td></tr></tfoot></table>';
return $html;
}

function getDomains($req_img_uri, $output) {
	global $mysqli;
$data = json_decode($output);

$domains = array();	$count = array();
$sqlStr = "";
$fullMatches = $data->responses[0]->webDetection->fullMatchingImages;
foreach ($fullMatches as $match) :
	$domainArr = explode('/', $match->url);
	$url = $domainArr[0] . '//' . $domainArr[2];
	$pos = array_search($url, $domains);
	if($pos === FALSE):
		$domains[] = $url;
		$pos = array_search($url, $domains);
		$count[$pos] = 1;
	else:
		$count[$pos] ++;
	endif;
endforeach;

//sendSQL($req_img_uri, $domains, $count);

//return array('domains' => $domains, 'count' => $count);


/* If image != exists */
$sql = 'INSERT INTO images (imgURI, imageDomainsCount) VALUES ("' . $req_img_uri . '", \'' . str_replace('"', '\\"',json_encode(array($domains, $count))) . '\');';
//echo $sql;
if (!$result = $mysqli->query($sql)) {
    echo "Sorry, could not create image.";
    exit;
}
$sqlStr= '';
//print_r($domains);
$countVal = count($count);
for ($i = 0; $i < $countVal; $i ++):
$sqlStr .= '(\'' . $domains[$i] . '\'), ';
endfor;

$sql = "INSERT IGNORE INTO domains (domainURI) VALUES " . substr($sqlStr,0,-2) . ';';
//echo $sql;

if (!$result = $mysqli->query($sql)) {
    echo "Sorry, could not insert domains.";
    exit;
}

$countVal = count($count);
for ($i = 0; $i < $countVal; $i++):
 $sql = 'UPDATE domains SET domainImagesIndexed = domainImagesIndexed + ' . $count[$i] . ' WHERE domainURI = "' .$domains[$i] . '";';
if (!$result = $mysqli->query($sql)) {
    echo "Couldn't update " . $domain;
}

endfor;

}

function sendSQL($imageURI) {
	
	global $mysqli;

/* Check if Image Exists */
$sql = 'SELECT * FROM images WHERE imgURI = "' . $imageURI . '";';

if (!$result = $mysqli->query($sql)) {
    echo "BAD SQL.";
	exit;
}
$result = $result->fetch_assoc();

if(isset($result['imgURI']) && $result['imgURI'] == $imageURI):
/* IF image exists */
echo '<div class="row"><div class="col-md-6">';
echo '<img src="' . $imageURI . '" class="img-thumbnail">';
echo '<p><small>Image URL: ' . $imageURI . '</small></p>';
echo '<p><small>Added on: ' . $result['imageAdded'] . '<small></p>';
echo '<p><small>Last Updated: ' . $result['lastUpdate'] . '<small></p></div><div class="col-md-6">';
echo '<h3>Image Value: ' . $result['imageValue'] . '</h3></div></div>';
$domains = json_decode($result['imageDomainsCount']);
$len = count($domains[0]);
$html = '<div class="row"><div class="col-md-8 col-md-offset-2"><table class="table"><thead><tr><th>Domain</th><th>Count</th><th>Value</th><th>Total</th></tr></thead><tbody>';
for ($i = 0; $i < $len; $i++) :
	$html .= '<tr><td>' . $domains[0][$i] . '</td><td id="' . $i . '-qty">' . $domains[1][$i] . '</td><td><input type="number" value="0.00" id="' . $i . '-value"></td><td id="' . $i . '-total"></th></tr>';
endfor;
$html .= '</tbody><tfoot><tr><td></td><td>' . $len . '</td><td></td><td id="tot-val"></td></tr></tfoot></table>';
echo $html;
echo '</div></div>';

elseif (!isset($result['imgURI']) && $result['imgURI'] != $imageURI):

	postURL();
	sendSQL($imageURI);
endif;


}

echo sendSQL($_POST['url']);
$mysqli->close();

?>