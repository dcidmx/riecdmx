<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?=SITE_NAME?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Framedev"
            name="description" />
        <meta content="" name="author" />
        <link href="<?=URL_PUBLIC?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_PUBLIC?>assets/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_PUBLIC?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_PUBLIC?>assets/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_PUBLIC?>css/login/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?=URL_PUBLIC?>css/login/plugins-md.min.css" rel="stylesheet" type="text/css" />
        <link href="<?=URL_PUBLIC?>css/login/lock-2.min.css" rel="stylesheet" type="text/css" />
        <link rel="icon" href="<?=FW7?>img/favicon.ico" />
    <body class="">
        <div class="page-lock">
            <div class="page-logo">
                <a class="brand" href="javascript:;">FRAMEDEV</a>
            </div>
            <div class="page-body">

                <?php
                if ($avatar){
                ?>
                      <img src="../plugs/timthumb.php?src=tmp/<?=$avatar?>&w=200">
                <?php
                }else{
                ?>
                      <img class="page-lock-img" src="<?=URL_APP?>img/lock4.png" width="250px" alt="Candado">
                <?php
                }
                ?>



                <div class="page-lock-info">
                    <h1><?=$usuario['nombres']?></h1>
                    <span class="email"><?=$correo?></span>
                    <span class="locked"> Sesión asegurada </span>
                    <form class="form-inline">
                        <div class="input-group input-medium">
                            <input type="hidden" name="usuario" id="usuario" value="<?=$username?>">

							              <input class="form-control" type="password" placeholder="Contraseña" name="password" id="password" required="" autocomplete="off">

                            <span class="input-group-btn">
                                <a class="btn btn-primary loginfn" rel="nofollow">
                                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                                </a>
                            </span>
                        </div>
                        <div class="relogin">
                            <a href="../">¿ No eres  <?=$usuario['nombres']?> ?</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="page-footer-custom"> 2017 &copy; <?=SITE_NAME?> </div>
        </div>
        <!--[if lt IE 9]>
		<script src="<?=URL_PUBLIC?>assets/plugins/respond.min.js"></script>
		<script src="<?=URL_PUBLIC?>assets/plugins/excanvas.min.js"></script>
		<script src="<?=URL_PUBLIC?>assets/plugins/ie8.fix.min.js"></script>
		<![endif]-->
        <script src="<?=URL_PUBLIC?>assets/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?=URL_PUBLIC?>assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="<?=URL_PUBLIC?>assets/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="<?=URL_PUBLIC?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?=URL_PUBLIC?>assets/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?=URL_PUBLIC?>assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <script src="<?=URL_PUBLIC?>assets/plugins/backstretch/jquery.backstretch.min.js" type="text/javascript"></script>
        <script src="<?=URL_PUBLIC?>assets/pages/app.min.js" type="text/javascript"></script>
        <script src="<?=URL_PUBLIC?>assets/pages/lock-2.js" type="text/javascript"></script>

		<script>var url_app = '<?=URL_APP?>';</script>

		<script src="<?=URL_PUBLIC?>assets/js/generales.js"></script>
		<script src="<?=URL_PUBLIC?>assets/js/common.js"></script>
    </body>

</html>
