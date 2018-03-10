<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$brocker_node = "localhost";
$brocker_port = 5672;
$brocker_user = "guest";
$brocker_pass = "guest";
$brocker_queue = "checku";

$mongoUri = "mongodb://127.0.0.1:27017/";

$mongoManager = new MongoDB\Driver\Manager( $mongoUri );

$connection = new AMQPStreamConnection( $brocker_node, $brocker_port, $brocker_user, $brocker_pass );

if ( ! $channel = $connection->channel() )
  {
     throw new Exception( implode( " ", error_get_last() ) );
  }
$channel = $connection->channel();

$callback = function( $msg ) use( $mongoManager ) {
   $tmp = json_decode( $msg->body, true );
   $tmp['checkDate'] = new MongoDB\BSON\UTCDateTime( $tmp['checkDate']*1000 );
   $mongoBulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
   $mongoBulk->insert( $tmp );
   $mongoManager->executeBulkWrite( 'checku.check', $mongoBulk );
};

$channel->basic_consume( $brocker_queue, '', false, true, false, false, $callback );
while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

?>
