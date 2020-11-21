<?php
if ($no_menu==1)
 {
  include_once 'config.php';
  include_once 'func.php';
 } else include_once "login.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="/libs/bootstrap-4.5.0-dist/css/bootstrap.css?t=<?php echo time(); ?>">
	<script src="/libs/jquery-3.5.1.min.js"></script>
	<link rel="stylesheet" href="/libs/jquery-ui-1.12.1/jquery-ui.css">
	<script src="/libs/jquery-ui-1.12.1/jquery-ui.js"></script>
	
	<script src="/libs/bootstrap-4.5.0-dist/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="/libs/font-awesome-updated/css/all.css">
	<link rel="stylesheet" href="/libs/font-awesome-updated/css/fontawesome.min.css">
	<link rel="stylesheet" href="/libs/jquery-confirm/css/jquery-confirm.css">
	<script src="/libs/jquery-confirm/dist/jquery-confirm.min.js"></script>
	<link href="/libs/material-icons/icons.css" rel="stylesheet">
 
	<link rel="stylesheet" href="style.css?t=<?php echo time();?>">
	<script type="text/javascript" src="script.js?t=<?php echo time(); ?>"></script>


  <!-- CODE MIRROR -->
	<link rel="stylesheet" href="libs/codemirror-5.58.2/lib/codemirror.css">
	<link rel="stylesheet" href="libs/codemirror-5.58.2/theme/rubyblue.css">
	<script src="libs/codemirror-5.58.2/lib/codemirror.js"></script>
	<script src="libs/codemirror-5.58.2/mode/css/css.js"></script>
	<script src="libs/codemirror-5.58.2/mode/xml/xml.js"></script>
	<script src="libs/codemirror-5.58.2/addon/edit/closetag.js"></script>
	<script src="libs/codemirror-5.58.2/addon/edit/matchbrackets.js"></script>
	<script src="libs/codemirror-5.58.2/mode/htmlmixed/htmlmixed.js"></script>
	<script src="libs/codemirror-5.58.2/mode/javascript/javascript.js"></script>
	<script src="libs/codemirror-5.58.2/mode/clike/clike.js"></script>
	<script src="libs/codemirror-5.58.2/mode/php/php.js"></script>
	<link rel="stylesheet" href="libs/codemirror-5.58.2/addon/scroll/simplescrollbars.css">
	<script src="libs/codemirror-5.58.2/addon/scroll/simplescrollbars.js"></script>
	<!-- ros bridge -->
	<script type="text/javascript" src="libs/rosbridge/EventEmitter2/eventemitter2.min.js?r=2"></script>
	<script type="text/javascript" src="libs/rosbridge/roslibjs/roslib.min.js?k=3"></script>


  <script src="/libs/jquery.ui.datepicker-bg.js"></script>
	<link rel="stylesheet" href="/libs/gallery-fonts/font.css">
	
	

	<title><?=$LRobotControlCenter?></title>
 	    <style>
			    	    	
                  input::-webkit-outer-spin-button,
                  input::-webkit-inner-spin-button {
                         -webkit-appearance: none;
                         margin: 0;
                  }

                  /* Firefox */
                  input[type=number] {
                  -moz-appearance: textfield;
                  }
                  
                  .ui-datepicker a.ui-state-default {
                    color: #000 !important;
                    }
			    	    </style>
			    	     <script>
                 $( function() {
                 
                  $( "#datepicker" ).datepicker( $.datepicker.regional[ "bg" ] );
                 } );
                </script>
</head>
<!-- End of head section -->
<?php 
if (!$no_menu) { 
?>
<body>
<!-- Top Navigation Begin -->
	<header>
		<nav class="navbar fixed-top navbar-dark bg-primary" style="justify-content: initial;">
		 <a class="navbar-brand" href="index.php"><i class='fab fa-android'></i><?=$LRobotControlCenter?></a>			
		   <div>
		    <ul class="navbar-nav">
		      <li class="nav-item active">	
		        <a class="nav-link" href="#"><i class="fa fa-user system-icons"></i> <?=$logged_user?></a>
		      </li>		     
		    </ul>   
		  </div> 
		 	  
		  <a href="login.php?cmd=logout" style="margin-left: 30px;" class="ml-auto" id="logout-link"><i class="fas fa-sign-out-alt logout-icon" style="font-size: 28px; "></i></a>
			<button class="navbar-toggler visible_pre d-lg-none ml-auto" type="button" aria-controls="navbarNav2" aria-expanded="false" aria-label="Toggle navigation" id="toggle-nav-btn">
				<span class="navbar-toggler-icon"></span>
		  </button>
		  <div class= "navbar-collapse" id="navbarNav2" style="display: none!important;">
		    <ul class="navbar-nav2">
		     <?php include "menu.php"; ?>
			  </ul>
		  </div>
		</nav>
	</header>
	<style>
		.modal, .modal-img{
			    z-index: 2002 !important;
			    background-color: rgba(0,0,0,0.1) !important;
		}
		
		@media (min-width: 1600px){
				.modal-dialog{
					max-width:1575px !important;
				}
		}
		
		@media (min-width: 1200px){
				.modal-dialog{
					max-width:1175px !important;
				}
		}
		
		@media (max-width: 1199px){
				.modal-dialog{
					max-width:600px !important;
				}
		}
		
		@media (max-width: 600px){
			.modal-dialog{
				max-width:100% !important;
			}
		}
	</style>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <iframe style="width:100%;height:400px;" frameborder="0"src="/libs/filemanager/dialog.php?type=0"></iframe>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->
<script>
		$(document).ready(function(){
			$(".dropdown-btn-scripts").click(function(){
				var data = $(this).attr("data-ul");
				$(`ul[data-name='${data}']`).toggle("slow");
			});
		});
</script>
<!-- Top Navigation End -->

<div class="container-fluid">
		<div class="row">
		
<!-- Left Sidebar Menu Begin -->		
			<nav class="col-md-3 col-lg-2 d-none d-md-block bg-light sidebar">
	   		<div class="sidebar-sticky">				
			     <ul class="nav flex-column">
			       <?php include "menu.php"; ?>
			     </ul>
	      </div>
			</nav>
<!-- Left Sidebar Menu End -->	

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">

<?php } ?>