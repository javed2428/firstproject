<?php
$jsonString = file_get_contents('newOutput.json');
$data_arr = json_decode($jsonString,true);


$count = 1;

$new_arr=[];
foreach($data_arr as $document) {

    $arr = ['index'=>['_id'=>"{$count}"]];
    foreach($document['tech_details'] as $key=>$value){
        $document[$key] = $value;
    }
    unset($document['tech_details']);
    $document['price'] = str_replace(',','',$document['price']);
    $jsonString = json_encode($arr);
    file_put_contents('formatted_output.json',$jsonString."\n",FILE_APPEND);
    $jsonString = json_encode($document);
    file_put_contents('formatted_output.json',$jsonString."\n",FILE_APPEND);
    $count++;
}

$newJsonString = file_get_contents('formatted_output.json');
$newJsonString = str_replace('"price":[', '"price":',$newJsonString);
$newJsonString = str_replace('],"link"', ',"link"',$newJsonString);
file_put_contents('formatted_output.json',$newJsonString);

?>