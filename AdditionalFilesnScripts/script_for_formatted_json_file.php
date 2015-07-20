<?php
/*
$jsonString = file_get_contents('tempOutput.json');
$data_arr = json_decode($jsonString,true);

foreach($data_arr as $document) {

    $arr = ['index'=>['_id'=>"{$document['asin']}"]];
    foreach($document['tech_details'] as $key=>$value){
        $document[$key] = $value;
    }
    unset($document['tech_details']);
    unset($document['Brand']);
    $document['price'] = str_replace(',','',$document['price']);
    if(is_array($document['price'])){
        $document['price'] = implode('',$document['price']);
    }
    $jsonString = json_encode($arr);
    $jsonString .= "\n".json_encode($document);
    file_put_contents('123.json',$jsonString."\n",FILE_APPEND);
}
*/
require '../MySite/includes/includes.php';
$sourceFileHandle = fopen('tempOutput.json','r') or die('unable to open source file');
$destinationFileHandle = fopen('formattedOutput.json','w') or die('unable to open destination file');

while (($line = fgets($sourceFileHandle)) !== false) {
    // process the line read.
    //$line = rtrim($line, ",");
    $line = substr($line, 0, -2);
    //echo $line."\n";
    $document = json_decode($line, true);
    /*if(!is_array($document) ){
        echo $line;
        die(22);
    }*/

//    print_r($document);
//    die('Javed');
    $arr = ['index'=>['_id'=>"{$document['asin']}"]];
    foreach($document['tech_details'] as $key=>$value){
        $document[$key] = $value;
    }
    unset($document['tech_details']);
    unset($document['Brand']);
    $document['price'] = str_replace(',','',$document['price']);
    if(is_array($document['price'])){
        $document['price'] = implode('',$document['price']);
    }

    $esObject->setDetailsById($document['asin'], $document);

//    $jsonString = json_encode($arr);
//    $jsonString .= "\n".json_encode($document)."\n";
//    fwrite($destinationFileHandle, $jsonString);

}

fclose($sourceFileHandle);
fclose($destinationFileHandle);

?>