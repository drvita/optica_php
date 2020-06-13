<table id="gridCatid"></table>
<div id="navgrid"></div>
<!--Formulario de agregar-->
<div id="dialog-form-new" title="Nueva categoria"class="hide">
	<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formNew')?>
	<?=form_open('catalogos/saveCatid',$atributos)?>
		<div class="form-group">
			<label for="name">Categoria padre</label>
			<select name="padre"id="padre"class="form-control input-sm"></select>
		</div>	
		<div class="form-group">
			<label for="name">Nombre de la categoria</label>
			<input type="text"pattern="^[a-zA-Z0-9\s\/\-]{1,25}"name="name"id="name"maxlength="25"class="form-control input-lg"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
		</div>
		<div class="form-group">
			<label for="name">Codigo</label>
			<input type="text"pattern="^[a-zA-Z0-9]{1,6}"name="code"id="code"maxlength="6"class="form-control input-lg"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
		</div>
		<div class="form-group">
			<label for="price">Precio general</label>
			<input type="number"step="1"min="0"max="99999"name="precio" id="precio"class="form-control input-lg"value="0.0"/>
		</div>	
		<input type="hidden" name="id" value="0"/>
		<input type="hidden" id="rowid" value="0"/>
		<input type="hidden" name="oper" value="add"/>
	</form>
</div>
