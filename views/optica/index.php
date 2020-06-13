<table id="gridexamen"></table>
<div id="navgrid"></div>
<div id="dialog-form-new" title="Nuevo examen">
	<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formNew')?>
	<?=form_open('optica/saveExamenes',$atributos)?>
		<div class="form-group">
			<label for="contacto">Cliente</label>
			<input type="text"name="contacto"id="contacto"class="form-control input-lg"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
			<input type="hidden" name="idclient" id="idclient" value="0"/>
			<input type="hidden" name="oper" value="add"/>
			<input type="hidden" name="status" value="0"/>
		</div>
	</form>
</div>
<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'opentest','id'=>'opentest')?>
<?=form_open('optica/examen',$atributos)?>
	<input type="hidden" name="id" value="0"/>
</form>
