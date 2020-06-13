<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formNew','id'=>'form-setting')?>
<?=form_open('settings/save',$atributos)?>
<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Emisor</a></li>
		<li><a href="#tabs-7">Telefonos</a></li>
		<li><a href="#tabs-2">Domicilio fiscal</a></li>
		<li><a href="#tabs-3">Expedido en</a></li>
		<li><a href="#tabs-6">Factura</a></li>
		<li><a href="#tabs-4">PAC</a></li>
		<li><a href="#tabs-5">SAT</a></li>
	</ul>
	<div id="tabs-1">
		<div class="form-group">
			<label for="rfc">RFC</label>
			<input type="text"class="form-control input-lg"name="emisor[rfc]"id="rfc"value="<?=(isset($row['emisor']['rfc']))?$row['emisor']['rfc']:''?>" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
		</div>
		<div class="form-group">
			<label for="nombre">Razon social</label>
			<input type="text"name="emisor[nombre]"id="nombre"class="form-control input-lg"value="<?=(isset($row['emisor']['nombre']))?$row['emisor']['nombre']:''?>" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
		</div>
		<div class="form-group">
			<label for="RegimenFiscal">Regimen fiscal</label>
			<input type="text"name="emisor[RegimenFiscal]"id="RegimenFiscal"class="form-control input-lg"value="<?=(isset($row['emisor']['RegimenFiscal']))?$row['emisor']['RegimenFiscal']:''?>" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
		</div>
		
	</div>
	<div id="tabs-2">
		<?$dom=(isset($row['emisor']['DomicilioFiscal']))?$row['emisor']['DomicilioFiscal']:'';
		if(!isset($dom['calle'])) $dom['calle']='';
		if(!isset($dom['noExterior'])) $dom['noExterior']='';
		if(!isset($dom['noInterior'])) $dom['noInterior']='';
		if(!isset($dom['colonia'])) $dom['colonia']='';
		if(!isset($dom['localidad'])) $dom['localidad']='';
		if(!isset($dom['municipio'])) $dom['municipio']='';
		if(!isset($dom['estado'])) $dom['estado']='COLIMA';
		if(!isset($dom['pais'])) $dom['pais']='MEXICO';
		if(!isset($dom['CodigoPostal'])) $dom['CodigoPostal']='';?>
		<div class="form-group">
			<label for="calle"class="col-sm-2 control-label">Calle</label>
			<div class="col-sm-7">
				<input type="text"name="emisor[DomicilioFiscal][calle]"id="calle"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=strtoupper($dom['calle'])?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="noExterior"class="col-sm-2 control-label">No. exterior</label>
			<div class="col-sm-2">
				<input type="number"name="emisor[DomicilioFiscal][noExterior]"id="noExterior"class="form-control input-sm"value="<?=$dom['noExterior']?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="noInterior"class="col-sm-2 control-label">No. interior</label>
			<div class="col-sm-2">
				<input type="text"name="emisor[DomicilioFiscal][noInterior]"id="noInterior"class="form-control input-sm"value="<?=$dom['noInterior']?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="colonia"class="col-sm-2 control-label">Colonia</label>
			<div class="col-sm-7">
				<input type="text"name="emisor[DomicilioFiscal][colonia]"id="colonia"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=strtoupper($dom['colonia'])?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="localidad"class="col-sm-2 control-label">Localidad</label>
			<div class="col-sm-7">
				<input type="text"name="emisor[DomicilioFiscal][localidad]"id="localidad"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=strtoupper($dom['localidad'])?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="municipio"class="col-sm-2 control-label">Municipio</label>
			<div class="col-sm-7">
				<input type="text"name="emisor[DomicilioFiscal][municipio]"id="municipio"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=strtoupper($dom['municipio'])?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="estado"class="col-sm-2 control-label">Estado</label>
			<div class="col-sm-7">
				<input type="text"name="emisor[DomicilioFiscal][estado]"id="estado"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=strtoupper($dom['estado'])?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="pais"class="col-sm-2 control-label">Pais</label>
			<div class="col-sm-7">
				<input type="text"name="emisor[DomicilioFiscal][pais]"id="pais"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=strtoupper($dom['pais'])?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="CodigoPostal"class="col-sm-2 control-label">C.P.</label>
			<div class="col-sm-2">
				<input type="number"name="emisor[DomicilioFiscal][CodigoPostal]"id="CodigoPostal"class="form-control input-sm"value="<?=$dom['CodigoPostal']?>"/>
			</div>
		</div>
	</div>
	<div id="tabs-3">
		<?$dom=(isset($row['emisor']['ExpedidoEn']))?$row['emisor']['ExpedidoEn']:'';
		if(!isset($dom['calle'])) $dom['calle']='';
		if(!isset($dom['noExterior'])) $dom['noExterior']='';
		if(!isset($dom['noInterior'])) $dom['noInterior']='';
		if(!isset($dom['colonia'])) $dom['colonia']='';
		if(!isset($dom['localidad'])) $dom['localidad']='';
		if(!isset($dom['municipio'])) $dom['municipio']='';
		if(!isset($dom['estado'])) $dom['estado']='';
		if(!isset($dom['pais'])) $dom['pais']='';
		if(!isset($dom['CodigoPostal'])) $dom['CodigoPostal']='';?>
		<div class="form-group">
			<label for="calle"class="col-sm-2 control-label">Calle</label>
			<div class="col-sm-7">
				<input type="text"name="emisor[ExpedidoEn][calle]"id="calle"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=strtoupper($dom['calle'])?>" />
			</div>
			<div class="col-sm-3">
				<button type="button" class="btn btn-info" onclick="copyDom()">Copiar domicilio fiscal</button>
			</div>
		</div>
		<div class="form-group">
			<label for="noExterior"class="col-sm-2 control-label">No. exterior</label>
			<div class="col-sm-2">
				<input type="number"name="emisor[ExpedidoEn][noExterior]"id="noExterior"class="form-control input-sm"value="<?=$dom['noExterior']?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="noInterior"class="col-sm-2 control-label">No. interior</label>
			<div class="col-sm-2">
				<input type="text"name="emisor[ExpedidoEn][noInterior]"id="noInterior"class="form-control input-sm"value="<?=$dom['noInterior']?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="colonia"class="col-sm-2 control-label">Colonia</label>
			<div class="col-sm-7">
				<input type="text"name="emisor[ExpedidoEn][colonia]"id="colonia"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=strtoupper($dom['colonia'])?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="localidad"class="col-sm-2 control-label">Localidad</label>
			<div class="col-sm-7">
				<input type="text"name="emisor[ExpedidoEn][localidad]"id="localidad"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=strtoupper($dom['localidad'])?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="municipio"class="col-sm-2 control-label">Municipio</label>
			<div class="col-sm-7">
				<input type="text"name="emisor[ExpedidoEn][municipio]"id="municipio"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=strtoupper($dom['municipio'])?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="estado"class="col-sm-2 control-label">Estado</label>
			<div class="col-sm-7">
				<input type="text"name="emisor[ExpedidoEn][estado]"id="estado"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=strtoupper($dom['estado'])?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="pais"class="col-sm-2 control-label">Pais</label>
			<div class="col-sm-7">
				<input type="text"name="emisor[ExpedidoEn][pais]"id="pais"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();" value="<?=strtoupper($dom['pais'])?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="CodigoPostal"class="col-sm-2 control-label">C.P.</label>
			<div class="col-sm-2">	
				<input type="number"name="emisor[ExpedidoEn][CodigoPostal]"id="CodigoPostal"class="form-control input-sm"value="<?=$dom['CodigoPostal']?>"/>
			</div>
		</div>
	</div>
	<div id="tabs-4">
		<?if(empty($row['PAC']['usuario']) || empty($row['PAC']['pass'])):?>
		<div class="alert alert-info" role="alert">
			<span class="glyphicon glyphicon-exclamation-sign"></span>
			El usuario y password sera establecido por el provedor, a mas tardar 24 Hrs.
		</div>
		<?endif?>
		<div class="form-group">
			<label for="usuario"class="col-sm-2 control-label">Usuario</label>
			<div class="col-sm-7">
				<input type="text" name="PAC[usuario]" id="usuario" class="form-control input-sm" value="<?=(isset($row['PAC']['usuario']))?$row['PAC']['usuario']:''?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="pass"class="col-sm-2 control-label">Password</label>
			<div class="col-sm-7">
				<input type="password" name="PAC[pass]" id="pass" class="form-control input-sm" value="<?=(isset($row['PAC']['pass']))?$row['PAC']['pass']:''?>"/>
			</div>
		</div>
		<div class="form-group">
			<label for="produccion"class="col-sm-2 control-label">Produccion</label>
			<div class="col-sm-7">
				<select name="PAC[produccion]" id="produccion" class="form-control" >
					<option value="NO"<?=(isset($row['PAC']['produccion']) && $row['PAC']['produccion']=='NO')?'selected':''?>>NO</option>
					<option value="SI"<?=(isset($row['PAC']['produccion']) && $row['PAC']['produccion']=='SI')?'selected':''?>>SI</option>
				</select>
			</div>
		</div>	
	</div>
	<div id="tabs-5">
		<div class="form-group">
			<label for="cer"class="col-sm-2 control-label">Certificado</label>
			<div class="col-sm-5">	
				<input type="text" name="PAC[cer]" id="cer" class="form-control input-lg" value="<?=(isset($row['PAC']['cer']))?$row['PAC']['cer']:''?>" readonly/>
			</div>
			<div class="col-sm-5">
				<input type="file" name="file_cer" class="form-control input-lg"/>
			</div>
		</div>
		<br/>
		<div class="form-group">
			<label for="key"class="col-sm-2 control-label">Key</label>
			<div class="col-sm-5">		
				<input type="text" name="PAC[key]" id="key" class="form-control input-lg" value="<?=(isset($row['PAC']['key']))?$row['PAC']['key']:''?>" readonly/>
			</div>
			<div class="col-sm-5">	
				<input type="file" name="file_key" class="form-control input-lg"/>
			</div>
		</div>
		<p>&nbsp;</p>
		<div class="form-group">
			<label for="pass"class="col-sm-2 control-label">Password</label>
			<div class="col-sm-5">	
				<input type="password" name="PAC[SAT][pass]" id="pass" class="form-control input-lg" value="<?=(isset($row['PAC']['SAT']['pass']))?$row['PAC']['SAT']['pass']:''?>"/>
			</div>
		</div>
	</div>
	<div id="tabs-6">
		<div class="form-group">
			<label for="folio"class="col-sm-2 control-label">Folio inicial</label>
			<div class="col-sm-5">
				<input type="number"class="form-control input-sm"name="factura[folio]"id="folio"value="<?=(isset($row['factura']['folio']))?$row['factura']['folio']:''?>" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
			</div>
		</div>
		<div class="form-group">
			<label for="serie"class="col-sm-2 control-label">Serie</label>
			<div class="col-sm-5">
				<input type="text"name="factura[serie]"id="serie"class="form-control input-sm"value="<?=(isset($row['factura']['serie']))?$row['factura']['serie']:''?>" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
			</div>
		</div>
	</div>
	<div id="tabs-7">
		<div class="form-group">
			<label for="rfc">Sucursal</label>
			<input type="tel"class="form-control input-lg"name="emisor[tel][sucursal]"id="sucursal"value="<?=(isset($row['emisor']['tel']['sucursal']))?$row['emisor']['tel']['sucursal']:''?>"/>
		</div>
		<div class="form-group">
			<label for="nombre">Quejas</label>
			<input type="text"name="emisor[tel][quejas]"id="quejas"class="form-control input-lg"value="<?=(isset($row['emisor']['tel']['quejas']))?$row['emisor']['tel']['quejas']:''?>"/>
		</div>
	</div>
</div>
</form>
