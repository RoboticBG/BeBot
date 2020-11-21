<?php
$tmp = file_get_contents('/dev/shm/sensor.txt');
$vals=explode("=",$tmp);
echo $vals[1];

?>