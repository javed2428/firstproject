<?php

require_once 'includes.php';
//query Elastic Search

if (isset($_POST['query'])) {//means home.php form is submitted

    $query = $_POST['query'];
    $start_index = 0;
} else if(isset($_GET['new_query'])){ //means filtered form is submitted
    $query = $_GET['new_query'];
    $start_index = 0;
} else {  //means next page link is clicked for pagination
    $query = $_GET['query'];
    $start_index = $_GET['start_index']; //offset for pagination

}


$params['index'] = 'amazon';
$params['type'] = 'docs';

$params['body']['size'] = 240;


$brand=0; //brand decides whether brand filter is included or not
$range=0; //range decides whether range filter is included or not
if(isset($_GET['new_query']) || ($_GET['type']==2)){ //do a filtered query
    $type=2; //type decides whether it's normal or filtered query
    $brand = $query;
    if(isset($_POST['brand']) || ($_GET['brand']==1)){
        //make query logic
        //make a brand filter
        if(isset($_POST['brand'])){
            $brand='';
            foreach($_POST['brand'] as $string) {
                $brand .= ' ';
                $brand .= $string;
            }
            //echo "<p>Brands : {$brand}</p><br />";
            unset($_POST['brand']);
        }else{
            $brand = $_GET['brand'];
            unset($_GET['brand']);
        }
        //$brand=1;
        $params['body']['query']['filtered']['query']['bool']['must']=[['match'=>["Brand"=>$brand]],['match'=>['title'=>$query]]];


    }
    if(isset($_POST['range']) || isset($_GET['range'])){
        //make range logic
        if(isset($_POST['range'])){
            $range = $_POST['range'];
            unset($_POST['range']);
        } else{
            $range = $_GET['range'];
            unset($_GET['range']);
        }
        //make a range filter
        //$range=1;
        $lower_bound = (($range/2)-5)*1000;
        $upper_bound = $lower_bound + 10000;


        $params['body']['query']['filtered']['filter']['range']['price']['gte']=$lower_bound;
        $params['body']['query']['filtered']['filter']['range']['price']['lte']=$upper_bound;

    }

}else { //do a normal query
    $type=1;
    $params['body']['query']['match']['title']['query'] = $query;
    $params['body']['query']['match']['title']['minimum_should_match'] = "50%";
}
$results = $client->search($params);



//$total_docs = $results['hits']['total'];
//$milliseconds = $results['took'];
//$maxScore     = $results['hits']['max_score'];
//$score = $results['hits']['hits'][0]['_score'];
//$doc1   = $results['hits']['hits'][0]['_source']; //$doc is an associative array
//$doc2 = $results['hits']['hits'][$total - 1]['_source'];


//set start and end indices for results page(Pagination)

$end_index = $start_index + 9;

$total = $results['hits']['total'];
$end_index = (($end_index < ($total - 1)) ? $end_index : ($total - 1)); //to check if $end_index doesn't exceeds $total-1