<?php
$jsonString = file_get_contents('./amazon-crawler/output.json');
$data_arr = json_decode($jsonString);
#echo "<pre>";
#print_r($data_arr);
#echo "</pre>";
$count = 1;

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



?>