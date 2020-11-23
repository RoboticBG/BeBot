<?php
  include_once 'login.php';	
	include_once 'head.php';  

?>

	<script type="text/javascript">
	
	var ros = new ROSLIB.Ros({
		url: 'ws://<?=$_SERVER['SERVER_ADDR']?>:9090'
	});

	ros.on('connection', function(){
		console.log('Connected to websocket server.');
	});

	ros.on('error', function(error){
		console.log('Error connecting to websocket server: ', error);
	});

	ros.on('close', function(){
		console.log('Connection to websocket server closed.');
	});
   
     var cmdVel = new ROSLIB.Topic({
       ros : ros,
       name : '/cmd_vel',
       messageType : 'geometry_msgs/Twist'
     });
   
     var twistLeft = new ROSLIB.Message({
       linear : {
         x : 0.0,
         y : 0.0,
         z : 0.0
       },
       angular : {
         x : 0.0,
         y : 0.0,
         z : 1.0
       }
     });

     var forward = new ROSLIB.Message({
       linear : {
         x : 0.1,
         y : 0.0,
         z : 0.0
       },
       angular : {
         x : 0.0,
         y : 0.0,
         z : 0.0
       }
     });
     var backward = new ROSLIB.Message({
       linear : {
         x : -0.1,
         y : 0.0,
         z : 0.0
       },
       angular : {
         x : 0.0,
         y : 0.0,
         z : 0.0
       }
     });

     var twistRight = new ROSLIB.Message({
       linear : {
         x : 0.0,
         y : 0.0,
         z : 0.0
       },
       angular : {
         x : 0.0,
         y : 0.0,
         z : -1.0
       }
     });
       
    $( document ).ready(function() {
    	console.log( "ready!" );
	      $( document ).bind('keydown', function(e){

	       if(e.keyCode == 74){
                                console.log("left");
		   		cmdVel.publish(twistLeft);
		   		var leftKey = $('#left');
		   		
		   		leftKey.removeClass('bg-dark text-white');
		   		leftKey.addClass('bg-light text-dark');	
		   }
		   else if(e.keyCode == 75){
                                console.log("right");
		   		cmdVel.publish(twistRight);
		   		$('#right').removeClass('bg-dark text-white');
		   		$('#right').addClass('bg-light text-dark');
		   }
		   else if(e.keyCode == 73){
                                console.log("forward");
		   		cmdVel.publish(forward);
		   		$('#up').removeClass('bg-dark text-white');
		   		$('#up').addClass('bg-light text-dark');
		   }
		   else if(e.keyCode == 77){
                                console.log("backward");
		   		cmdVel.publish(backward);
		   		$('#down').removeClass('bg-dark text-white');
		   		$('#down').addClass('bg-light text-dark');
		   }
	    });

			var gamepadInfo = document.getElementById("gamepad-info");
				var start;
				var a = 0;
				var b = 0;
				var last_a_position=0;
				var last_b_position=0;
				var rAF = window.mozRequestAnimationFrame ||
				  window.webkitRequestAnimationFrame ||
				  window.requestAnimationFrame;
				var rAFStop = window.mozCancelRequestAnimationFrame ||
				  window.webkitCancelRequestAnimationFrame ||
				  window.cancelRequestAnimationFrame;
				window.addEventListener("gamepadconnected", function() {
				  var gp = navigator.getGamepads()[0];
				  gameLoop();
				});
				window.addEventListener("gamepaddisconnected", function() {
				  gamepadInfo.innerHTML = "Waiting for gamepad.";
				  rAFStop(start);
				});
				if(!('GamepadEvent' in window)) {
				  // No gamepad events available, poll instead.
				  var interval = setInterval(pollGamepads, 500);
				}
				function pollGamepads() {
				  var gamepads = navigator.getGamepads ? navigator.getGamepads() : (navigator.webkitGetGamepads ? navigator.webkitGetGamepads : []);
				  for (var i = 0; i < gamepads.length; i++) {
				    var gp = gamepads[i];
				    if(gp) {
				      gamepadInfo.innerHTML = "Gamepad connected at index " + gp.index + ": " + gp.id + ". It has " + gp.buttons.length + " buttons and " + gp.axes.length + " axes.";
				      gameLoop();
				      clearInterval(interval);
				    }
				  }
				}
				function buttonPressed(b) {
				  if (typeof(b) == "object") {
				    return b.pressed;
				  }
				  return b == 1.0;
				}
				function gameLoop() {
				  var gamepads = navigator.getGamepads ? navigator.getGamepads() : (navigator.webkitGetGamepads ? navigator.webkitGetGamepads : []);
				  if (!gamepads)
				    return;
				  var gp = gamepads[0];
				  if (buttonPressed(gp.buttons[0])) {
				    b--; //gore
				  } else if (buttonPressed(gp.buttons[2])) {
				    b++; //dolu
				  }
				  if(buttonPressed(gp.buttons[1])) {
				    a++; //dqsno
				    
				  } else if(buttonPressed(gp.buttons[3])) {
				    a--; //lqvo
				  }

				  if(last_b_position < b){
				 		 		cmdVel.publish(backward);
				 	}
				 	else if(last_b_position > b){
				 		 		cmdVel.publish(forward);
				 	}	
				 	
				 	if(last_a_position < a){
				 		 		cmdVel.publish(twistRight);
				 	}
				 	else if(last_a_position > a){
				 		 		cmdVel.publish(twistLeft);
				 	}	

				  last_a_position = a;
				  last_b_position = b;
				  var start = rAF(gameLoop);
				}
    });       

	</script>
<div class="content-header">
						<header>
							<div class="header"><h4><?=$LManualControl?></h4></div>
						</header>
					</div>
					<div class="content-container" style="overflow: scroll;">
						
						
							
	 <div class="inside-header">
					<div id="header-title"><br><br></div>
			    <div id="scriptSign"></div>
   </div>
	
	<?php /*
		<iframe width=410 height=306 style="border:none;transform: scaleY(1);" src="http://<?=$site_ip?>:8080/stream?topic=/raspicam_node/image"></iframe>
		*/
		?>
		<div id="canvas_container">
			<div id="keyboard_control">
				<div class="row">
					<div id="up" class="arrows bg-dark text-white">I (Frw.)</div>
				</div>
				<div class="row">
					<div id="wrapper">
						<div id="left" class="arrows bg-dark text-white">J (Left)</div>
						<div id="down" class="arrows bg-dark text-white">M (Back)</div>
						<div id="right" class="arrows bg-dark text-white">K (Right)</div>
				</div>
			</div>	
		</div>
	</div>

<?php	
	include 'foot.php';
?>
