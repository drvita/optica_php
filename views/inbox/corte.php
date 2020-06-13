<div class="row">
	<div class="col-xs-12 col-lg-4">
		<div class="box box-primary">
			<div class="box-header"><h3 class="box-title"><i class="fa fa-level-down"></i> Inicio</h3></div>
			<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'inicio','id'=>'inicio')?>
			<?=form_open('inbox/saveinoff',$atributos)?>
			<div class="box-body chart-responsive">
				<div class="row">
					<div class="col-xs-7"><label><i class="fa fa-money"></i> Efectivo</label></div>
					<div class="col-xs-5">
						<input type="number"name="efectivo"min="0"max="99999.90"step=".10"value="<?php echo isset($in['efectivo'])?$in['efectivo']:0 ?>"id="inCash"class="form-control input-sm"/>
					</div>
				</div>
			</div>
			<?php if(!isset($in['id'])||!$user['kindof']): ?>
			<div class="box-footer"id="infooter">
				<div class="row">
					<div class="col-xs-12">
						<button type="submit"class="pull-right btn btn-success"><i class="fa fa-save"></i> Guardar</button>
					</div>
				</div>
			</div>
			<?php endif?>
			<input type="hidden"name="type"value="0"/>
			<input type="hidden"name="id"value="<?php echo isset($in['id'])?$in['id']:0 ?>"/>
			</form>
		</div>
	</div>
	<div class="col-xs-12 col-lg-4">
		<div class="box box-info">
			<div class="box-header"><h3 class="box-title"><i class="fa fa-level-up"></i> Corte</h3></div>
			<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'corte','id'=>'corte')?>
			<?=form_open('inbox/saveinoff',$atributos)?>
			<div class="box-body chart-responsive">
				<div class="row">
					<div class="col-xs-7"><label><i class="fa fa-money"></i> Efectivo</label></div>
					<div class="col-xs-5">
						<input type="number"name="efectivo"id="offcash"min="0"max="99999.90"step=".10"value="<?php echo isset($off['efectivo'])?$off['efectivo']:0 ?>"id="offCash"class="form-control input-sm"/>
					</div>
				</div>
				<br/>
				<div class="row">
					<div class="col-xs-7"><label><i class="fa fa-credit-card"></i> Tarjetas</label></div>
					<div class="col-xs-5">
						<input type="number"name="tarjetas"min="0"max="99999.90"step=".10"value="<?php echo isset($off['tarjetas'])?$off['tarjetas']:0 ?>"class="form-control input-sm"/>
					</div>
				</div>
				
				<br/>
				<div class="row">
					<div class="col-xs-7"><label><i class="fa fa-tag"></i> Cheques</label></div>
					<div class="col-xs-5">
						<input type="number"name="cheques"min="0"max="99999.90"step=".10"value="<?php echo isset($off['cheques'])?$off['cheques']:0 ?>"class="form-control input-sm"/>
					</div>
				</div>
				
				<br/>
				<div class="row">
					<div class="col-xs-7"><label><i class="fa fa-shopping-cart"></i> Compras</label></div>
					<div class="col-xs-5">
						<input type="number"name="compras"min="0"max="99999.90"step=".10"value="<?php echo isset($off['compras'])?$off['compras']:0 ?>"class="form-control input-sm"/>
					</div>
				</div>
			</div>
			<?php if(!isset($off['id'])||!$user['kindof']||!$off['efectivo']||!$off['tarjetas']||!$off['compras']): ?>
			<div class="box-footer">
				<div class="row">
					<div class="col-xs-12">
						<button type="submit"class="pull-right btn btn-success"><i class="fa fa-save"></i> Guardar</button>
					</div>
				</div>
			</div>
			<?php endif?>
			<input type="hidden"name="type"value="1"/>
			<input type="hidden"name="id"value="<?php echo isset($off['id'])?$off['id']:0 ?>"/>
			</form>
		</div>
	</div>
	<div class="col-xs-12 col-lg-4">
		<div class="box box-info">
			<div class="box-header"><h3 class="box-title"><i class="fa fa-bitcoin"></i> Winzar</h3></div>
			<div class="box-body chart-responsive">
				<div class="row">
					<div class="col-xs-3"><label><i class="fa fa-usd"></i> 500</label></div>
					<div class="col-xs-4">
						<input type="number"min="0"max="99999"value="0"name="num500"class="form-control input-sm"/>
					</div>
					<div class="col-xs-5">
						<input type="number"min="0"max="99999"value="0"name="res500"class="form-control input-sm"readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3"><label><i class="fa fa-usd"></i> 200</label></div>
					<div class="col-xs-4">
						<input type="number"min="0"max="99999"value="0"name="num200"class="form-control input-sm"/>
					</div>
					<div class="col-xs-5">
						<input type="number"min="0"max="99999"value="0"name="res200"class="form-control input-sm"readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3"><label><i class="fa fa-usd"></i> 100</label></div>
					<div class="col-xs-4">
						<input type="number"min="0"max="99999"value="0"name="num100"class="form-control input-sm"/>
					</div>
					<div class="col-xs-5">
						<input type="number"min="0"max="99999"value="0"name="res100"class="form-control input-sm"readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3"><label><i class="fa fa-usd"></i> 50</label></div>
					<div class="col-xs-4">
						<input type="number"min="0"max="99999"value="0"name="num50"class="form-control input-sm"/>
					</div>
					<div class="col-xs-5">
						<input type="number"min="0"max="99999"value="0"name="res50"class="form-control input-sm"readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3"><label><i class="fa fa-usd"></i> 20</label></div>
					<div class="col-xs-4">
						<input type="number"min="0"max="99999"value="0"name="num20"class="form-control input-sm"/>
					</div>
					<div class="col-xs-5">
						<input type="number"min="0"max="99999"value="0"name="res20"class="form-control input-sm"readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3"><label><i class="fa fa-usd"></i> 10</label></div>
					<div class="col-xs-4">
						<input type="number"min="0"max="99999"value="0"name="num10"class="form-control input-sm"/>
					</div>
					<div class="col-xs-5">
						<input type="number"min="0"max="99999"value="0"name="res10"class="form-control input-sm"readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3"><label><i class="fa fa-usd"></i> 5</label></div>
					<div class="col-xs-4">
						<input type="number"min="0"max="99999"value="0"name="num5"class="form-control input-sm"/>
					</div>
					<div class="col-xs-5">
						<input type="number"min="0"max="99999"value="0"name="res5"class="form-control input-sm"readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-3"><label><i class="fa fa-usd"></i> 2</label></div>
					<div class="col-xs-4">
						<input type="number"min="0"max="99999"value="0"name="num2"class="form-control input-sm"/>
					</div>
					<div class="col-xs-5">
						<input type="number"min="0"max="99999"value="0"name="res2"class="form-control input-sm"readonly/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-7"><label><i class="fa fa-usd"></i> otro</label></div>
					<div class="col-xs-5">
						<input type="number"min="0"max="99999"value="0"name="res0"class="form-control input-sm"/>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
