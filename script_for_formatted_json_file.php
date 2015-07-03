ice<?php
$jsonString = file_get_contents('./amazon-crawler/new_output.json');
$data_arr = json_decode($jsonString,true);


$count = 1;

$new_arr=[];
foreach($data_arr as $document) {

    $arr = ['index'=>['_id'=>"{$count}"]];
    $new_arr[] = $arr;

    foreach($document['tech_details'] as $key=>$value){
        $document[$key] = $value;
    }
    unset($document['tech_details']);
    $document['price'] = str_replace(',','',$document['price']);
    $new_arr[] = $document;
    $count++;
}

//for ($data_arr as $data_temp) {
//    json_encode($data_temp);
//}

$newJsonString = json_encode($new_arr);

$newJsonString = str_replace('},{"tech_details', '}'."\n".'{"tech_details', $newJsonString); //to change the format of file to be exported to ES
$newJsonString = str_replace('},{"index"', '}'."\n".'{"index"', $newJsonString); //to change the format of file to be exported to ES
$newJsonString = str_replace('[{"index"','{"index"',$newJsonString);
$newJsonString = str_replace('}]','}'."\n ",$newJsonString);
$newJsonString = str_replace('},{"prod_desc"', '}'."\n".'{"prod_desc"',$newJsonString);


$newJsonString = str_replace('"price":[', '"price":',$newJsonString);
$newJsonString = str_replace('],"link"', ',"link"',$newJsonString);


file_put_contents('latest_formatted_output.json',$newJsonString);

?>