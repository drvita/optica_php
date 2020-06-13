<table id="gridPedidos"></table>
<div id="navgrid"></div>
<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formpedido','id'=>'formpedido')?>
<?=form_open('pedidos/pedido',$atributos)?>
	<input type="hidden"name="id"value="0"id="idpedido"/>
	<input type="hidden"name="year"value="<?php echo date('Y')?>"id="year"/>
</form>
