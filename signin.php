<?php
  $no_menu=1;
	include_once 'head.php';			
?>

<body style="padding: 0;">

<div id="loginModal" tabindex="-1" role="dialog" aria-hidden="true" class="login-register-dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <?php /* <img height=40 style="margin-left:5px;" src="/img/dnevnik_logo.png">*/ ?>
                <i class='fab fa-android'></i><?=$LRobotControlCenter?></a>
            </div>
            <div class="modal-body">
            		<div class="formCheck"></div>
                <form class="form" role="form" autocomplete="off" id="formLogin" action="index.php?cmd=login" method="POST">
                    <div class="form-group">          
                         
                        <label for="uname1"><?=$LEmail?></label>
                        <input type="text" class="form-control form-control-lg" name="username" id="uname" required="">
                    </div>
                    <div class="form-group">
                        <label><?=$LPassword?></label>
                        <input type="password" class="form-control form-control-lg" name="password" id="pwd" required="" autocomplete="new-password">
                    </div>
                    <div class="form-group py-4">
                       
                        <button type="submit" class="btn btn-success btn-lg float-right" id="btnLogin" name="login-btn"><?=$LLogin?></button>
                        <input type="hidden" name="submitLogin" value="1">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
