<?php

/* Create a new connection */
$connection = new AMQPConnection();

// set the login details
$connection->setHost('127.0.0.1');
$connection->setPort('5672');
$connection->setLogin('guest');
$connection->setPassword('guest');


if ($connection->connect()) {
    echo "Established a connection to the broker\n";
}
else {
    echo "Cannot connect to the broker\n";
}

$channel = new AMQPChannel( $connection );
$exchange = new AMQPExchange( $channel );
$exchange->setName('checku') ;
$exchange->setType( AMQP_EX_TYPE_DIRECT );
$exchange->setFlags( AMQP_DURABLE );
$exchange->declare();

for ( $i=0;$i<100;$i++ )
  {
     $exchange->publish( 'john' );
  }
?>
