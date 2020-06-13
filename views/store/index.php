<table id="gridStore"></table>
<div id="navgrid"></div>
<!--Formulario de agregar-->
<div id="dialog-form-new" title="Nuevo lote"class="hide">
	<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formNew')?>
	<?=form_open('store/saveLot',$atributos)?>
		<div class="form-group">
			<label for="code"class="col-sm-5 control-label">Codigo corto o nombre</label>
			<div class="col-sm-7">
				<input type="text"pattern="[a-zA-Z0-9.-/+]{0,13}"name="code"id="code"maxlength="13"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
			</div>
		</div>
		<div class="form-group">
			<label for="date"class="col-sm-5 control-label">Fecha de ingreso</label>
			<div class="col-sm-4">
				<input type="date"class="form-control input-sm"name="date"id="date"disabled/>
			</div>
		</div>
		<div class="form-group">
			<label for="bill" class="col-sm-5 control-label">Numero de factura</label>
			<div class="col-sm-4">
				<input type="text"pattern="[a-zA-Z0-9/-]{0,20}"maxlength="20"class="form-control input-sm"name="bill"id="bill"disabled/>
			</div>
		</div>
		<div class="form-group">
			<label for="cost" class="col-sm-5 control-label">Costo</label>
			<div class="col-sm-3">
				<input type="number"pattern="[0-9\.]*"min="0"max="9999.99"step="0.01"class="form-control input-sm"name="cost"id="cost"disabled/>
			</div>
		</div>
		<div class="form-group">
			<label for="price" class="col-sm-5 control-label">Precio base</label>
			<div class="col-sm-3">
				<input type="number"pattern="[0-9\.]*"min="0"max="9999.99"step="0.01"class="form-control input-sm"name="price"id="price"disabled/>
			</div>
		</div>
		<div class="form-group">
			<label for="amount" class="col-sm-5 control-label">Cantidad</label>
			<div class="col-sm-3">
				<input type="number"pattern="[0-9]*"min="0"max="9999"maxlength="4"step="1"class="form-control input-sm"name="amount"id="amount"disabled/> 
			</div>
		</div>
		<div class="form-group">
			<label for="base64" class="col-sm-5 control-label">Codigo de barras</label>
			<div class="col-sm-7">
				<input type="text"class="form-control input-sm"name="base64"id="base64"disabled/>
			</div>
		</div>
		<input type="hidden"name="oper"value="add"/>
		<input type="hidden"name="id"value="0"/>
		<input type="hidden"name="ids"value="0"/>
	</form>
</div>
