<ul class="nav nav-tabs"data-tabs="tabs"id="tabs">
	<li class="active"><a href="#tabs-1"data-toggle="tab">Positivo</a></li>
	<li><a href="#tabs-2"data-toggle="tab">Negativo</a></li>
</ul>
<div class="tab-content">
	<div class="tab-pane active"id="tabs-1">
	<div class="jumbotron">
		<table class="table table-striped table-condensed table-hover">
		<thead id="tabs-1-head">
			<tr>
				<th>Esf+</th>
				<?for($x=0;$x<=5;$x=$x+.25):?>
				<th><?=number_format($x,2,'.','')?></th>
				<?endfor?>
			</tr>
		</thead>
		<tbody>
			<?for($x=0;$x<=10;$x=$x+.25):?>
			<tr>
				<th><?=number_format($x,2,'.','')?></th>
				<?for($y=0;$y<=5;$y=$y+.25):
				$num="+".number_format($x,2,'.','')."-".number_format($y,2,'.','');?>
				<th>
					<input type="text"name="<?=$num?>"title="<?=$num?>"value="<?=(isset($items[$num]))?$items[$num]:''?>"/>
				</th>
				<?endfor?>
			</tr>
			<?endfor?>
		</tbody>
		</table>
	</div>
	</div>
	<div class="tab-pane"id="tabs-2">
	<div class="jumbotron">
		<table class="table table-striped table-condensed table-hover">
		<thead id="tabs-2-head">
			<tr>
				<th>Esf-</th>
				<?for($x=0.0;$x<=5;$x=$x+.25):?>
				<th><?=number_format($x,2,'.','')?></th>
				<?endfor?>
			</tr>
		</thead>
		<tbody>
			<?for($x=0.0;$x<=10;$x=$x+.25):?>
			<tr>
				<th><?=number_format($x,2,'.','')?></th>
				<?for($y=0.0;$y<=5;$y=$y+.25):
				$num="-".number_format($x,2,'.','')."-".number_format($y,2,'.','');?>
				<th>
					<input type="text"name="<?=$num?>"title="<?=$num?>"value="<?=(isset($items[$num]))?$items[$num]:''?>"/>
				</th>
				<?endfor?>
			</tr>
			<?endfor?>
		</tbody>
		</table>
	</div>
	</div>
</div>

<!--Formulario de agregar-->
<div id="dialog-form-new"title="Lente monofocal"class="hide">
	<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formNew')?>
	<?=form_open('catalogos/saveStoreItem',$atributos)?>
		<h3 id="title"></h3>
		<?foreach($catid as $row):?>
		<?foreach($row['tx'] as $val):?>
		<div class="form-group">
			<label class="col-sm-5 control-label"id="name-<?=$val['id']?>"for="<?=$val['id']?>"><?=$row['name']?></label>
			<span class="col-sm-4 label label-default"><?=$val['name']?></span>
			<div class="col-sm-3">
				<input type="number"min="0"max="999"pattern="[0-9]*"step="1"name="<?=$val['id']?>[cant]"id="<?=$val['id']?>"class="form-control input-sm"/>
				<input type="hidden"name="<?=$val['id']?>[precio]"value="<?=$val['precio']?>"/>
				<input type="hidden"name="<?=$val['id']?>[name]"value="<?=$row['name']?>-<?=$val['name']?>"/>
			</div>
		</div>
		<?endforeach?>
		<?endforeach?>
		<input type="hidden" name="grad" id="grad" value="0"/>
		<input type="hidden" name="id" value="0"/>
		<input type="hidden" name="oper" value="lente"/>
	</form>
</div>
