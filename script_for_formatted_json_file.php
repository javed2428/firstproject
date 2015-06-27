<?php
$jsonString = file_get_contents('./amazon-crawler/output.json');
$data_arr = json_decode($jsonString,true);


$count = 1;

$new_arr=[];
foreach($data_arr as $document){

    $arr = ['index'=>['_id'=>"{$count}"]];
    $new_arr[] = $arr;
    $new_arr[] = $document;
    $count++;
}

$newJsonString = json_encode($new_arr);
$newJsonString = str_replace('},{"price"', '}'."\n".'{"price"', $newJsonString); //to change the format of file to be exported to ES
$newJsonString = str_replace('},{"index"', '}'."\n".'{"index"', $newJsonString); //to change the format of file to be exported to ES

file_put_contents('formatted_output.json',$newJsonString);

?>