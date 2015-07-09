<?php
define('ROOT','/var/www/html/firstproject/MySite');
define('DS','/');
//initialize ES
require ROOT.DS.'..'.DS.'vendor/autoload.php';
//Initialize Predis
require ROOT.DS.'..'.DS."predis/autoload.php";
Predis\Autoloader::register();


$index = 'amazon';
$type = 'docs';
$size = '240';
class ElasticSearch {
    public $index;
    public $type;
    public $size;
    public $params;
    public $results;
    public $client;

    public function __construct($ind, $typ, $siz) {
        $this->index = $ind;
        $this->type = $typ;
        $this->size = $siz;
        $this->client = new Elasticsearch\Client();
        //$this->client = new Elasticsearch\Client();
        $this->params['index'] = $this->index;
        $this->params['type'] = $this->type;
        $this->params['size'] = $this->size;
    }
    public function matchQuery($query, $field) {
        unset($this->params['body']);//this is done to unset params['body'] b'coz it was set by any previous query
        $this->params['body']['query']['match'][$field]['query'] = $query;
        $this->params['body']['query']['match'][$field]['minimum_should_match'] = "50%";
        $this->results = $this->client->search($this->params);
        return $this->results;
    }
    public function brandFilteredQuery($query, $field, $brands_arr){
        unset($this->params['body']);//this is done to unset params['body'] b'coz it was set by any previous query
        $this->params['body']['query']['filtered']['query']['match'][$field] = $query;
        $this->params['body']['query']['filtered']['filter']['terms']['brnd'] = $brands_arr;
        $this->results = $this->client->search($this->params);
        return $this->results;
    }
    public function rangeFilteredQuery($query, $field, $gte, $lte){
        unset($this->params['body']);//this is done to unset params['body'] b'coz it was set by any previous query
        $this->params['body']['query']['filtered']['query']['match'][$field] = $query;
        $this->params['body']['query']['filtered']['filter']['range']['price']['gte'] = $gte;
        $this->params['body']['query']['filtered']['filter']['range']['price']['lte'] = $lte;
        $this->results = $this->client->search($this->params);
        return $this->results;
    }

    public static function getAllBrands($results){
        $res_arr = $results['hits']['hits'];
        $brands_arr = [];
        foreach($res_arr as $item){
            $brands_arr[] = $item['_source']['brnd'];
        }
        $brands_arr = array_count_values($brands_arr);
        arsort($brands_arr);
        return $brands_arr;

    }
}
$esObject = new ElasticSearch($index, $type, $size);

//$results = $esObject->matchQuery("Apple","brnd",$client);
//var_dump($results);

//$brands_arr = ['Apple','Samsung'];
//$results = $esObject->brandFilteredQuery("tab tablets tablet ipad", "title", $brands_arr, $client);
//var_dump($results);

//$results = $esObject->rangeFilteredQuery("tab tablets tablet ipad", "title", 0, 10000, $client);
//var_dump($results);

//$allBrands = $esObject->getAllBrands($client);
//var_dump($allBrands);

$hashmap = 'amazonHashMap';
class redisHashClass {
    public $redisClient;
    public $hashmap;

    public function __construct($hash) {
        $this->redisClient = new Predis\Client();
        $this->hashmap = $hash;
    }

    public function getDetailsById($id ) {//returns associative array of document using json_decode(if found) else NULL
        $jsonString =  ($this->redisClient->hget($this->hashmap, $id));
        if($jsonString == NULL){//means no data in redis
            return NULL;
        } else {
            $doc = json_decode($jsonString, true);
            return $doc;
        }
    }

    public function setDetailsById($id, $document) {//store document in redis Hashmap
        $jsonString = json_encode($document);
        $this->redisClient->hset($this->hashmap, $id, $jsonString);
    }
}
$redisHashObject = new redisHashClass($hashmap);

?>