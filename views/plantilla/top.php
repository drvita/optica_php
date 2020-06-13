<!DOCTYPE html>
<html lang="es">
<head>
	<title><?=$title?></title>
	<meta charset="utf-8" />
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link rel="shortcut icon" type="image/png" href="<?=base_url('lib/images/apple-touch-icon.png')?>"/>
	<link rel="stylesheet" href="<?=base_url('lib/css/themes/start/jquery-ui.min.css')?>" />
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
<? if(isset($cssf) && count($cssf)){
		foreach ($cssf as $css){
			if(empty($css['href'])) continue;
			if(!filter_var($css['href'], FILTER_VALIDATE_URL)) $css['href']=base_url($css['href']); ?>
	<link rel="stylesheet"<?foreach($css as $key=>$value) echo " $key='$value'"?>/>
<?		}
	}?>
	<script type="text/javascript" src="<?=base_url('lib/js/jquery-2.1.1.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/jquery-ui-1.9.2.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/jquery.noty.packaged.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/jquery.ui-contextmenu.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/bootstrap.min.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/jquery.cookie.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/jqgridExcel.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/app.js')?>"></script>
	<script type="text/javascript" src="<?=base_url('lib/js/system.js')?>"></script>
<? if(isset($scripts) && count($scripts)){
		foreach ($scripts as $script){
			if(empty($script['src'])) continue;
			if(!filter_var($script['src'], FILTER_VALIDATE_URL)) $script['src']=base_url($script['src']);?>
<script type="text/javascript"<?foreach($script as $key=>$value) echo " $key='$value'"?>></script>
<?}}?>
<script>var base_url = '<?=base_url()?>';</script>
</head>
<body class="skin-blue"id="htmlContent">
	 <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="<?=base_url()?>" class="logo">
				<img src="<?=base_url('lib/images/logo_oa_small.png')?>"title="openAdmin"/>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
						<?if($user['kindof']!=0):?>
						<li class="dropdown tasks-menu">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-list-alt"title="Ultimos clientes registrados"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header"><span class="glyphicon glyphicon-list-alt"></span> 
									Ultimos clientes registrados</li>
                                <li>
                                    <ul class="menu"id="user-last"></ul>
                                </li>
                                <li class="footer"><a href="javascript:void(0)">&nbsp;</a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?if($user['kindof']!=1 && $menu=='inbox' && $submenu=='index'):?>
						<li class="dropdown tasks-menu">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-eye-open"title="Examenes sin pedido"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header"><span class="glyphicon glyphicon-eye-open"></span> 
									Examenes sin pedido</li>
                                <li>
                                    <ul class="menu"id="list-examenes"></ul>
                                </li>
                                <li class="footer"><a href="javascript:void(0)">&nbsp;</a></li>
                            </ul>
                        </li>
                        <?php endif;?>
                        <li class="dropdown tasks-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-tasks"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Datos de sistema</li>
                                <li>
                                    <ul class="menu">
                                        <li>
											<a href="javascript:void(0)">
                                                <h3>
													<strong><i class="fa fa-circle text-success"></i> Version:</strong>
													<small>
														<?php echo APP ?>
													</small>
												</h3>
											</a>
                                        </li>
                                        <li>
											<a href="javascript:void(0)">
												<h3>
													<strong><i class="fa fa-circle text-success"></i> DB:</strong>
													<small>
														<?php echo $this->db->database; ?>
													</small>
												</h3>
											</a>
                                        </li>
                                        <li>
											<a href="javascript:void(0)">
												<h3>
													<strong><i class="fa fa-circle text-success"></i> DBdriver:</strong>
													<small>
														<?php echo $this->db->dbdriver; ?> [<?php echo $this->db->conn_id->client_info; ?>]
													</small>
												</h3>
											</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="#">Ver mas...</a>
                                </li>
                            </ul>
                        </li>
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?php echo $user['name']; ?> <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <img src="<?=base_url('lib/images/'.$user['kindof'].'.png')?>" class="img-circle" alt="User Image" />
                                    <p>
                                        <?php echo $user['name']; ?>
                                        <small>
                                        <?php
                                        switch($user['kindof']){
											case 0:
											echo "Empleado administrativo";
											break;
											case 1;
											echo "Optometrista";
											break;
											case 2:
											echo "Empleado operativo";
										}
                                        ?>
                                        </small>
                                    </p>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Perfil</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="javascript:void(0)"onclick="buttonoutsystem()"class="btn btn-default btn-flat">Cerrar sistema</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">                
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="<?=base_url('lib/images/'.$user['kindof'].'.png')?>" class="img-circle" alt="<?php echo $user['username']; ?>" />
                        </div>
                        <div class="pull-left info">
                            <p>Hola, <?php echo $user['username']; ?></p>
                            <a href="javascript:void(0)"><i class="fa fa-circle text-success"></i> Activo</a>
                        </div>
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
						<?if(!$user['kindof']):?>
                        <li class="<?=($menu=='report')?"active treeview":"treeview";?>">
							<a href="javascript:void(0)">
								<span class="glyphicon glyphicon-blackboard"></span> <span>Reportes</span> <span class="caret"></span>
							</a>
							<ul class="treeview-menu">
								<li><a href="<?=base_url('report')?>">Corte</a></li>
								<li><a href="<?=base_url('report/print_ventas')?>">Ventas</a></li>
							</ul>
						</li>
                        <?php endif;?>
						<li class="<?=($menu=='catalogos')?"active treeview":"treeview";?>">
							<a href="javascript:void(0)">
								<span class="glyphicon glyphicon-list-alt"></span> <span>Catalogos</span> <span class="caret"></span>
							</a>
							<ul class="treeview-menu">
								<li><a href="<?=base_url('catalogos/contact')?>">Contactos</a></li>
								<?if(!$user['kindof']):?>
								<li><a href="<?=base_url('catalogos/store_items')?>">Productos (almacen)</a></li>
								<li><a href="<?=base_url('catalogos/store_unit')?>">Unidad (almacen)</a></li>
								<li><a href="<?=base_url('catalogos/store_brand')?>">Marcas (almacen)</a></li>
								<li><a href="<?=base_url('catalogos/store_catid')?>">Categoria (almacen)</a></li>
								<?php endif?>
							</ul>
						</li>
						<li class="<?=($menu=='optica')?"active":"";?>">
							<a href="<?=base_url('optica')?>">
								<span class="glyphicon glyphicon-eye-open"></span> <span>Examenes</span>
							</a>
						</li>
						<?if($user['kindof']==2 || $user['kindof']==0):?>
						<li class="<?=($menu=='pedidos')?"active":"";?>">
							<a href="<?=base_url('pedidos')?>">
								<span class="glyphicon glyphicon-copy"></span> <span>Pedidos</span>
							</a>
						</li>
						<?endif?>
						<?if(!$user['kindof']):?>
						<li class="<?=($menu=='store')?"active treeview":"treeview";?>">
							<a href="javascript:void(0)">
								<span class="glyphicon glyphicon-tag"></span> <span>Almacen</span> <span class="caret"></span>
							</a>
							<ul class="treeview-menu">
								<li><a href="<?=base_url('store')?>">Articulos</a></li>
								<li><a href="<?=base_url('store/lentes')?>">Inventario (Lentes)</a></li>
							</ul>
						</li>
						<li class="<?=($menu=='settings')?"active":"";?>">
							<a href="<?=base_url('settings')?>">
								<span class="glyphicon glyphicon-wrench"></span> <span>Configuraciones</span>
							</a>
						</li>
						<li class="<?=($menu=='home')?"active":"";?>">
							<a href="<?=base_url('home/users')?>">
								<span class="glyphicon glyphicon-user"></span> <span>Usuarios</span>
							</a>
						</li>
						<?endif?>
						<?if($user['kindof']==2||!$user['kindof']):?>
						<li class="<?=($menu=='inbox')?"active treeview":"treeview";?>">
							<a href="javascript:void(0)">
								<span class="glyphicon glyphicon-inbox"></span> <span>Caja</span> <span class="caret"></span>
							</a>
							<ul class="treeview-menu">
								<?php if($user['kindof']<=2):?>
								<li><a href="<?=base_url('inbox')?>">Venta</a></li>
								<?php endif?>
								<li><a href="<?=base_url('inbox/corte')?>">Inicio y corte</a></li>
							</ul>
						</li>
						<?endif?>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>
            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">                
                <section class="content-header">
					<div class="row">
						<div class="col-xs-6">
							<h4 style="text-transform:uppercase;">
								<?php echo $menu; ?> 
								<small style="text-transform:capitalize;"><?php echo $submenu; ?></small>
							</h4>
						</div>
						<?if(isset($main) && count($main) && is_array($main)):?>
						<div class="col-xs-6">
							<div class="btn-group pull-right">
							<?foreach($main as $item):?>
								<?if(!empty($item['label'])):?>
									<button type="button" class="btn btn-<?=(isset($item['type']))?$item['type']:'default'?>" onclick="<?=(isset($item['click']))?$item['click']:''?>">
									<?if(isset($item['class'])):?>
										<span class="glyphicon glyphicon-<?=$item['class']?>"></span>
									<?endif?>
										<?=(isset($item['label']))?$item['label']:''?>
									</button>
								<?endif?>
							<?endforeach?>
							</div>
						</div>
						<?php endif; ?>
					</div>
                </section>
                <!-- Main content -->
                <section class="content"id="sectionContent">

