<div class="panel panel-default">
  <div class="panel-body">
    <div class="container-fluid">
		<div class="page-header">
			<h3><span class="glyphicon glyphicon-user"></span> Paciente</h3>
		</div>
		<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'newexamen')?>
		<?=form_open('optica/saveExamenes',$atributos)?>
		<div class="row">
			<?
			if(isset($row['now']['edad']) && $row['now']['edad']!='0'){
				$edad=(int)($row['now']['edad']);
			} else $edad=0?>
			<div class="col-xs-6"><i class="fa fa-male"></i> <label for="contacto">Nombre</label></div>
			<div class="col-xs-2"><i class="fa fa-calendar"></i> <label for="edad">Edad</label></div>
			<div class="col-xs-4"><i class="fa fa-phone"></i> <label for="particular">Telefono</label></div>
		</div>
		<div class="row">
			<div class="col-xs-6">
				<input type="text"id="contacto"name="contact[contacto]"class="form-control input-xs"value="<?=$row['now']['contacto']?>"disabled />
			</div>
			<div class="col-xs-2">
				<input type="text"id="edad"name="edad"class="form-control input-xs"value="<?=$edad?>" />
			</div>
			<?if(isset($row['now']['telnumber'])):?>
			<div class="col-xs-4">
				<? $val=null;
				foreach($row['now']['telnumber'] AS $key=>$val){
					if(!empty($val)) break;
				}?>
				<input type="tel"id="telefono"pattern="^[0-9]{10}"maxlength="10"title="LADA + NUMERO = 10"name="contact[telnumber][<?=(!empty($key)?$key:'mobil')?>]"class="form-control input-xs"value="<?=$val?>"<?=(!empty($val))?'disabled':''?> />
			</div>
			<?endif?>
		</div>
		<div class="page-header">
			<h3>
				<span class="glyphicon glyphicon-folder-open"></span> Examen 
				<?php if($row['now']['status']):?>
				<span class="badge"><?php echo $row['now']['id']?></span> <small><?php echo dateCut($row['now']['date'])?></small>
				<?php else: ?>
				<small>anterior</small> <span class="badge"><?php echo $row['ant'][0]['id']?></span> <small><?php echo dateCut($row['ant'][0]['date'])?></small>
				<?php endif;?>
			</h3>
		</div>
		<div class="row">
			<div class="col-xs-2"><span class="label label-default"><i class="fa fa-circle"></i> Keratometria</span></div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Derecho</label>
				<input type="text"name="keratometriaod"maxlength="12"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['keratometriaod']:$row['ant'][0]['keratometriaod']?>"/>
			</div>
			<div class="col-xs-2"style="border-right: 1px solid #999;">
				<i class="fa fa-eye"></i> <label>Izquierdo</label>
				<input type="text"name="keratometriaoi"maxlength="12"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['keratometriaoi']:$row['ant'][0]['keratometriaoi']?>"/>
			</div>
			<div class="col-xs-2"><span class="label label-default"><i class="fa fa-circle"></i> Pantalleo</span></div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Derecho</label>
				<input type="text"name="pantalleood"maxlength="12"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['pantalleood']:$row['ant'][0]['pantalleood']?>"/>
			</div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Izquierdo</label>
				<input type="text"name="pantalleooi"maxlength="12"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['pantalleooi']:$row['ant'][0]['pantalleooi']?>"/>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-2"><span class="label label-default"><i class="fa fa-circle"></i> Interrogatorio</span></div>
			<div class="col-xs-10">
				<textarea name="interrogatorio"class="form-control"rows="2"><?php echo $row['now']['status']?$row['now']['interrogatorio']:$row['ant'][0]['interrogatorio']?></textarea>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-1"><span class="label label-default"><i class="fa fa-desktop"></i> PC</span></div>
			<div class="col-xs-1">
				<input name="pc"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo $row['now']['pc']?'checked':''; else echo $row['ant'][0]['pc']?'checked':''?>/>
			</div>
			<div class="col-xs-2">
				<input type="text"name="pc_time"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['pc_time']:$row['ant'][0]['pc_time']?>"/>
			</div>
			<div class="col-xs-1"><span class="label label-default"><i class="fa fa-tablet"></i> Tablet</span></div>
			<div class="col-xs-1">
				<input name="tablet"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo ($row['now']['tablet'])?'checked':''; else echo $row['ant'][0]['tablet']?'checked':''?>/>
			</div>
			<div class="col-xs-2">
				<input type="text"name="tablet_time"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['tablet_time']:$row['ant'][0]['tablet_time']?>"/>
			</div>
			<div class="col-xs-1"><span class="label label-default"><i class="fa fa-mobile-phone"></i> Telefono</span></div>
			<div class="col-xs-1">
				<input name="movil"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo ($row['now']['movil'])?'checked':''; else echo $row['ant'][0]['movil']?'checked':''?>/>
			</div>
			<div class="col-xs-2">
				<input type="text"name="movil_time"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['movil_time']:$row['ant'][0]['movil_time']?>"/>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-xs-1"><span class="label label-default"><i class="fa fa-laptop"></i> Laptop</span></div>
			<div class="col-xs-1">
				<input name="lap"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo $row['now']['lap']?'checked':''; else echo $row['ant'][0]['lap']?'checked':''?>/>
			</div>
			<div class="col-xs-2">
				<input type="text"name="lap_time"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['lap_time']:$row['ant'][0]['lap_time']?>"/>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-1"><span class="label label-default"><i class="fa fa-circle"></i> Frontal</span></div>
			<div class="col-xs-1">
				<input name="frontal"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo $row['now']['frontal']?'checked':''; else echo $row['ant'][0]['frontal']?'checked':''?>/>
			</div>
			<div class="col-xs-1"><span class="label label-default"><i class="fa fa-circle"></i> Occipital</span></div>
			<div class="col-xs-1">
				<input name="occipital"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo ($row['now']['occipital'])?'checked':''; else echo $row['ant'][0]['occipital']?'checked':''?>/>
			</div>
			<div class="col-xs-1"><span class="label label-default"><i class="fa fa-circle"></i> General</span></div>
			<div class="col-xs-1">
				<input name="generality"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo ($row['now']['generality'])?'checked':''; else echo $row['ant'][0]['generality']?'checked':''?>/>
			</div>
			<div class="col-xs-1"><span class="label label-default"><i class="fa fa-circle"></i> Temporal</span></div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Derecho</label> 
				<input name="temporaod"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo ($row['now']['temporaod'])?'checked':''; else echo $row['ant'][0]['temporaod']?'checked':''?>/>
			</div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Izquierdo</label> 
				<input name="temporaoi"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo ($row['now']['temporaoi'])?'checked':''; else echo $row['ant'][0]['temporaoi']?'checked':''?>/>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-1"><span class="label label-default"><i class="fa fa-circle"></i> Cefalea</span></div>
			<div class="col-xs-1">
				<input name="cefalea"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo ($row['now']['cefalea'])?'checked':''; else echo $row['ant'][0]['cefalea']?'checked':''?>/>
			</div>
			<div class="col-xs-2"><span class="label label-default"><i class="fa fa-circle"></i> Frecuencia</span></div>
			<div class="col-xs-4">
				<input type="text"name="c_frecuencia"maxlength="60"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['c_frecuencia']:$row['ant'][0]['c_frecuencia']?>"/>
			</div>
			<div class="col-xs-2"><span class="label label-default"><i class="fa fa-circle"></i> Intensidad</span></div>
			<div class="col-xs-2">
				<input type="range"name="c_intensidad"min="0"max="4"value="<?php echo $row['now']['status']?$row['now']['c_intensidad']:$row['ant'][0]['c_intensidad']?>"class="form-control input-xs"/>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-2"><span class="label label-default"><i class="fa fa-circle"></i> Cirugias oculares</span>
				<br/>anteriores</div>
			<div class="col-xs-10">
				<textarea name="coa"class="form-control"rows="2"><?php $row['now']['status']?$row['now']['coa']:$row['ant'][0]['coa']?></textarea>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-2">
				<span class="label label-default"><i class="fa fa-circle"></i> Antecedentes oculares</span>
					<br/>patologicas <label>personales</label>
			</div>
			<div class="col-xs-10">
				<textarea name="aopp"class="form-control"rows="2"><?php $row['now']['status']?$row['now']['aopp']:$row['ant'][0]['aopp']?></textarea>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-2">
				<span class="label label-default"><i class="fa fa-circle"></i> Antecedentes oculares</span>
				<br/>patologicas <label>familiares</label>
			</div>
			<div class="col-xs-10">
				<textarea name="aopf"class="form-control"rows="2"><?php $row['now']['status']?$row['now']['aopf']:$row['ant'][0]['aopf']?></textarea>
			</div>
		</div>
		<hr/>
		<span class="label label-default"><i class="fa fa-circle"></i> Diab&eacute;tico</span>
		<div class="row">
			<div class="col-xs-2">
				<label>Fecha</label>
				<input type="text"name="d_time"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['d_time']:$row['ant'][0]['d_time']?>"/>
			</div>
			<div class="col-xs-2">
				<label>Rango de glucosa</label>
				<input type="text"name="d_media"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['d_media']:$row['ant'][0]['d_media']?>"/>
			</div>
			<div class="col-xs-2">
				<label>Ultimo examen</label>
				<input type="text"name="d_test"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['d_test']:$row['ant'][0]['d_test']?>"/>
			</div>
			<div class="col-xs-2"><label>Fotocoagulaci&oacute;n laser</label></div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Derecho</label><br/>
				<input name="d_fclod"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo ($row['now']['d_fclod'])?'checked':''; else echo $row['ant'][0]['d_fclod']?'checked':''?>/>
			</div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Izquierdo</label><br/>
				<input name="d_fcloi"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo ($row['now']['d_fcloi'])?'checked':''; else echo $row['ant'][0]['d_fcloi']?'checked':''?>/>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-8">&nbsp;</div>
			<div class="col-xs-2">
				<input type="text"name="d_fclod_time"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['d_fclod_time']:$row['ant'][0]['d_fclod_time']?>"/>
			</div>
			<div class="col-xs-2">
				<input type="text"name="d_fcloi_time"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['d_fcloi_time']:$row['ant'][0]['d_fcloi_time']?>"/>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-2">
				<span class="label label-default"><i class="fa fa-circle"></i> Agudeza visual</span>
				<br/>sin lente</div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Derecho</label> 
				<input type="text"name="avslod"maxlength="12"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['avslod']:$row['ant'][0]['avslod']?>"/>
			</div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Izquierdo</label> 
				<input type="text"name="avsloi"maxlength="12"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['avsloi']:$row['ant'][0]['avsloi']?>"/>
			</div>
			<div class="col-xs-2"style="border-left: 1px solid #999;">
				<span class="label label-default"><i class="fa fa-circle"></i> Agudeza visual</span>
				<br/>con la graduaci&oacute;n anterior
			</div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Derecho</label> 
				<input type="text"name="avcgaod"maxlength="12"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['avcgaod']:$row['ant'][0]['avcgaod']?>"/>
			</div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Izquierdo</label> 
				<input type="text"name="avcgaoi"maxlength="12"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['avcgaoi']:$row['ant'][0]['avcgaoi']?>"/>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-2"><span class="label label-default"><i class="fa fa-circle"></i> Capacidad visual</span></div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Derecho</label> 
				<input type="text"name="cvod"maxlength="12"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['cvod']:$row['ant'][0]['cvod']?>"/>
			</div>
			<div class="col-xs-2"style="border-right: 1px solid #999;">
				<i class="fa fa-eye"></i> <label>Izquierdo</label> 
				<input type="text"name="cvoi"maxlength="12"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['cvoi']:$row['ant'][0]['cvoi']?>"/>
			</div>
			<div class="col-xs-2"><span class="label label-default"><i class="fa fa-circle"></i> Retinoscopia</span></div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Derecho</label> 
				<input type="text"name="rsod"maxlength="16"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['rsod']:$row['ant'][0]['rsod']?>"/>
			</div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Izquierdo</label> 
				<input type="text"name="rsoi"maxlength="16"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['rsoi']:$row['ant'][0]['rsoi']?>"/>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-2"><span class="label label-default"><i class="fa fa-circle"></i> Oftalmoscopia</span></div>
			<div class="col-xs-10">
				<textarea name="oftalmoscopia"class="form-control"rows="2"><?php echo $row['now']['status']?$row['now']['oftalmoscopia']:$row['ant'][0]['oftalmoscopia']?></textarea>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-2"><span class="label label-default"><i class="fa fa-circle"></i> Diagnostico</span></div>
			<div class="col-xs-2">
				<select name="diagnostico"class="form-control">
					<option value="Hemetrope"<?php if($row['now']['status']) $row['now']['diagnostico']=='Hemetrope'?' selected':''; else echo $row['ant'][0]['diagnostico']=='Hemetrope'?' selected':''?>>Hem&eacute;trope</option>
					<option value="hipermetropia"<?php if($row['now']['status']) $row['now']['diagnostico']=='hipermetropia'?' selected':''; else echo $row['ant'][0]['diagnostico']=='hipermetropia'?' selected':''?>>Hipermetrop&iacute;a</option>
					<option value="hipermetropia-astigmatismo"<?php if($row['now']['status']) $row['now']['diagnostico']=='hipermetropia-astigmatismo'?' selected':''; else echo $row['ant'][0]['diagnostico']=='hipermetropia-astigmatismo'?' selected':''?>>Hipermetrop&iacute;a y astigmatismo</option>
					<option value="hipermetropia-miopia"<?php if($row['now']['status']) $row['now']['diagnostico']=='hipermetropia-miopia'?' selected':''; else echo $row['ant'][0]['diagnostico']=='hipermetropia-miopia'?' selected':''?>>Hipermetrop&iacute;a y miop&iacute;a</option>
					<option value="astigmatismo-regla"<?php if($row['now']['status']) $row['now']['diagnostico']=='astigmatismo-regla'?' selected':''; else echo $row['ant'][0]['diagnostico']=='astigmatismo-regla'?' selected':''?>>Astigmatismo con la regla</option>
					<option value="astigmatismo-contra-regla"<?php if($row['now']['status']) $row['now']['diagnostico']=='astigmatismo-contra-regla'?' selected':''; else echo $row['ant'][0]['diagnostico']=='astigmatismo-contra-regla'?' selected':''?>>Astigmatismo contra la regla</option>
					<option value="astigmatismo-oblicuo"<?php if($row['now']['status']) $row['now']['diagnostico']=='astigmatismo-oblicuo'?' selected':''; else echo $row['ant'][0]['diagnostico']=='astigmatismo-oblicuo'?' selected':''?>>Astigmatismo oblicuo</option>
					<option value="astigmatismo-miopia"<?php if($row['now']['status']) $row['now']['diagnostico']=='astigmatismo-miopia'?' selected':''; else echo $row['ant'][0]['diagnostico']=='astigmatismo-miopia'?' selected':''?>>Astigmatismo/m&iacute;opico</option>
					<option value="miopia"<?php if($row['now']['status']) $row['now']['diagnostico']=='miopia'?' selected':''; else echo $row['ant'][0]['diagnostico']=='miopia'?' selected':''?>>Miop&iacute;a</option>
				</select>
			</div>
			<div class="col-xs-1"><span class="label label-default"><i class="fa fa-circle"></i> Presbicie</span></div>
			<div class="col-xs-2">
				<input name="presbicie"data-toggle="toggle"data-on="SI"data-off="NO"type="checkbox"value="1" 
				<?php if($row['now']['status']) echo ($row['now']['presbicie'])?'checked':''; else echo $row['ant'][0]['presbicie']?'checked':''?>/>
			</div>
			<div class="col-xs-1"><span class="label label-default"><i class="fa fa-circle"></i> Tensi&oacute;n</span><br/>ocular</div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Derecho</label> 
				<input type="number"name="piod"min="0"max="40"step="1"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['piod']:$row['ant'][0]['piod']?>"/>
			</div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Izquierdo</label> 
				<input type="number"name="pioi"min="0"max="40"step="1"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['pioi']:$row['ant'][0]['pioi']?>"/>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-2"><span class="label label-default"><i class="fa fa-circle"></i> Tratamiento oftalmico</span></div>
			<div class="col-xs-10">
				<textarea name="txoftalmico"class="form-control"rows="2"><?php echo $row['now']['status']?$row['now']['txoftalmico']:$row['ant'][0]['txoftalmico']?></textarea>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-xs-12">
				<span class="label label-default"><i class="fa fa-circle"></i> Graduaci&oacute;n</span>
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th style="width:35px">#</th>
							<th>Esfera</th>
							<th>Cilindro</th>
							<th>Eje</th>
							<th>Adici&oacute;n</th>
							<th>D/P</th>
							<th>Altura</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><i class="fa fa-eye"></i><br/><label>D</label></td>
							<td><input type="number"name="esferaod"min="-20"max="20"step=".25"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['esferaod']:$row['ant'][0]['esferaod']?>"/></td>
							<td><input type="number"name="cilindrod"min="-20"max="0"step=".25"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['cilindrod']:$row['ant'][0]['cilindrod']?>"/></td>
							<td><input type="number"name="ejeod"min="0"max="180"step="1"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['ejeod']:$row['ant'][0]['ejeod']?>"placeholder="°"/></td>
							<td><input type="number"name="adiciond"min="0"max="3"step=".25"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['adiciond']:$row['ant'][0]['adiciond']?>"/></td>
							<td><input type="number"name="dpod"min="0"max="80"step=".1"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['dpod']:$row['ant'][0]['dpod']?>"/></td>
							<td><input type="number"name="alturaod"min="0"max="80"step=".1"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['alturaod']:$row['ant'][0]['alturaod']?>"/></td>
						</tr>
						<tr>
							<td><i class="fa fa-eye"></i><br/><label>I</label></td>
							<td><input type="number"name="esferaoi"min="-20"max="20"step=".25"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['esferaoi']:$row['ant'][0]['esferaoi']?>"/></td>
							<td><input type="number"name="cilindroi"min="-20"max="0"step=".25"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['cilindroi']:$row['ant'][0]['cilindroi']?>"/></td>
							<td><input type="number"name="ejeoi"min="0"max="180"step="1"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['ejeoi']:$row['ant'][0]['ejeoi']?>"placeholder="°"/></td>
							<td><input type="number"name="adicioni"min="0"max="3"step=".25"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['adicioni']:$row['ant'][0]['adicioni']?>"/></td>
							<td><input type="number"name="dpoi"min="0"max="80"step=".1"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['dpoi']:$row['ant'][0]['dpoi']?>"/></td>
							<td><input type="number"name="alturaoi"min="0"max="80"step=".1"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['alturaoi']:$row['ant'][0]['alturaoi']?>"/></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-3"><span class="label label-default"><i class="fa fa-circle"></i> Agudeza visual final</span></div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Derecho</label><br/>
				<input type="text"name="avfod"maxlength="12"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['avfod']:$row['ant'][0]['avfod']?>"/>
			</div>
			<div class="col-xs-2">
				<i class="fa fa-eye"></i> <label>Izquierdo</label><br/>
				<input type="text"name="avfoi"maxlength="12"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['avfoi']:$row['ant'][0]['avfoi']?>"/>
			</div>
			<div class="col-xs-4">
				<i class="fa fa-eye"></i><i class="fa fa-eye"></i> <label>Ambos</label><br/>
				<input type="text"name="avf2o"maxlength="25"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['avf2o']:$row['ant'][0]['avf2o']?>"/>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-2">
				<span class="label label-default"><i class="fa fa-circle"></i> Lente de contacto</span><br/><br/>
				<small>(Marca)</small>
			</div>
			<div class="col-xs-6"><br/>
				<input type="text"name="lcmarca"maxlength="70"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['lcmarca']:$row['ant'][0]['lcmarca']?>"/>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-2">
				<br/><small>(graduaci&oacute;n)</small>
			</div>
			<div class="col-xs-4">
				<i class="fa fa-eye"></i> <label>Derecho</label><br/>
				<input type="text"name="lcgod"maxlength="30"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['lcgod']:$row['ant'][0]['lcgod']?>"/>
			</div>
			<div class="col-xs-4">
				<i class="fa fa-eye"></i> <label>Izquierdo</label><br/>
				<input type="text"name="lcgoi"maxlength="30"class="form-control input-xs"value="<?php echo $row['now']['status']?$row['now']['lcgoi']:$row['ant'][0]['lcgoi']?>"/>
			</div>
		</div>
		<hr/>
		<?php $txoptico=$row['now']['status']?json_decode($row['now']['txoptico'],true):json_decode($row['ant'][0]['txoptico'],true);
		if(is_array($txoptico)&&count($txoptico)==1){
			$lente1 = $txoptico[0];
			$lente2 = array();
		} elseif(is_array($txoptico)&&count($txoptico)==2){
			$lente1 = $txoptico[0];
			$lente2 = $txoptico[1];
		} elseif(is_array($txoptico)&&count($txoptico)>2){
			$lente1 = $txoptico;
			$lente2 = array();
		} else {
			$lente1 = array();
			$lente2 = array();
		}
		?>
		<div class="row">
			<div class="col-xs-4">
				<span class="label label-default"><i class="fa fa-circle"></i> Tipo de lente</span><br/><br/>
				<select name="txoptico[tipo][]"id="txoptico_tl0"class="form-control">
					<option value="0">Sin Lente</option>
					<? foreach($catid AS $key => $val):?>
					<option value="<?=$key?>"<?=(count($lente1)&&$lente1['tipo'][0]==$key)?' selected':''?>><?=$key?></option>
					<?endforeach?>
				</select>
			</div>
			<div class="col-xs-4<?=(is_array($lente1))?'':' hide'?>">
				<span class="label label-default"><i class="fa fa-circle"></i> Material</span><br/><br/>
				<select name="txoptico[material][]"id="txoptico_ma0"class="form-control">
					<?if(is_array($catid[$lente1['tipo'][0]]) && count($catid[$lente1['tipo'][0]])){?>
						<?foreach($catid[$lente1['tipo'][0]] AS $key=>$val):?>
						<option value="<?=$key?>"<?=($lente1['material'][0]==$key)?' selected':''?>><?=$key?></option>
						<?endforeach?>
					<?}else{?>
						<option value="0">Sin material</option>
					<?}?>
				</select>
			</div>
			<div class="col-xs-4<?=(is_array($lente1))?'':' hide'?>">
				<span class="label label-default"><i class="fa fa-circle"></i> Tratamiento</span><br/><br/>
				<select name="txoptico[tx][]"id="txoptico0"class="form-control">
					<?if(is_array($catid[$lente1['tipo'][0]][$lente1['material'][0]]) && count($catid[$lente1['tipo'][0]][$lente1['material'][0]])){?>
						<?foreach($catid[$lente1['tipo'][0]][$lente1['material'][0]] AS $key=>$val):?>
						<option value="<?=$key?>"<?=($lente1['tx'][0]==$key)?' selected':''?>><?=$key?></option>
						<?endforeach?>
					<?}else{?>
						<option value="0">Sin tratamiento</option>
					<?}?>
				</select>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-4">
				<select name="txoptico[tipo][]"id="txoptico_tl1"class="form-control">
					<option value="0">Sin Lente</option>
					<?foreach($catid AS $key => $val):?>
					<option value="<?=$key?>"<?=(count($lente2)&&$lente2['tipo']==$key)?' selected':''?>><?=$key?></option>
					<?endforeach?>
				</select>
			</div>
			<div class="col-xs-4<?=(is_array($lente2))?'':' hide'?>">
				<select name="txoptico[material][]"id="txoptico_ma1"class="form-control">
					<?if(is_array($lente2) && count($lente2)){?>
						<?foreach($catid[$lente2['tipo']] AS $key=>$val):?>
						<option value="<?=$key?>"<?=($lente2['material'][1]==$key)?' selected':''?>><?=$key?></option>
						<?endforeach?>
					<?}else{?>
						<option value="0">Sin material</option>
					<?}?>
				</select>
			</div>
			<div class="col-xs-4<?=(is_array($lente2))?'':' hide'?>">
				<select name="txoptico[tx][]"id="txoptico1"class="form-control">
					<?if(is_array($lente2) && count($lente2)){?>
						<?foreach($catid[$lente2['tipo']][$lente2['material']] AS $key=>$val):?>
						<option value="<?=$key?>"<?=($lente2['tx'][1]==$key)?' selected':''?>><?=$key?></option>
						<?endforeach?>
					<?}else{?>
						<option value="0">Sin tratamiento</option>
					<?}?>
				</select>
			</div>
		</div>
		<hr/>
		<div class="row">
			<div class="col-xs-2"><span class="label label-default"><i class="fa fa-circle"></i> Observaciones</span></div>
			<div class="col-xs-10">
				<textarea name="observaciones"class="form-control"rows="2"><?php echo $row['now']['status']?$row['now']['observaciones']:$row['ant'][0]['observaciones']?></textarea>
			</div>
		</div>
		<input type="hidden"name="idclient"id="idclient"value="<?=$row['now']['idclient']?>"/>
		<input type="hidden"name="status"id="status"value=1/>
		<input type="hidden"name="id" value="<?=$row['now']['id']?>"/>
		<input type="hidden"name="oper" value="<?=($row['now']['id'])?'edit':'add'?>"/>
		</form>
	</div>
  </div>
</div>

<div id="dialog-form-new" title="Historial">
	<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"></div>
</div>
<input type="hidden"id="anterior"value="<?=htmlentities(json_encode($row['ant']))?>"/>
<input type="hidden"id="catid"value="<?=htmlentities(json_encode($catid))?>"/>
