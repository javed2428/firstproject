<html>
<head>
    <title>Results Page</title>
</head>

<body>
<?php

require '../vendor/autoload.php';

$client = new Elasticsearch\Client();

//query Elastic Search

if (isset($_POST['query'])) {

    $query = $_POST['query'];
    $start_index = 0;
    unset($_POST['query']);
} else {
    $query = $_GET['query'];
    $start_index = $_GET['start_index']; //offset for pagination

}

$params['index'] = 'amazon';
$params['type'] = 'docs';

$params['body']['size'] = 240;


//$params['body']['from'] = $start_index;
$params['body']['query']['match']['title']['query'] = $query;
$params['body']['query']['match']['title']['minimum_should_match'] = "50%";

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


//start Displaying Items and Description Using Pagination
for ($i = $start_index; $i <= $end_index; $i++) {
    $id = $results['hits']['hits'][$i]['_id'];
    $doc = $results['hits']['hits'][$i]['_source'];
    $title = $doc['title'];
    $prod_desc = $doc['prod_desc'];
    echo '<p><a class="item-links" href="details.php?id=' .$id  . '">' . $title . '</a></p>';

    echo '<p>' . $prod_desc . '</p>';
    echo '<br />';


}



if ($end_index < ($total - 1)) {//means it's not the last page
    $page_num = ($end_index + 1) / 10;
    $end_index++;
    echo '<a href="results.php?' . "start_index={$end_index}"."&query={$query}" . '">Next</a>';
}


?>

</body>
</html>