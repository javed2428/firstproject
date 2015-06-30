<html>
<head>
    <title>Details of the product</title>
</head>
<body>
<?php
//initialize ES
require '../vendor/autoload.php';
$client = new Elasticsearch\Client();

//Initialize Predis
require "../predis/autoload.php";
Predis\Autoloader::register();
$redis = new Predis\Client();


$index = "amazon";

$jsonString = $redis->hget($index, $_GET['id']);
if ($jsonString == NULL) {//then query ES and store document in redis as well
    $params['index'] = $index;
    $params['type'] = 'docs';
    $params['body']['query']['match']['_id'] = $_GET['id'];
    $result = $client->search($params);  //result is all the info about the product
    $doc = $result['hits']['hits'][0]['_source'];
//store document  in redis
    $jsonString = json_encode($doc);
    $redis->hset($index, $_GET['id'], $jsonString);
} else {
    $doc = json_decode($jsonString, true);
}


$title = $doc['title'];
$prod_desc = $doc['prod_desc'];
$link = $doc['link'];
$img_src = $doc['img_src'];
$price = $doc['price'];
$tech_details = $doc['tech_details'];


echo '<h1><a href="' . $link . '">' . $title . '</a></h1>';//Title
echo '<p><a href="' . $img_src . '"><img src="' . $img_src . '"></a></p>';//image
echo '<p>Price :' . $price[0] . ' </p>';//price
echo '<p><a href="' . $link . '">Buy Now</a></p>';//buy now from amazon
echo '<h3>Product Description</h3>';
echo '<p>' . $prod_desc . ' </p>';//product description

echo '<h3>Technical Details</h3>';
echo '<ul>';
$count = 0;
foreach ($tech_details as $key => $value) {
    if ($count != 1) {//this is b'coz the second element in tech details array is in hex
        echo '<li>' . $key . ' : ' . $value . '</li>';
    }
    $count++;
}






?>
</body>
</html>