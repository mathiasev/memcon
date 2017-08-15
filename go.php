<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);


function postURL() {
$req_key = 'AIzaSyCzu8cvbYjW4Q5HOTO1kB18ZQ3oH6o_I98';
$req_url = 'https://vision.googleapis.com/v1/images:annotate?key=' . $req_key;


$req_body = '{
  "requests":[
    {
      "image":{
        "source":{
          "imageUri":
            "' . $_POST['url'] . '"
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
$domains = getDomains($output);
$len = count($domains['domains']);
$html .= '<table class="table"><thead><tr><th>Domain</th><th>Count</th><th>Value</th><th>Total</th></tr></thead><tbody>';
for ($i = 0; $i < $len; $i++) :
	$html .= '<tr><td>' . $domains['domains'][$i] . '</td><td id="' . $i . '-qty">' . $domains['count'][$i] . '</td><td><input type="number" value="0.00" id="' . $i . '-value"></td><td id="' . $i . '-total"></th></tr>';
endfor;
$html .= '</tbody><tfoot><tr><td></td><td>' . $len . '</td><td></td><td id="tot-val"></td></tr></tfoot></table>';
return $html;
}

function getDomains($output) {
$data = json_decode($output);

$domains = array();	$count = array();
$sqlStr = "";
$fullMatches = $data->responses[0]->webDetection->fullMatchingImages;
foreach ($fullMatches as $match) :
	$domainArr = explode('/', $match->url);
	$url = $domainArr[0] . '//' . $domainArr[2] . '</br>';
	$pos = array_search($url, $domains);
	if($pos === FALSE):
		$domains[] = $url;
		$sqlStr += '("' . $url .'"),';
		$pos = array_search($url, $domains);
		$count[$pos] = 1;
	else:
		$count[$pos] ++;
	endif;
endforeach;

/* Short SQL */
$mysqli = new mysqli('localhost', 'generaluser', 'generalpass', 'memcon');
if ($mysqli->connect_errno) {
    echo "Error: Failed to make a MySQL connection, here is why: \n";
    echo "Errno: " . $mysqli->connect_errno . "\n";
    echo "Error: " . $mysqli->connect_error . "\n";
    exit;
}

$sql = "INSERT IGNORE INTO domains (domainURI) VALUES " . substr($sqlStr,0,-1);
echo $sql;
if (!$result = $mysqli->query($sql)) {
    echo "Sorry, the website is experiencing problems.";
    exit;
}

$result->free();
$mysqli->close();


return array('domains' => $domains, 'count' => $count);
}

echo postURL();
?>