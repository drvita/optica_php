<div class="row">
	<div class="col-md-2">
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="glyphicon glyphicon-list-alt"></span> Mes
			</div>
			<div class="panel-body">
				<form class="form" role="form">
					<div class="form-group">
						<select class="form-control input-lg" name="mes" id="varmes">
						<?for($i=1; $i<=12; $i++){?>
							<option value="<?=sprintf("%02u", $i)?>"<?=($mes==$i)?'selected':''?>><?=sprintf("%02s", $i)?></option>
						<?}?>
						</select>
					</div>
					<div class="form-group">
						<select class="form-control input-lg" name="anio" id="varanio">
						<?for($i = $start; $i <= $end; $i++){?>
							<option value="<?=$i?>"<?=($anio==$i)?'selected':''?>><?=$i?></option>
						<?}?>
						</select>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="col-md-10">	
		<table id="gridIngresos"></table>
		<div id="navbar"></div>
	</div>
</div>

<?$atributos=array('role'=>'form','name'=>'sendto','id'=>'sendto')?>
<?=form_open('',$atributos)?>
	<input type="hidden"name="uuid"/>
</form>
<!--Confirmacion de cancelacion-->
<div id="dialog-confirm" title="Factura">
	<p>
		<span class="ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
		Esta seguro de cancelar esta factura
	</p>
</div>
<!--Correo electronico-->
<div id="dialog-email" title="Factura">
	<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formEmail','id'=>'formEmail')?>
	<?=form_open('ingresos/send_email',$atributos)?>
		<div class="form-group">
			<label class="control-label" for="diremail">Email</label>
			<input type="text"class="form-control"id="diremail"name="diremail"placeholder="Escriba el email"/>
		</div>
		<div class="form-group">
			<label class="control-label" for="msg">Mensaje</label>
			<textarea class="form-control"rows="3"id="msg"name="msg"></textarea>
		</div>
		<div class="form-group">
			<label class="control-label">Archivos:</label><br/>
			<label class="control-label" id="xmlfile">xml</label><br/>
			<label class="control-label" id="pdffile">pdf</label>
		</div>
		<input type="hidden"name="uuid"id="email_uuid"value=""/>
	</form>
</div>
<!--Opciones-->
<div id="dialog-opciones" title="Factura">
	<div class="row bs-glyphicons">
		<div class="col-xs-2 col-md-2"id="topdf">
			<a href="javascript:void(0)" class="thumbnail">
				<span class="glyphicon glyphicon-file" aria-hidden="true"></span>
				Impresion PDF
			</a>
		</div>
		<div class="col-xs-2 col-md-2"id="toemail">
			<a href="javascript:void(0)" class="thumbnail">
				<span class="glyphicon glyphicon-send" aria-hidden="true"></span>
				Enviar correo
			</a>
		</div>
		<div class="col-xs-2 col-md-2"id="tozip">
			<a href="javascript:void(0)" class="thumbnail">
				<span class="glyphicon glyphicon-save" aria-hidden="true"></span>
				Descargar ZIP
			</a>
		</div>
		<div class="col-xs-2 col-md-2"id="toedit">
			<a href="javascript:void(0)" class="thumbnail">
				<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
				Editar
			</a>
		</div>
		<div class="col-xs-2 col-md-2"id="tocancel">
			<a href="javascript:void(0)" class="thumbnail">
				<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
				Cancelar
			</a>
		</div>
	</div>
</div>
<!--Formulario de agregar-->
<div id="dialog-form-factura" title="Factura">
	<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formFactura','id'=>'formFactura')?>
	<?=form_open('ingresos/timbrar',$atributos)?>
	<div id="tabFacs">
		<ul>
			<li><a href="#tabs-1">1) Datos de factura</a></li>
			<li><a href="#tabs-2">2) Cliente a facturar</a></li>
			<li><a href="#tabs-3">3) Productos vendidos</a></li>
		</ul>
		<div id="tabs-1">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="tipo">Tipo</label>
			<div class="col-sm-4">
				<select class="form-control input-sm" name="tipo" id="tipo">
					<option value="FACTURA">FACTURA</option>
					<option value="RECIBOH">RECIBO DE HONORARIOS</option>
				</select>
			</div>
			<label class="col-sm-2 control-label" for="fecha_expedicion">Fecha</label>
			<div class="col-sm-4">
				<input class="form-control input-sm"type="datetime"name="factura[fecha_expedicion]"id="fecha_expedicion"value="<?=date('Y-m-d H:i:s',strtotime('-1 hour',strtotime(date('Y-m-d H:i:s'))))?>"readonly/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="forma_pago">Forma pago</label>
			<div class="col-sm-4">
				<select class="form-control input-sm" name="factura[forma_pago]" id="forma_pago">
					<option value="PAGO EN UNA SOLA EXHIBICION">PAGO EN UNA SOLA EXHIBICION</option>
					<option value="CREDITO">CREDITO</option>
				</select>
			</div>
			<label class="col-sm-2 control-label" for="metodo_pago">Metodo de pago</label>
			<div class="col-sm-4">
				<select class="form-control input-sm" name="factura[metodo_pago]" id="metodo_pago">
					<option value="EFECTIVO">EFECTIVO</option>
					<option value="CHEQUE">CHEQUE</option>
					<option value="TARJETA DE CREDITO">TARJETA DE CREDITO</option>
					<option value="TRANSFERENCIA BANCARIA">TRANSFERENCIA BANCARIA</option>
					<option value="NO IDENTIFICADO">NO IDENTIFICADO</option>
				</select>
			</div>	
		</div>
		<div class="form-group hide" id="grp-NumCtaPago">
			<label class="col-sm-2 control-label" for="NumCtaPago">Num. Cta. Pago</label>
			<div class="col-sm-4">
				<input class="form-control input-sm" disabled="disabled" type="number" name="factura[NumCtaPago]" id="NumCtaPago" title="Teclear los ultimos 4 digitos de la cuenta, cuando el metodo de pago sea transferencia"/>
			</div>
		</div>
		</div>
		<div id="tabs-2">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="rfc">RFC</label>
			<div class="col-sm-4">
				<input class="form-control"type="search"name="receptor[rfc]"id="rfc"onkeyup="javascript:this.value=this.value.toUpperCase();"required/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="nombre">Razon social</label>
			<div class="col-sm-10">
				<input class="form-control" type="text" name="receptor[nombre]" id="nombre" title="Este campo no se puede modificar" readonly/>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="domicilio">Domicilio</label>
			<div class="col-sm-10">	
				<input class="form-control" type="text" name="domicilio" id="domicilio" title="Este campo no se puede modificar" readonly/>
			</div>
		</div>
		<input type="hidden" name="receptor[Domicilio]" id="domicilioFiscal"/>
		</div>
		<div id="tabs-3">
			<div class="row">
			<div class="col-sm-12">
				<input type="text"id="codigo"style="width:94px"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
				<input type="number"pattern="^[0-9\,\.]*"min="0"max="9999"id="cantidad"style="width:60px"title="Cantidad de productos"/>
				<select id="unidad"style="width:60px">
					<?foreach($unit AS $value):?>
					<option value="<?=$value?>"><?=$value?></option>
					<?endforeach?>
					<option value="NO APLICA">NO APLICA</option>
				</select>
				<input type="text"id="descripcion"pattern="^[a-zA-Z0-9\.\,\/\-\s\(\)\+]{1,200}"style="width:240px"onkeyup="javascript:this.value=this.value.toUpperCase();"title="Describa el producto o servicio"/>
				<input type="number"pattern="^[0-9\,\.]*"min="0"max="99999"id="unitario"style="width:120px"title="Precio por producto"/>
				<button class="btn btn-primary btn-xs"style="width:90px"type="button"id="addimport">Agregar</button>
			</div>
			<div class="col-sm-12">
				<table id="gridConceptos"></table>
				<div id="navbar"></div>
			</div>
			</div>
		</div>
	</div>
	<p>&nbsp;</p>
	<div class="row nav-total">
		<div class="panel panel-default">
			<div class="panel-heading">
				<span class="glyphicon glyphicon-euro"></span> Total
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-3">
						<label>Subtotal</label> 
						<input type="number" id="subtotal" class="form-control" name="factura[subtotal]" readonly/>
					</div>
					<div class="col-md-2">
						<label>IVA</label> 
						<input type="number" id="tra-iva" class="form-control" name="impuestos[translados][0][importe]" readonly/>
						<input type="hidden" name="impuestos[translados][0][impuesto]" value="IVA"/>
						<input type="hidden" id="tra-iva-tasa"name="impuestos[translados][0][tasa]" value="16.00"/>
					</div>
					<div class="col-md-2 hide" id="col-iva">
						<label>RET. IVA</label> 
						<input type="number" id="ret-iva" class="form-control" name="impuestos[retenidos][0][importe]" disabled="disabled" readonly/>
						<input type='hidden' name="impuestos[retenidos][0][impuesto]" value="IVA" disabled="disabled"/>
						<input type='hidden' id="ret-iva-tasa"name="impuestos[retenidos][0][tasa]" value="16.00" disabled="disabled"/>
					</div>
					<div class="col-md-2 hide" id="col-isr">
						<label>RET. ISR</label> 
						<input type="number" id="ret-isr" class="form-control" name="impuestos[retenidos][1][importe]" disabled="disabled" readonly/>
						<input type='hidden' name="impuestos[retenidos][1][impuesto]" value="ISR" disabled="disabled"/>
						<input type='hidden' id="ret-isr-tasa"name="impuestos[retenidos][1][tasa]" value="0.00" disabled="disabled"/>
					</div>
					<div class="col-md-3">
						<label>Total</label>
						<input type="number" class="form-control" name="factura[total]" id="total" readonly/>
					</div>
				</div>	
			</div>
		</div>
	</div>
	<input type='hidden' name='factura[serie]'id='serie'value=""/>
	<input type='hidden' name='factura[folio]'id='folio'value=""/>
	<input type='hidden' name='factura[moneda]'value="MXN"/>
	<input type='hidden' name='factura[tipocambio]'value="1.0"/>
	<input type='hidden' name='factura[tipocomprobante]'value="ingreso"/>
	<input type='hidden' name='conceptos'id='conceptos'/>
	<input type='hidden' name='timbrar'id='timbrar'value="0"/>
	<input type='hidden' name='id'value="0"/>
	</form>
</div>
