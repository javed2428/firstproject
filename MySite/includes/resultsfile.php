<?php

require_once 'includes.php';
//query Elastic Search

if (isset($_POST['query'])) {//means home.php form is submitted

    $query = $_POST['query'];
    $start_index = 0;
} else if(isset($_GET['new_query'])){ //means filtered form is submitted and post(query) == get(new_query) it's just to return original post(query) via get in filtered form
    $query = $_GET['new_query'];
    $start_index = 0;
} else {  //means next page link is clicked for pagination
    $query = $_GET['query'];
    $start_index = $_GET['start_index']; //offset for pagination
}


$brands_to_filter=0; //brand decides whether brand filter is included or not
$range=0; //range decides whether range filter is included or not

if(isset($_GET['new_query']) || ($_GET['type']==2)){ //do a filtered query
    $type=2; //type decides whether it's normal or filtered query
    if(isset($_POST['brand']) || (isset($_GET['brands_to_filter']) && is_string($_GET['brands_to_filter']))){

        if(isset($_POST['brand'])){
            $brands_to_filter = implode(',',$_POST['brand']);

            $brands_to_filter = urlencode($brands_to_filter);
            //var_dump($brands_to_filter);
            $brands_to_filter_array = $_POST['brand'];
            //echo "<p>Brands : {$brand}</p><br />";
        }else{
            $brands_to_filter = urldecode($_GET['brands_to_filter']);
            $brands_to_filter_array = explode(',',$brands_to_filter);
            //echo "In Else Part <br />";
            //var_dump($brands_to_filter);
        }
        $results = $esObject->brandFilteredQuery($query, 'title', $brands_to_filter_array);
    }
    if(isset($_POST['range']) || ($_GET['range'] != 0)){
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
        if($lower_bound == 20000){ $upper_bound += 100000;}
        $results = $esObject->rangeFilteredQuery($query, 'title', $lower_bound, $upper_bound);
    }

}else { //do a normal query
    $type=1;
    $results = $esObject->matchQuery($query, 'title');
}
$allBrands = ElasticSearch::getAllBrands($results);



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