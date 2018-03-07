<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

function leadZero( $str )
  {
     if ( strlen($str) == 1 )
       {
          $str = "0".$str;
       }
     return $str;
  }

function makeCheck( $checkNumber, $checkPos, $startDate, $offset )
  {
     $check = array();
     $check['checkNumber'] = $checkNumber;
     $check['checkPos'] = $checkPos;
     $check['checkDate'] = date( 'Y-m-d', strtotime( "+".$offset." day".$startDate))." ".leadZero(rand(6,22)).":".leadZero(rand(0,59)).":".leadZero(rand(0,59));
     $check['checkAmount'] = rand(80,150);
     return json_encode( $check );
  }

$brocker_node = 'localhost';
$brocker_port = 5672;
$brocker_user = 'guest';
$brocker_pass = 'guest';
$brocker_queue = 'checku';

$connection = new AMQPStreamConnection( $brocker_node, $brocker_port, $brocker_user, $brocker_pass );

if ( ! $channel = $connection->channel() )
  {
     throw new Exception( implode( " ", error_get_last() ) );
  }
$channel = $connection->channel();

$days = 366;
$startDate = '2016-01-01';

//make test
for ( $k=1;$k<=3;$k++ )
  {
     for ( $i=0;$i<$days;$i++ )
       {
          $cnt = rand(400,800);
          for ( $j=1; $j<=$cnt; $j++ )
            {
               $message = new AMQPMessage( makeCheck( $j, $k, $startDate, $i ) );
               $channel->basic_publish($message, '', 'checku');
            }
       }
  }

$channel->close();
$connection->close();

?>