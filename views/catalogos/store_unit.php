<table id="gridstore"></table>
<div id="navgrid"></div>

<!--Formulario de agregar-->
<div id="dialog-form-new" title="Nueva categoria"class="hide">
	<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formNew')?>
	<?=form_open('catalogos/saveStoreUnit',$atributos)?>
		<div class="form-group">
			<label for="name">Nombre de la unidad</label>
			<input type="text"pattern="^[a-zA-Z\s]{1,4}"name="name"id="name"maxlength="4"class="form-control input"onkeyup="javascript:this.value=this.value.toUpperCase();"required/>
		</div>	
		<input type="hidden" name="id" value="0"/>
		<input type="hidden" name="oper" value="add"/>
	</form>
</div>
