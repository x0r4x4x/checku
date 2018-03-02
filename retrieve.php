<?php

/* Create a new connection */
$connection = new AMQPConnection();

// set the login details
$connection->setHost('127.0.0.1');
$connection->setPort('5672');
$connection->setLogin('guest');
$connection->setPassword('guest');


if ( $connection->connect() ) {
    echo "Established a connection to the broker\n";
}
else {
    echo "Cannot connect to the broker\n";
}

//$channel = $connection->channel();
$channel = new AMQPChannel( $connection );
$queue = new AMQPQueue( $channel );
$queue->setName( 'checku' );
$queue->setFlags( AMQP_DURABLE );
$queue->declare();
while(  $message = $queue->get(AMQP_NOPARAM) )
  {
     echo $message->getBody()."\n";
     $queue->ack( $message->getDeliveryTag() ) ;
  }

?>
