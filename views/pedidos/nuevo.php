<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formsave','id'=>'formsave')?>
<?=form_open('pedidos/savePedido',$atributos)?>
	<div class="row">
		<div class="col-sm-4">
			<div class="box box-primary">
				<div class="box-header"><span class="glyphicon glyphicon-user"></span> 
					<h3 class="box-title">
						Paciente 
						<span class="label label-success"><?php echo $contacto['id']?></span>
					</h3>
				</div>
				<div class="box-body chart-responsive">
					<div class="col-xs-12"><h6><?php echo $contacto['contacto']?></h6></div>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header"><span class="glyphicon glyphicon-inbox"></span> 
					<h3 class="box-title">Venta</h3>
				</div>
				<div class="box-body chart-responsive">
					<div class="row">
						<div class="col-xs-7"><label>Sub-total</label></div>
						<div class="col-xs-5">
							<input type="number"name="subtotal"id="subtotal"min="0"max="99999.99"pattern="[0-9\.\,]{1,5}"step="0.01"value="0"class="form-control input-sm"/>
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-xs-7"><label>Descuento</label></div>
						<div class="col-xs-5">
							<input type="number"name="descuento"id="descuento"min="0"max="99999.99"pattern="[0-9\.\,]{1,5}"step="0.01"value="0"class="form-control input-sm"/>
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-xs-7"><label>Anticipo</label></div>
						<div class="col-xs-5">
							<input type="number"name="anticipo"id="anticipo"min="0"max="99999.99"pattern="[0-9\.\,]{1,5}"step="0.01"value="0"class="form-control input-sm"/>
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-xs-7"><label>Metodo de pago</label></div>
						<div class="col-xs-5">
							<select class="form-control input-sm"name="metodopago"id="p_metodopago">
								<option value="EFECTIVO">EFECTIVO</option>
								<option value="CHEQUE">CHEQUE</option>
								<option value="TARJETA DE CREDITO">TARJETA DE CREDITO</option>
								<option value="TARJETA DE DEBITO">TARJETA DE DEBITO</option>
								<option value="TRANSFERENCIA BANCARIA">TRANSFERENCIA BANCARIA</option>
								<option value="NO IDENTIFICADO">NO IDENTIFICADO</option>
							</select>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-xs-7"><label>Total</label></div>
						<div class="col-xs-5">
							<input type="number"name="total"id="total"min="0"max="99999.99"pattern="[0-9\.\,]{1,5}"step="0.01"value="0"class="form-control input"/>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-8">
			<div class="box box-primary">
				<div class="box-header"><span class="glyphicon glyphicon-eye-open"></span> 
					<h3 class="box-title">Examen</h3>
					<div class="box-tools pull-right">
						<div class="input-group">
							<select class="form-control input-sm pull-right"id="examenes">
								<?php foreach ($examenes As $test):?>
								<option value="<?php echo $test['id']?>">[<?php echo $test['id']?>] <?php echo dateCut($test['date'])?></option>
								<?php endforeach;?>
							</select>
						</div>
					</div>
				</div>
				<div class="box-body chart-responsive">
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
							<td id="esferaod"></td>
							<td id="cilindrod"></td>
							<td id="ejeod"></td>
							<td id="adiciond"></td>
							<td><input type="number"min="0"max="80"step=".1"name="test[dpod]"id="dpod"class="form-control input-xs"/></td>
							<td><input type="number"min="0"max="80"step=".1"name="test[alturaod]"id="alturaod"class="form-control input-xs"/></td>
						</tr>
						<tr>
							<td><label>I</label></td>
							<td id="esferaoi"></td>
							<td id="cilindroi"></td>
							<td id="ejeoi"></td>
							<td id="adicioni"></td>
							<td><input type="number"min="0"max="80"step=".1"name="test[dpoi]"id="dpoi"class="form-control input-xs"/></td>
							<td><input type="number"min="0"max="80"step=".1"name="test[alturaoi]"id="alturaoi"class="form-control input-xs"/></td>
						</tr>
						</tbody>
					</table>
					<br/>
					<div class="pull-left image">
						<img src="<?=base_url('lib/images/1.png')?>"class="img-circle"alt="Medico"style="max-width:50px"/>
					</div>
					<div class="pull-left info"id="1-observaciones"style="padding-left:10px"></div>
				</div>
			</div>
			<div class="box box-primary">
				<div class="box-header"><span class="glyphicon glyphicon-tag"></span> 
					<h3 class="box-title">Pedido</h3>
				</div>
				<div class="box-body chart-responsive">
					<div class="row">
						<div class="col-xs-12"id="venta">
							<table id="gridcaja"></table>
						</div>
					</div>
					<br/>
					<div class="row">
						<div class="col-xs-6">
							<div class="input-group">
								<span class="input-group-addon glyphicon glyphicon-barcode"></span>
								<input type="text"class="form-control"id="code"placeholder="Codigo"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
							</div>
						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="row">
						<div class="col-xs-9">
							<label>Observaciones</label><br/>
							<textarea name="observaciones"class="form-control input-sm"rows="1"></textarea>
						</div>
						<div class="col-xs-3">
							<label>Entrega</label><br/>
							<input type="date"name="fentrega"id="fentrega"class="form-control input-sm"value="<?=date('Y-m-d',strtotime('+8 day'))?>"/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden"name="idcliente"value="<?php echo $contacto['id']?>"/>
	<input type="hidden"name="test[id]"id="test_id"value="0"/>
	<input type="hidden"name="banco"id="detalles_banco"value="{}"/>
	<input type="hidden"name="items"id="items"value="[]"/>
	<input type="hidden"name="oper"value="add"/>
	<input type="hidden"id="test_rows"value="<?php echo htmlentities(json_encode($examenes))?>"/>
</form>
<input type="hidden"id="catid"value="<?=htmlentities(json_encode($catid))?>"/>
