<div class="row">
	<div class="col-xs-12"id="grid">
		<table id="gridVentas"></table>
		<div id="navgrid"></div>
	</div>
</div>
<?$atributos=array('name'=>'formservices','id'=>'formservices')?>
	<?=form_open('report/print_nota',$atributos)?>
	<input type="hidden"name="idserver"id="idserver"value=0>
</form>
