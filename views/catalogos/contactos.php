<div class="row">
	<div class="col-xs-10">&nbsp;</div>
	<div class="col-xs-2">
		<select id="typeclient"class="form-control input-sm">
			<option value="0">Cliente</option>
			<option value="1">Proveedor</option>
		</select>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-12">
		<table id="gridContacs"></table>
		<div id="navgrid"></div>
	</div>
</div>
<!--Formulario de contacto-->
<div id="dialog-form-new" title="Formulario de contacto"class="hide">
	<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formNew')?>
	<?=form_open('catalogos/saveContacto',$atributos)?>
		<div id="tabs">
			<ul>
				<li><a href="#tabs-4">Contacto</a></li>
				<li><a href="#tabs-1">Domicilio</a></li>
				<li><a href="#tabs-2">Telefonos</a></li>
				<li><a href="#tabs-3">Facturaci&oacute;n</a></li>
			</ul>
			<div id="tabs-1">
				<div class="form-group">
					<label for="calle" class="col-sm-2 control-label">Calle</label>
					<div class="col-sm-10">
						<input type="text"pattern="^[a-zA-Z0-9\s.,]{3,250}"class="form-control input-sm"name="dm[calle]"id="dmcalle"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
				</div>
				<div class="form-group">
					<label for="noExterior" class="col-sm-3 control-label">No. exterior</label>
					<div class="col-sm-3">
						<input type="number"min="0"max="99999"pattern="^[0-9]{1,5}"step="1"class="form-control input-sm"name="dm[noExterior]"id="dmnoExterior"/>
					</div>
					<label for="noInterior" class="col-sm-3 control-label">No. interior</label>
					<div class="col-sm-3">
						<input type="text"pattern="^[a-zA-Z0-9\s.]{1,5}"class="form-control input-sm"data-toggle="tooltip"title="Dejelo vacio si no tiene numero interior."name="dm[noInterior]"id="dmnoInterior"/>
					</div>
				</div>
				<div class="form-group">
					<label for="colonia" class="col-sm-2 control-label">Colonia</label>
					<div class="col-sm-4">
						<input type="text"pattern="^[a-zA-Z0-9\s.]{2,250}"class="form-control input-sm"name="dm[colonia]"id="dmcolonia"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
					<label for="localidad" class="col-sm-2 control-label">Localidad</label>
					<div class="col-sm-4">
						<input type="text"pattern="^[a-zA-Z0-9\s.]{2,250}"class="form-control input-sm"data-toggle="tooltip"title="Dejelo vacio si no es una localidad"name="dm[localidad]"id="dmlocalidad"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
				</div>
				<div class="form-group">
					<label for="municipio" class="col-sm-2 control-label">Municipio</label>
					<div class="col-sm-4">
						<input type="text"pattern="^[a-zA-Z0-9\s.]{2,250}"class="form-control input-sm"name="dm[municipio]"id="dmmunicipio"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
					<label for="estado" class="col-sm-2 control-label">Estado</label>
					<div class="col-sm-4">
						<input type="text"pattern="^[a-zA-Z\s.]{2,250}"class="form-control input-sm"name="dm[estado]"id="dmestado"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
				</div>
				<div class="form-group">
					<label for="pais" class="col-sm-2 control-label">Pais</label>
					<div class="col-sm-4">
						<input type="text"pattern="^[a-zA-Z\s]{2,250}"class="form-control input-sm"name="dm[pais]"id="dmpais"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
					<label for="CodigoPostal" class="col-sm-2 control-label">C.P.</label>
					<div class="col-sm-4">
						<input type="number"min="0"max="99999"pattern="[0-9]*"step="1"class="form-control input-sm"name="dm[CodigoPostal]"id="dmCodigoPostal"/>
					</div>
				</div>
			</div>
			<div id="tabs-2">
				<div class="form-group">
					<label for="particular" class="col-sm-2 control-label">Particular</label>
					<div class="col-sm-8">
						<input type="tel"pattern="[0-9]{10}"class="form-control input-sm"name="telnumber[particular]"id="particular"/>
					</div>
				</div>
				<div class="form-group">
					<label for="oficina" class="col-sm-2 control-label">Oficina</label>
					<div class="col-sm-8">
						<input type="tel"pattern="[0-9]{10}"class="form-control input-sm"name="telnumber[oficina]"id="oficina"/>
					</div>
				</div>
				<div class="form-group">
					<label for="mobil" class="col-sm-2 control-label">Mobil</label>
					<div class="col-sm-8">
						<input type="tel"pattern="[0-9]{10}"class="form-control input-sm"name="telnumber[mobil]"id="mobil"/>
					</div>
				</div>
			</div>
			<div id="tabs-3">
				<div class="form-group">
					<label for="rfc" class="col-sm-4 control-label">RFC</label>
					<label for="name" class="col-sm-8 control-label">Razon social</label>
				</div>
				<div class="form-group">
					<div class="col-sm-4">
						<input type="text"pattern="^[a-zA-Z0-9\ñ\Ñ]{10,13}"maxlength="13"class="form-control input-sm"name="rfc"id="rfc"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
					<div class="col-sm-8">
						<input type="text"pattern="^[a-zA-Z0-9\s\.\,\ñ\Ñ]{0,250}"maxlength="250"class="form-control input-sm"data-toggle="tooltip"title="Nombre de la persona fisica o moral"name="name"id="name"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
				</div>
				<div class="form-group">
					<label for="calle" class="col-sm-2 control-label">Calle</label>
					<div class="col-sm-10">
						<input type="text"pattern="^[a-zA-Z0-9\s.,]{3,250}"class="form-control input-sm"name="domicilio[calle]"id="calle"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
				</div>
				<div class="form-group">
					<label for="noExterior" class="col-sm-3 control-label">No. exterior</label>
					<div class="col-sm-3">
						<input type="number"min="0"max="99999"pattern="[0-9]{1,5}"step="1"class="form-control input-sm"name="domicilio[noExterior]"id="noExterior"/>
					</div>
					<label for="noInterior" class="col-sm-3 control-label">No. interior</label>
					<div class="col-sm-3">
						<input type="text"pattern="[a-zA-Z0-9\s.]{1,5}"class="form-control input-sm"data-toggle="tooltip"title="Dejelo vacio si no tiene numero interior."name="domicilio[noInterior]"id="noInterior"/>
					</div>
				</div>
				<div class="form-group">
					<label for="colonia" class="col-sm-2 control-label">Colonia</label>
					<div class="col-sm-4">
						<input type="text"pattern="^[a-zA-Z0-9\s.]{2,250}"class="form-control input-sm"name="domicilio[colonia]"id="colonia"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
					<label for="localidad" class="col-sm-2 control-label">Localidad</label>
					<div class="col-sm-4">
						<input type="text"pattern="^[a-zA-Z0-9\s.]{2,250}"class="form-control input-sm"data-toggle="tooltip"title="Dejelo vacio si no es una localidad"name="domicilio[localidad]"id="localidad"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
				</div>
				<div class="form-group">
					<label for="municipio" class="col-sm-2 control-label">Municipio</label>
					<div class="col-sm-4">
						<input type="text"pattern="^[a-zA-Z0-9\s.]{2,250}"class="form-control input-sm"name="domicilio[municipio]"id="municipio"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
					<label for="estado" class="col-sm-2 control-label">Estado</label>
					<div class="col-sm-4">
						<input type="text"pattern="^[a-zA-Z\s.]{2,250}"class="form-control input-sm"name="domicilio[estado]"id="estado"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
				</div>
				<div class="form-group">
					<label for="pais" class="col-sm-2 control-label">Pais</label>
					<div class="col-sm-4">
						<input type="text"pattern="^[a-zA-Z\s]{2,250}"class="form-control input-sm"name="domicilio[pais]"id="pais"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
					<label for="CodigoPostal" class="col-sm-2 control-label">C.P.</label>
					<div class="col-sm-4">
						<input type="number"min="0"max="99999"pattern="[0-9]*"step="1"class="form-control input-sm"name="domicilio[CodigoPostal]"id="CodigoPostal"/>
					</div>
				</div>	
			</div>
			<div id="tabs-4">
				<div class="form-group">
					<label for="contacto" class="col-sm-4 control-label">Nombre de contacto</label>
					<div class="col-sm-8">
						<input type="text"maxlength="250"data-toggle="tooltip"title="Nombre(s) de la persona a contactar"class="form-control input-sm"name="contacto"id="contacto"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
					</div>
				</div>
				<div class="form-group">
					<label for="email" class="col-sm-4 control-label">Correo electronico</label>
					<div class="col-sm-8">
						<input type="email"pattern="^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?"class="form-control input-sm"name="email"id="email"placeholder="@"/>
					</div>
				</div>
				<!----
				<div class="form-group">
					<label for="email" class="col-sm-4 control-label">Fecha de nacimiento</label>
					<div class="col-sm-4">
						<input type="date"class="form-control input-sm"name="birthday"id="birthday"/>
					</div>
				</div>
				-->
				<div class="form-group">
					<label for="type" class="col-sm-4 control-label">Tipo de contacto</label>
					<div class="col-sm-4">
						<select class="form-control input-sm"name="type"id="type">
							<option value="0">Cliente</option>
							<option value="1">Proveedor</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="oper" value="add"/>
		<input type="hidden" name="id" value="0"/>
	</form>
</div>
