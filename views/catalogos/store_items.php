<table id="gridStore"></table>
<div id="navgrid"></div>
<!--Formulario de agregar-->
<div id="dialog-form-new" title="Nuevo articulo"class="hide">
	<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formNew','autocomplete'=>'off')?>
	<?=form_open('catalogos/saveStoreItem',$atributos)?>
		<div class="form-group">
			<label class="col-sm-5 control-label"for="catid">Categoria</label>
			<div class="col-sm-7">
				<select name="catid"id="catid"class="form-control input-sm">
					<?foreach($catid AS $val):
						if($val['padrename']=='MONOFOCAL' || $val['namepadre']=='MONOFOCAL' || 
							$val['padrename']=='BIFOCAL' || $val['namepadre']=='BIFOCAL' ||
							$val['padrename']=='PROGRESIVO/STA' || $val['namepadre']=='PROGRESIVO/STA' ||
							$val['padrename']=='PROGRESIVO/PREMIUM' || $val['namepadre']=='PROGRESIVO/PREMIUM'
						) continue;?>
					<option value="<?=$val['id']?>"><?=empty($val['namepadre'])?'':$val['namepadre'].'-'?><?=$val['padrename']?>-<?=$val['name']?></option>
					<?endforeach?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5 control-label"for="supplier">Proveedor</label>
			<div class="col-sm-7">
				<select name="supplier"id="supplier"class="form-control input-sm">
					<?foreach($supplier AS $val):?>
					<option value="<?=$val['id']?>"><?=!empty($val['contacto'])? $val['contacto']:$val['name']?></option>
					<?endforeach?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5 control-label"for="brand">Marca</label>
			<div class="col-sm-7">	
				<select name="brand"id="brand"class="form-control input-sm"></select>
			</div>
		</div>
		<div class="form-group"id="store-group">
			<div class="col-sm-4">
				<label for="code">Codigo corto</label>
				<input type="text"pattern="^[a-zA-Z0-9\s\/\.\+\-]{4,18}"name="code"id="code"maxlength="18"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();"required/>
			</div>
			<div class="col-sm-4">
				<label for="price">Precio base</label>
				<input type="number"min="0"max="9999"step="1"value="0"class="form-control input-sm"name="price"id="preciobase"/>
			</div>
			<div class="col-sm-4">
				<label for="amount">Cantidad</label>
				<input type="number"min="0"max="9999"step="1"value="0"class="form-control input-sm"name="cant"/> 
			</div>
		</div>
		<input type="hidden"id="jsoncatid"value="<?=htmlentities(json_encode($catid))?>"/>
		<input type="hidden"name="name"id='name'value="Producto sin nombre"/>
		<input type="hidden"name="id"value="0"/>
		<input type="hidden"name="oper"value="add"id="oper"/>
	</form>
</div>
