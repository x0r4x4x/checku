<?php

$mongoUri = "mongodb://127.0.0.1:27017/";
$mongoManager = new MongoDB\Driver\Manager( $mongoUri );

$summary = new MongoDB\Driver\Command([
    'aggregate' => 'check',
    'pipeline' => [
       [ '$group' => [ '_id' => [ 'checkPos' => '$checkPos', 'month' => [ '$dateToString' => ['format' => '%Y-%m', 'date' => '$checkDate' ] ] ],'total' => [ '$sum' => '$checkAmount' ] ] ],
       [ '$sort' => [ '_id' => 1 ] ]
    ],
    'cursor' => new stdClass,
]);

$cursor = $mongoManager->executeCommand( 'checku', $summary1 );

$json = '[';

foreach ( $cursor as $document ) {
    $json = $json.json_encode( $document ).",";
}
$json = preg_replace("/,$/","",$json);
$json = $json.']';

print $json."\n";

?>
