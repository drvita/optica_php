<table id="gridUsers"></table>
<div id="navgrid"></div>

<!--Formulario de agregar-->
<div id="dialog-form-new" title="Buscar articulo">
	<?$atributos=array('class'=>'form-horizontal','role'=>'form','name'=>'formNew','autocomplete'=>"off")?>
	<?=form_open('home/saveUser',$atributos)?>
		<div class="form-group">
			<label for="name"class="col-sm-5 control-label">Nombre</label>
			<div class="col-sm-7">
				<input type="text"name="name"id="name"maxlength="25"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toUpperCase();"/>
			</div>
		</div>
		<div class="form-group">
			<label for="username"class="col-sm-5 control-label">Nombre de usuario</label>
			<div class="col-sm-7">
				<input type="text"pattern="^[a-zA-Z0-9\s.]{5,20}"name="username"id="username"maxlength="12"class="form-control input-sm"onkeyup="javascript:this.value=this.value.toLowerCase();"/>
			</div>
		</div>
		<div class="form-group">
			<label for="passr"class="col-sm-5 control-label">Password</label>
			<div class="col-sm-7">
				<input type="password"pattern="^[a-zA-Z0-9\s.#]{8,16}"name="pass"id="pass"maxlength="16"class="form-control input-sm"/>
			</div>
		</div>
		<div class="form-group">
			<label for="passr"class="col-sm-5 control-label">Repita el password</label>
			<div class="col-sm-7">
				<input type="password"pattern="^[a-zA-Z0-9\s.#]{8,16}"name="passr"id="passr"maxlength="16"class="form-control input-sm"/>
			</div>
		</div>
		<div class="form-group">
			<label for="kindof"class="col-sm-5 control-label">Tipo de usuario</label>
			<div class="col-sm-7">	
				<select name="kindof" id="kindof" class="form-control">
					<option value="2">Empleado</option>
					<option value="1">Doctor</option>
					<option value="0">Administrador</option>
				</select>
			</div>
		</div>
		<input type="hidden" name="iduser" value="0"/>
		<input type="hidden" name="oper" value="add"/>
	</form>
</div>
