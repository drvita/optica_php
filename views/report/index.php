<div class="row">
	<div class="col-lg-5 col-xs-12">
		<div class="box box-info">
			<div class="box-header">
				<div class="pull-right box-tools">
					<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'fecha','id'=>'form-fecha')?>
					<?=form_open('report',$atributos)?>
					<input type="date"name="date"id="date"class="form-control input-sm"value="<?php echo $date?>"/>
					</form>
				</div>
				<h3 class="box-title"><i class="fa fa-list-alt"></i> Resumen</h3>
			</div>
			<div class="box-body chart-responsive">
				<?php $eci = 0;
				foreach($corte AS $row): 
					if($row['type'] && $eci==0){
						$eci += $row['efectivo'] + $row['compras']; ?>
				<p>&nbsp;</p>
				<div class="row"><div class="col-xs-12">Corte final</div></div>
				<div class="row">
					<div class="col-xs-7"><label><i class="fa fa-money"></i> Efectivo</label></div>
					<div class="col-xs-5">
						<input type="number"value="<?php echo $row['efectivo'] ?>"class="form-control input-sm"readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-7"><label><i class="fa fa-credit-card"></i> Tarjetas</label></div>
					<div class="col-xs-5">
						<input type="number"value="<?php echo $row['tarjetas'] ?>"class="form-control input-sm"readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-7"><label><i class="fa fa-tag"></i> Cheques</label></div>
					<div class="col-xs-5">
						<input type="number"value="<?php echo $row['cheques'] ?>"class="form-control input-sm"readonly/>
					</div>
				</div>
				
				<div class="row">
					<div class="col-xs-7"><label><i class="fa fa-shopping-cart"></i> Compras</label></div>
					<div class="col-xs-5">
						<input type="number"value="<?php echo $row['compras'] ?>"class="form-control input-sm"readonly/>
					</div>
				</div>
				
				<?php } elseif(!$row['type']) {
						$eci -= $row['efectivo']; ?>
				<p>&nbsp;</p>
				<div class="row"><div class="col-xs-12">Caja chica inicial</div></div>
				<div class="row">
					<div class="col-xs-7"><label><i class="fa fa-money"></i> Efectivo</label></div>
					<div class="col-xs-5">
						<input type="number"value="<?php echo $row['efectivo'] ?>"class="form-control input-sm"readonly/>
					</div>
				</div>
				<?php }
				endforeach; ?>
				<p>&nbsp;</p>
				<div class="row"><div class="col-xs-12">Totales</div></div>
				<div class="row">
					<div class="col-xs-7"><label><i class="fa fa-dollar"></i> Faltante de efectivo</label></div>
					<div class="col-xs-5">
						<input type="number"value="<?php echo $eci ?>"id="sale-cash"class="form-control input-sm"readonly/>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-7 col-xs-12 hidden-print">
		<div class="box box-primary">
			<div class="box-header"><h3 class="box-title"><i class="fa fa-bar-chart-o"></i> Ingresos <small>ventas</small></h3></div>
			<div class="box-body chart-responsive">
				<div id="chartContainer"style="height:300px;width:100%;">
			</div>
		</div>
	</div>
</div>
<input type="hidden"id="ventas_metodos"value="<?php echo htmlentities(json_encode($ventas))?>"/>
<input type="hidden"id="venta_efectivo"value=""/>
