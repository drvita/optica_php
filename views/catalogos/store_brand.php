<table id="gridstore"></table>
<div id="navgrid"></div>
<!--Formulario de agregar-->
<div id="dialog-form-new" title="Nueva categoria"class="hide">
	<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formNew')?>
	<?=form_open('catalogos/saveStoreBrand',$atributos)?>
		<div class="form-group">
			<label for="name">Proveedor</label>
			<select name="supplier"class="form-control input-lg">
				<?foreach($supplier AS $val):?>
				<option value="<?=$val['id']?>"><?=$val['contacto']?></option>
				<?endforeach?>
			</select>
		</div>
		<div class="form-group">
			<label for="name">Nombre de la marca</label>
			<input type="text"pattern="^[a-zA-Z0-9\s]{1,25}"name="name"id="name"maxlength="25"class="form-control input-lg"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
		</div>
		<input type="hidden" name="id" value="0"/>
		<input type="hidden" name="oper" value="add"/>
	</form>
</div>
