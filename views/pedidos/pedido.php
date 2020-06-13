<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formsave','id'=>'formsave')?>
<?=form_open('pedidos/savePedido',$atributos)?>
	<div class="row">
		<div class="col-xs-7">
			<?if(is_array($pedido['test'])):?>
			<div id="tmt"class="box box-primary">
				<div class="box-header"><span class="glyphicon glyphicon-eye-open"></span>
					<h3 class="box-title">Examen
						<span class="badge"><?php echo $pedido['test']['id']?></span> 
						<?php echo $pedido['test']['date']!='0000-00-00'?dateCut($pedido['test']['date']) : $pedido['test']['date']?>
					</h3>
				</div>
				<div class="box-body chart-responsive">
					<?php if(is_string($pedido['test']['txoptico']['tipo'])):?>
					<div class="row">
						<div class="col-xs-4"><?=$pedido['test']['txoptico']['tipo']?></div>
						<div class="col-xs-4"><?=$pedido['test']['txoptico']['material']?></div>
						<div class="col-xs-4"><?=$pedido['test']['txoptico']['tx']?></div>
					</div>
					<?php endif;?>
					<table class="table table-bordered table-hover">
						<thead>
						<tr>
							<th style="width: 10px">#</th>
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
							<td><label>D</label></td>
							<td><?=floatval($pedido['test']['esferaod'])>0?'+'.$pedido['test']['esferaod']:$pedido['test']['esferaod']?></td>
							<td><?=$pedido['test']['cilindrod']?></td>
							<td><?=$pedido['test']['ejeod'].'°'?></td>
							<td><?=$pedido['test']['adiciond']?></td>
							<td><?=$pedido['test']['dpod']?></td>
							<td><?=$pedido['test']['alturaod']?></td>
						</tr>
						<tr>
							<td><label>I</label></td>
							<td><?=floatval($pedido['test']['esferaoi'])>0?'+'.$pedido['test']['esferaoi']:$pedido['test']['esferaoi']?></td>
							<td><?=$pedido['test']['cilindroi']?></td>
							<td><?=$pedido['test']['ejeoi'].'°'?></td>
							<td><?=$pedido['test']['adicioni']?></td>
							<td><?=$pedido['test']['dpoi']?></td>
							<td><?=$pedido['test']['alturaoi']?></td>
						</tr>
						</tbody>
					</table>
					<br/>
					<div class="pull-left image">
						<img src="<?=base_url('lib/images/1.png')?>"class="img-circle"alt="Medico"style="max-width:50px"/>
					</div>
					<div class="pull-left info"style="padding-left:10px">
						<?php echo !empty($pedido['test']['observaciones'])? $pedido['test']['observaciones']: 'Sin observaciones'?>
					</div>
				</div>
			</div>
			<?endif?>
			<div class="box box-primary">
				<div class="box-header"><span class="glyphicon glyphicon-inbox"></span> <h3 class="box-title">Venta</h3></div>
				<div class="box-body chart-responsive">
					<div class="row">
						<div class="col-xs-12"id="venta">
							<div class="input-group">
								<span class="input-group-addon glyphicon glyphicon-barcode"></span>
								<input type="text"class="form-control"id="code"placeholder="Codigo"onkeyup="javascript:this.value=this.value.toUpperCase();"<?=($pedido['status']==4)?' readonly ':''?>/>
							</div>
							<br/>
							<table id="gridcaja"></table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-5">
			<div class="box box-primary">
				<div class="box-header"><span class="glyphicon glyphicon-user"></span> <h3 class="box-title">Paciente
					<span class="badge"><?php echo $pedido['idcliente']?></span></h3>
				</div>
				<div class="box-body chart-responsive">
					<div class="col-xs-12"><h6><?php echo $pedido['contacto']?></h6></div>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header"><span class="glyphicon glyphicon-th-list"></span> 
					<h3 class="box-title">Pedido <span class="badge"><?=$pedido['id']?></span></h3></div>
				<div class="box-body chart-responsive"id="ppedido">
					<div class="row">
						<div class="col-xs-7"><label>Registro</label><br/><?=$pedido['date']!='0000-00-00'?dateCut($pedido['date']):'Sin fecha'?></div>
						<div class="col-xs-5"><label>Entregar</label><br/><?=$pedido['fentrega']!='0000-00-00'?dateCut($pedido['fentrega']):'Sin fecha'?></div>
					</div>
					<br/>
					<div class="pull-left image">
						<img src="<?=base_url('lib/images/2.png')?>"class="img-circle"alt="Operativo"style="max-width:50px"/>
					</div>
					<div class="pull-left info"style="padding-left:10px">
						<?php echo !empty($pedido['observaciones'])? $pedido['observaciones']: 'Sin observaciones'?>
					</div>
				</div>	
				<div class="panel-footer">	
					<div class="row">
						<div class="col-xs-8">
							<label>Estado</label><br/>
							<select name="status"id="statusname"class="form-control"<?=($pedido['status']==4)?' readonly':''?>>
								<option value="0"<?=$pedido['status']==0 ? ' selected':''?><?=($pedido['status']>0)?' disabled':''?>>En proceso</option>
								<option value="1"<?=$pedido['status']==1 ? ' selected':''?><?=($pedido['status']>1)?' disabled':''?>>En laboratorio</option>
								<option value="2"<?=$pedido['status']==2 ? ' selected':''?><?=($pedido['status']>2)?' disabled':''?>>En bicelaci&oacute;n</option>
								<option value="3"<?=$pedido['status']==3 ? ' selected':''?><?=($pedido['status']>3)?' disabled':''?>>Completo</option>
								<? if($pedido['status']==4){?>
								<option value="4" selected >Terminado</option>
								<? } ?>
							</select>
						</div>
						<div class="col-xs-4">
							<label>Caja</label><br/>
							<input type="number"name="ncaja"class="form-control"value="<?=$pedido['ncaja']?>"min="0"max="999"step="1"<?=($pedido['status']==4)?' readonly ':''?>/>
						</div>
					</div>
					<div class="row<?= ($pedido['status']!=1 && !$pedido['npedidolab']) ? ' hidden':''?>"id="laboratorio">
						<div class="col-xs-6">
							<label>Laboratorio</label><br/>
							<select name="laboratorio"class="form-control"id="laboratorio"<?=($pedido['status']==4)?' readonly ':''?>>
								<?foreach($supplier AS $val):
								if($val['id']==2081||$val['id']==2002){?>
								<option value="<?=$val['id']?>"<?=($pedido['laboratorio']==$val['id']||$val['id']==2002) ? ' selected':''?>><?=$val['contacto']?></option>
								<?} 
								endforeach?>
							</select>
						</div>
						<div class="col-xs-6">
							<label>N. folio Lab</label><br/>
							<input type="text"maxlength="12"name="npedidolab"id="npedidolab"class="form-control"value="<?=$pedido['npedidolab']?>"<?=($pedido['status']==4)?' readonly ':''?>/>
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-xs-2">
							<img src="<?=base_url('lib/images/0.png')?>"class="img-circle"alt="Medico"style="max-width:50px"/>
						</div>
						<div class="col-xs-10">
							<textarea name="mensajes"class="form-control"<?=($pedido['status']==4)?' readonly ':''?>><?=$pedido['mensajes']?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden"name="oper"value="edit"/>
	<input type="hidden"name="id"value="<?=$pedido['id']?>"/>
	<input type="hidden"name="pfx"value="<?=$pfx?>"/>
	<input type="hidden"name="items"id="items"value="<?=empty($pedido['items'])?'[]':htmlentities($pedido['items'])?>"/>
</form>
