<?php

include_once 'config.php';
include_once 'func.php';

$pdesc = array(
    0 => array('pipe', 'r'), // 0 is STDIN for process
    1 => array('pipe', 'w'), // 1 is STDOUT for process
    2 => array('pipe', 'r'), // 2 is STDERR for process
);

function say($text="1 2 3",$lang="bg",$pitch=10,$gap=7,$speed=170)
{ 
    global $debug;
    $pitch=(int)$pitch;
    $gap=(int)$gap;
    $speed=(int)$speed;
    $cmd="/usr/bin/espeak -g $gap -p $pitch -s $speed -v $lang '".$text."'  > /dev/null 2>&1 ";
    exec($cmd);
    if ($debug) echo($cmd);
}

function show_emoji($param=0,$time=1)
{
    global $debug,$PT,$root_dir;
    $ptype=4;
    if ($time==0) $cmd="sudo /usr/bin/fbi -d /dev/fb0 -T 1 -a -noverbose -nocomments $root_dir/files/emotions/emoji_".(int)$param.".png > /dev/null 2>&1 "; 
    else $cmd="sudo /usr/bin/fbi -d /dev/fb0 -1 -t $time -T 1 -a -noverbose -nocomments $root_dir/files/emotions/emoji_".(int)$param.".png > /dev/null 2>&1 "; 
    exec($cmd); 
    sleep($time);
    exec("sudo /usr/bin/killall -9 /usr/bin/fbi");
    if ($debug) echo($cmd);
}

function play_media($filename, $aspect="stretch",$non_blocking=0)
{
	  global $debug, $conn, $root_dir,$pdesc;
	  $ID= (int)$ID;
	  $media_dir = "";
  	$split_filename = explode(".", $filename);
  	if(end($split_filename) == "mp3") $media_dir = "audio";
  	else if(end($split_filename) == "mp4") $media_dir = "video";
  	
  	$cmd="sudo /usr/bin/omxplayer --aspect $aspect  \"$root_dir/files/$media_dir/$filename\" > /dev/null 2>&1 ";
    if ($non_blocking==1) proc_open($cmd, $pdesc, $pipes); 
    else exec($cmd);	
    return false;
}

//show image, if filename empty clear screen/framebuffer
function show_image($file_name, $apect="stretch")
{
    global $debug, $conn, $root_dir;
  	$cmd="sudo /usr/bin/fbi -d /dev/fb0 -T 1 -a -noverbose -nocomments \"$root_dir/files/images/$file_name\" > /dev/null 2>&1 ";
	  if (!$file_name) $cmd="sudo $root_dir/robo-libs/clr_frame.sh";
	  shell_exec($cmd);
	  if ($debug) echo($cmd);
    return false;
}

//turn on and off the raspberry touchscreen
function display_ctl($switch=1)
{
    global $debug;
    if ($switch==0) $cmd="sudo $root_dir/robo-libs/disp_off.sh";
    else            $cmd="sudo $root_dir/robo-libs/disp_on.sh";
    shell_exec($cmd);
    if ($debug) echo($cmd);
}


function move_base($offset,$non_blocking=0)
{
	  global $debug,$root_dir,$pdesc;
	  $offset=$offset/10;
    $offset=(float)$offset/10;
    $cmd = "/home/pi/ros.sh $root_dir/robo-libs/maxibot_move $offset &";
    if ($offset<10 and $offset>-10) 
        	{
        	  if ($non_blocking==1) proc_open($cmd, $pdesc, $pipes); 
        	  else exec($cmd);
          }
	  if ($debug) echo $cmd;
}

function rotate_base($angle,$non_blocking=0)
{
	  global $debug,$root_dir,$pdesc;
    $angle=(float)$angle;
    if ($non_blocking==1) $cmd = "/home/pi/ros.sh $root_dir/robo-libs/maxibot_rotate $angle &";
    else $cmd = "/home/pi/ros.sh $root_dir/robo-libs/maxibot_rotate $angle";
	  if ($angle<900 and $angle>-900) 
	       {
	        	if ($non_blocking==1) proc_open($cmd, $pdesc, $pipes); 
        	  else exec($cmd);
         }
    if ($debug) echo $cmd;
}

function stop_move()
{
  	global $debug,$root_dir;
	  @exec ("sudo killall -9 $root_dir/robo-libs/maxibot_move   > /dev/null 2>&1 ");
	  @exec ("sudo killall -9 $root_dir/robo-libs/maxibot_rotate   > /dev/null 2>&1 ");
}

function telemetry()
{
  	global $root_dir;
	  $cmd="sudo /home/pi/ros.sh rostopic echo /diagnostics -n 1";
	  exec($cmd, $res);
	  $strRes;
	  echo "<div class=\"content container\">";
	  $strRes = implode("<br/>", $res);
	  echo $strRes;
	  echo "</div>";
}

function read_sensor($sensor=1)
{
	  $tmp = file_get_contents('/dev/shm/sensor.txt');
    $vals=explode("=",$tmp);
    $result=(int)$vals[1];
    if ($result>0) $result=round($result/10); // make output in centimeters
    return $result;
}

function end_clean()
{   
	  global $msg_ok,$debug,$root_dir,$ram_fsdir,$pid;
	  echo "\n\n ----Изпълнението на програмата приключи---- \n\n";
	  $run_pid=@file_get_contents ("$ram_fsdir/bebotphpexec.pid");
	  @exec("sudo killall -9 fbi  > /dev/null 2>&1");
		@unlink("$ram_fsdir/bebotphpexec.pid");
	  if ($run_pid>0) @exec("$root_dir/robo-libs/kill_recur.sh $run_pid ");		
}

?>
