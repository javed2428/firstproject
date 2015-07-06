<?php
require_once 'includes.php';

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