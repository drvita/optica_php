<!DOCTYPE html>
<html class="bg-black">
	<title>Openadmin - Optica Madero system</title>
	<meta charset="utf-8" />
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" type="image/png" href="<?=base_url('lib/images/apple-touch-icon.png')?>"/>
	<link rel="stylesheet" href="<?=base_url('lib/css/themes/blitzer/jquery-ui.min.css')?>" />
	<link rel="stylesheet" href="<?=base_url('lib/css/ui.jqgrid.css')?>" />
	<link rel="stylesheet" href="<?=base_url('lib/css/bootstrap.min.css')?>">
	<link rel="stylesheet" href="<?=base_url('lib/css/font-awesome.min.css')?>"/>
	<link rel="stylesheet" href="<?=base_url('lib/css/ionicons.min.css')?>"/>
	<link rel="stylesheet" href="<?=base_url('lib/css/AdminLTE.css')?>"/>
	<link rel="stylesheet" href="<?=base_url('lib/css/animate.css')?>" />
	<link rel="stylesheet" href="<?=base_url('lib/css/style.css')?>" />
	<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	<script type="text/javascript" src="<?=base_url('lib/js/jquery-2.1.1.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/jquery-ui-1.9.2.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/jquery.noty.packaged.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/jquery.ui-contextmenu.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/bootstrap.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/jquery.cookie.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/jqgridExcel.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/system.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/jquery.validate.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/jquery.md5.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/view/home/autho.js')?>"></script>
	<script>var base_url = '<?=base_url()?>';</script>
</head>
    <body class="bg-black">

        <div class="form-box" id="login-box">
            <div class="header">
				<img src="<?=base_url('lib/images/logo_oa_small.png')?>"title="openAdmin"/>
            </div>
            <div class="body bg-gray">
				<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formAutho')?>
				<?=form_open('home/session',$atributos)?>
				<div class="form-group">
					<label class="sr-only" for="user">Usuario</label>
					<input pattern="^[a-zA-Z0-9\s.]{5,20}"class="form-control"id="user"placeholder="Usuario"type="text"name="user"/>
				</div>
				<div class="form-group">
					<label class="sr-only" for="pass">Password</label>
					<input pattern="^[a-zA-Z0-9\s.#]{8,16}"class="form-control"id="pass"placeholder="Contraseña"type="password"name="pass"/>
				</div>
				<p style="text-align:right">
					<button type="submit" class="btn btn-primary">
						<span class="glyphicon glyphicon-check"></span> Ingresar
					</button>
				</p>
				</form>
			</div>
        </div>     
    </body>
</html>
