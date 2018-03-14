<?php

$mongoUri = "mongodb://127.0.0.1:27017/";
$mongoManager = new MongoDB\Driver\Manager( $mongoUri );

$startPeriod = new MongoDB\BSON\UTCDateTime( strtotime( date("Y-m-d 00:00:00",strtotime($_GET['mydate']) ) )*1000 );
$endPeriod = new MongoDB\BSON\UTCDateTime( strtotime( date("Y-m-t 23:59:59",strtotime($_GET['mydate']) ) )*1000 );

$summary = new MongoDB\Driver\Command([
    'aggregate' => 'check',
    'pipeline' => [
       [ '$match' => [ 'checkDate' => [ '$gte' => $startPeriod, '$lte'=> $endPeriod ] ] ],
       [ '$group' => [ '_id' => [ 'checkPos' => '$checkPos', 'month' => [ '$dateToString' => ['format' => '%Y-%m-%d', 'date' => '$checkDate', 'timezone' => '+03' ] ] ],'total' => [ '$sum' => '$checkAmount' ] ] ],
       [ '$sort' => [ '_id' => 1 ] ]
    ],
    'cursor' => new stdClass,
]);

$cursor = $mongoManager->executeCommand( 'checku', $summary );

$json = '[';

foreach ( $cursor as $document ) {
    $json = $json.json_encode( $document ).",";
}
$json = preg_replace("/,$/","",$json);
$json = $json.']';

print $json."\n";

?>
