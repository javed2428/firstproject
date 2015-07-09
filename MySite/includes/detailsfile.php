<?php
require_once 'includes.php';

$doc = $redisHashObject->getDetailsById($_GET['id']);
if ($doc == NULL) {//then query ES and store document in redis as well

    //$params['body']['query']['match']['_id'] = $_GET['id'];
    $results = $esObject->matchQuery($_GET['id'], 'asin');
    $doc = $results['hits']['hits'][0]['_source'];

    //$result = $client->search($params);  //result is all the info about the product
    //$doc = $result['hits']['hits'][0]['_source'];
//store document  in redis
   // $jsonString = json_encode($doc);
   $redisHashObject->setDetailsById($_GET['id'], $doc);
}


$title = $doc['title'];
$prod_desc = $doc['prod_desc'];
$link = $doc['link'];
$img_src = $doc['img_src'];
$price = $doc['price'];
?>