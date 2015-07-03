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
$hash = "hash_amazon";

$jsonString = $redis->hget($hash, $_GET['id']);
if ($jsonString == NULL) {//then query ES and store document in redis as well
    $params['index'] = $index;
    $params['type'] = 'docs';
    $params['body']['query']['match']['_id'] = $_GET['id'];
    $result = $client->search($params);  //result is all the info about the product
    $doc = $result['hits']['hits'][0]['_source'];
//store document  in redis
    $jsonString = json_encode($doc);
    $redis->hset($hash, $_GET['id'], $jsonString);
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
echo '<p>Price :' . $price . ' </p>';//price
echo '<p><a href="' . $link . '">Buy Now</a></p>';//buy now from amazon
echo '<h3>Product Description</h3>';
echo '<p>' . $prod_desc . ' </p>';//product description

echo '<h3>Technical Details</h3>';
echo '<ul>';

foreach ($doc as $key => $value) {
    if(($key == 'title' ) || ($key == 'link' ) || ($key == 'prod_desc' ) || ($key == 'img_src' ) || ($key == 'price' ) || ($key == '' ))
        continue ;
    echo "<li>{$key} : {$value}</li>";
}
echo '</ul>';





?>
</body>
</html>