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
     $check['checkDate'] = strtotime( "+".$offset." day".$startDate." ".leadZero(rand(6,22)).":".leadZero(rand(0,59)).":".leadZero(rand(0,59)));
     $check['checkAmount'] = rand(80,150);
     file_put_contents( "prod.txt", $check['checkNumber']."\t".$check['checkPos']."\t".date("Y-m-d H:i:s" , $check['checkDate'] )."\t".$check['checkAmount']."\n" , FILE_APPEND );
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
    switch ($k) {
    case 1:
        date_default_timezone_set( 'Etc/GMT-8' );
        break;
    case 2:
        date_default_timezone_set( 'Etc/GMT' );
        break;
    case 3:
        date_default_timezone_set( 'Etc/GMT+8' );
        break;
    }

     for ( $i=0;$i<$days;$i++ )
       {
          $cnt = rand(400,800);
          //$cnt = rand(1,2);
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
