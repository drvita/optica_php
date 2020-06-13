<div class="row">
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-body chart-responsive">
				<div class="input-group">
					<input type="text"class="form-control"id="cliente"placeholder="Cliente"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					<span class="input-group-btn">
						<button class="btn btn-default"type="button"id="remove-client">
							<span class="glyphicon glyphicon-remove"></span>
						</button>
					</span>
				</div>
				<br/>
				<div class="btn-group btn-group-sm"id="client-button">
					<button type="button"class="btn btn-default"id="goexamen"disabled>Nuevo Examen</button>
					<button type="button"class="btn btn-default"id="addPedido"disabled>Pedido</button>
				</div>
			</div>
		</div>
		<?$atributos=array('name'=>'formservices','id'=>'formservices')?>
		<?=form_open('report/print_nota',$atributos)?>
		<div class="box box-primary">
			<div class="box-body chart-responsive"id="panel-caja">
				<div class="row">
					<div class="col-xs-12">
						<div class="input-group">
							<span class="input-group-addon glyphicon glyphicon-barcode"></span>
							<input type="text"class="form-control"id="code"placeholder="Codigo"disabled='disabled' />
						</div>
					</div>
				</div><br/>
				<table id="gridcaja"></table>
				<p>&nbsp;</p>
				<div class="row">
					<div class="col-xs-5">
						<div class="input-group">
							<span class="input-group-addon glyphicon glyphicon-usd"></span>
							<input type="number"min="-99999.50"max="99999.50"step="0.50"value="0"name="montopago"class="form-control"/>
						</div>
					</div>
					<div class="col-xs-4">
						<select name="metodopago"class="form-control"id="c_metododepago">
							<option value="EFECTIVO">EFECTIVO</option>
							<option value="CHEQUE">CHEQUE</option>
							<option value="TARJETA DE CREDITO">TARJETA DE CREDITO</option>
							<option value="TARJETA DE DEBITO">TARJETA DE DEBITO</option>
							<option value="TRANSFERENCIA BANCARIA">TRANSFERENCIA BANCARIA</option>
							<option value="CREDITO">CREDITO</option>
							<option value="DESCUENTO">DESCUENTO</option>
						</select>
					</div>
					<div class="col-xs-3">
						<button type="button"id="c_pagar"class="btn btn-success">Cobrar</button>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden"name="idclient"id="idclient"value=0>
		<input type="hidden"name="idserver"id="idserver"value=0>
		<input type="hidden"name="serie"id="serie"value=0>
		<input type="hidden"name="idtest"id="idtest"value=0>
		<input type="hidden"name="items"id="items"value='[]'>
		<input type="hidden"name="total"id="venta_total"value=0>
		<input type="hidden"name="banco"id="c_banco"value='{}'>
		</form>
	</div>
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header">
				<h3 class="box-title">
					Pedidos terminados
					&nbsp;
					<small>Serie</small>
					<?php $fin=date('Y')*1+1;
					$x=2015; ?>
					<select name="year"id="year">
						<?php while ($x<$fin): ?>
						<option value="<?php echo $x; ?>" <?php if($x==date('Y')) echo "selected";?>><?php echo $x; ?></option>
						<?php $x++; 
						endwhile; ?>
					</select>
					&nbsp;
					<small>Registros</small>
					<select name="rows"id="rows">
						<option value="10">10</option>
						<option value="25">25</option>
						<option value="50" selected>50</option>
						<option value="100">100</option>
						<option value="250">200</option>
					</select>
				</h3>
			</div>
			<div class="box-body chart-responsive"id="list-pedidos"></div>
		</div>
	</div>
</div>
