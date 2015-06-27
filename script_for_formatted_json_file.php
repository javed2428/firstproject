<?php
$jsonString = file_get_contents('./amazon-crawler/output.json');
$data_arr = json_decode($jsonString,true);

//$index = array()
//{"index":{"_id":"1"}}
//arr = array()
//$arr['id'] = "1"
//
//
//{'id':'1'}
//
//$index['index'] = $arr;
//json_encode($index);
// write_to_file()

//{"index":{"_id":"1"}}

$count = 1;

//echo $doc1;
$new_arr=[];
foreach($data_arr as $document){

    $arr = ['index'=>['_id'=>"{$count}"]];
//    $doc1 = json_encode($arr);
//    $value = json_decode($doc1);
    $new_arr[] = $arr;
    $new_arr[] = $document;
    $count++;
}

$newJsonString = json_encode($new_arr);
$newJsonString = str_replace('},{"price"', '}'."\n".'{"price"', $newJsonString);
$newJsonString = str_replace('},{"index"', '}'."\n".'{"index"', $newJsonString);


//echo $newstring;
file_put_contents('formatted_output.json',$newJsonString);

?>