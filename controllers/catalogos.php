<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogos extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->plantillas->is_session();
		$this->load->model('home_model','catalogo');
		$this->load->helper('form');
	}
	//Pagina principal
	public function index(){
		$this->contact();
	}
	public function contact(){
		$data['top']['title']='Listado de contactos';
		$data['top']['cssf'][]['href']=base_url('lib/css/view/catalogos/contactos.css');
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.validate.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/catalogos/contactos.js');
		$data['top']['main'][]=array('click'=>'buscar()','class'=>'search','label'=>'Buscar');
		$data['top']['main'][]=array('click'=>'add()','class'=>'plus','label'=>'Nuevo','type'=>'success');
		$data['top']['main'][]=array('click'=>'edit()','class'=>'pencil','label'=>'Editar');
		$data['top']['main'][]=array('click'=>'eliminar()','class'=>'trash','label'=>'Eliminar');
		$this->plantillas->show_tpl('catalogos/contactos',$data);
	}
	public function store_catid(){
		$data['top']['title']='Categorias de almacen';
		$data['top']['cssf'][]['href']=base_url('lib/css/view/catalogos/store_catid.css');
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.validate.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/catalogos/store_catid.js');
		$data['top']['main'][]=array('click'=>'add()','class'=>'plus','label'=>'Nuevo','type'=>'success');
		$data['top']['main'][]=array('click'=>'edit()','class'=>'pencil','label'=>'Editar');
		$data['top']['main'][]=array('click'=>'eliminar()','class'=>'trash','label'=>'Eliminar');
		$this->plantillas->show_tpl('catalogos/store_catid',$data);
	}
	public function store_unit(){
		$data['top']['title']='Unidades de presentaci&oacute;n';
		$data['top']['cssf'][]['href']=base_url('lib/css/view/catalogos/store_unit.css');
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.validate.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/catalogos/store_unit.js');
		$data['top']['main'][]=array('click'=>'add()','class'=>'plus','label'=>'Nuevo','type'=>'success');
		$data['top']['main'][]=array('click'=>'edit()','class'=>'pencil','label'=>'Editar');
		$data['top']['main'][]=array('click'=>'eliminar()','class'=>'trash','label'=>'Eliminar');
		$this->plantillas->show_tpl('catalogos/store_unit',$data);
	}
	public function store_brand(){
		$data['top']['title']='Marcas de productos';
		$data['top']['cssf'][]['href']=base_url('lib/css/view/catalogos/store_brand.css');
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.validate.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/catalogos/store_brand.js');
		$data['top']['main'][]=array('click'=>'add()','class'=>'plus','label'=>'Nuevo','type'=>'success');
		$data['top']['main'][]=array('click'=>'edit()','class'=>'pencil','label'=>'Editar');
		$data['top']['main'][]=array('click'=>'eliminar()','class'=>'trash','label'=>'Eliminar');
		$sql="SELECT id,contacto,name FROM contactos WHERE type=1";
		$query = $this->db->query($sql);
		$data['supplier'] = $query->result_array();
		$this->plantillas->show_tpl('catalogos/store_brand',$data);
	}
	public function store_items(){
		$data['top']['title']='Productos de almacen';
		$data['top']['cssf'][]['href']=base_url('lib/css/view/catalogos/store_items.css');
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.validate.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/catalogos/store_items.js');
		$data['top']['main'][]=array('click'=>'buscar()','class'=>'search','label'=>'Buscar');
		$data['top']['main'][]=array('click'=>'add()','class'=>'plus','label'=>'Nuevo','type'=>'success');
		$data['top']['main'][]=array('click'=>'edit()','class'=>'pencil','label'=>'Editar');
		$data['top']['main'][]=array('click'=>'eliminar()','class'=>'trash','label'=>'Eliminar');
		$sql="SELECT c.*,k.name AS padrename,q.name AS namepadre,k.padre AS idpn FROM categorias AS c LEFT ".
		"JOIN categorias AS k ON c.padre=k.id LEFT JOIN categorias AS q ON k.padre=q.id WHERE c.padre>0 AND ".
		"c.precio>0 ORDER BY c.padre,c.name";
		$query = $this->db->query($sql);
		$data['catid']=$query->result_array();
		$sql="SELECT * FROM config WHERE name='store_unit'";
		$query = $this->db->query($sql); 
		$row = $query->row_array();
		$data['unit'] = json_decode($row['value'],true);
		$sql="SELECT id,contacto,name FROM contactos WHERE type=1";
		$query = $this->db->query($sql);
		$data['supplier'] = $query->result_array();
		$this->plantillas->show_tpl('catalogos/store_items',$data);
	}
	public function jsonContacts(){
		$page = $this->input->post('page');
		$page = (!$page)?1:$page;
		$limit = $this->input->post('rows');
		$limit = (!$limit)?12:$limit;
		$sidx =$this->input->post('sidx'); 
		$sidx = (!$sidx)?'name':$sidx;
		$sidx = $sidx=='dm'?'domicilio':$sidx;
		$sidx = $sidx=='tel'?'telnumber':$sidx;
		$sord = $this->input->post('sord');
		$sord = (!$sord)?"":$sord;
		$type = $this->input->post('type');
		$type = (!$type)?0:(int)$type;
		$search = $this->input->post('_search');
		$searchField = $this->input->post('searchField');
		$searchString = $this->input->post('searchString');
		$searchOper = $this->input->post('searchOper');
		$filters = $this->input->post('filters');
		$filters = !empty($filters) ? json_decode($filters,true) : array();
		if(empty($searchField)) $search='false';
		if($search=='true'){
			$where = $this->catalogo->getWhereClause($searchField,$searchOper,$searchString,$filters);
			$where .= " AND type=$type";
		} else $where = " WHERE type=$type";
		$sql="SELECT * FROM contactos$where";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$count = $query->num_rows();
		if( $count > 0 )
			$total_pages = ceil($count/$limit);
		else
			$total_pages = 0;
	
		if ($page > $total_pages)$page=$total_pages;
		if($page==0)$page=1;
		$start = $limit*$page - $limit;
		$sql="SELECT * FROM contactos$where ORDER BY $sidx $sord LIMIT $start,$limit";
		$query = $this->db->query($sql);
		$responce = (object) array();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count;
		$i=0;
		foreach($query->result_array() as $row){
			if(isset($row['id'])) $responce->rows[$i]['id']=$row['id'];
			foreach($row as $key=>$val){
				if($key==='domicilio'){
					$df = json_decode($val,true);
					if(is_array($df) && count($df)) $namedom = (!empty($df['calle']))?$df['calle']:'';
					else $namedom = null;
					if(!empty($namedom)){
						$namedom .= (!empty($df['noExterior']))?" ".$df['noExterior']:" SN";
						$namedom .= (!empty($df['noInterior']))?" (" .$df['noInterior'].")":"";
						$responce->rows[$i]['cell']["dm"]=$namedom;
					}
				}
				if($key==='domicilioFiscal' && !isset($responce->rows[$i]['cell']["dm"])){
					$df = json_decode($val,true);
					if(is_array($df) && count($df)){
						$namedom = (!empty($df['calle']))?$df['calle']:'';
						$namedom .= (!empty($df['noExterior']))?" ".$df['noExterior']:" SN";
						$namedom .= (!empty($df['noInterior']))?" (" .$df['noInterior'].")":"";
					} else $namedom = "Sin domicilio registrado";
					$responce->rows[$i]['cell']["dm"]=$namedom;
				}
				if($key==='telnumber'){
					$tel = json_decode($val,true);
					if(is_array($tel) && count($tel)){
						if(!empty($tel['particular'])) $responce->rows[$i]['cell']["tel"]=$tel['particular'];
						elseif(!empty($tel['oficina'])) $responce->rows[$i]['cell']["tel"]=$tel['oficina'];
						elseif(!empty($tel['mobil'])) $responce->rows[$i]['cell']["tel"]=$tel['mobil'];
					}
				}
				$responce->rows[$i]['cell'][$key]=$val;
			}
			$i++; 
		}
		$this->output
		->set_content_type('application/json;charset=utf-8')
		->set_output(json_encode($responce));
	}
	public function jsonCatid(){
		$page = $this->input->post('page');
		$page = (!$page)?1:$page;
		$limit = $this->input->post('rows');
		$sidx =$this->input->post('sidx');
		$sord = $this->input->post('sord');
		$type = $this->input->post('type');
		$type = (!$type)?0:(int)$type;
		$search = $this->input->post('_search');
		$searchField = $this->input->post('searchField');
		$searchString = $this->input->post('searchString');
		$searchOper = $this->input->post('searchOper');
		$filters = $this->input->post('filters');
		$filters = !empty($filters) ? json_decode($filters,true) : array();
		if(empty($searchField)) $search='false';
		if($search=='true'){
			$where = $this->catalogo->getWhereClause($searchField,$searchOper,$searchString,$filters);
		} else $where = "";
		$sql="SELECT c.* FROM categorias AS c LEFT JOIN categorias AS k ON c.padre=k.id ".
		"LEFT JOIN categorias AS q ON k.padre=q.id$where";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$count = $query->num_rows();
		if( $count > 0 )
			$total_pages = ceil($count/$limit);
		else
			$total_pages = 0;
	
		if ($page > $total_pages)$page=$total_pages;
		if($page==0)$page=1;
		$start = $limit*$page - $limit;
		$sql="SELECT c.*,k.name AS padrename,q.name AS namepadre,k.padre AS idpn FROM categorias AS c LEFT JOIN categorias AS k ON c.padre=k.id".
		" LEFT JOIN categorias AS q ON k.padre=q.id$where ORDER BY $sidx $sord,c.name LIMIT $start,$limit";
		$query = $this->db->query($sql);
		$responce = (object) array();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count;
		$i=0;
		foreach($query->result_array() as $row){
			if(isset($row['id'])) $responce->rows[$i]['id']=$row['id'];
			foreach($row as $key=>$val){
				if($key=='padrename'){ 
					if(empty($val)) $val="PRINCIPAL";
					else {
						if(empty($row['namepadre'])) $row['namepadre']="PRINCIPAL";
						$val=$row['namepadre'].'-'.$val;
					}
				}
				$responce->rows[$i]['cell'][$key]=$val;
			}
			$i++; 
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($responce));
	}
	public function jsonStoreUnit(){
		$page = $this->input->post('page');
		$page = (!$page)?1:$page;
		$limit = $this->input->post('rows');
		$limit = (!$limit)?12:$limit;
		
		$sql="SELECT * FROM config WHERE name='store_unit'";
		$query = $this->db->query($sql);
		$responce = (object) array();
		$responce->page = $page; 
		$row = $query->row_array();
		$value = json_decode($row['value'],true);
		$count = (int)count($value);
		if( $count > 0 )
			$total_pages = ceil($count/$limit);
		else
			$total_pages = 0;
	
		if($page > $total_pages)$page=$total_pages;
		if($page==0)$page=1;
		$start = $limit*$page - $limit;
		if($limit > $count) $limit = $count-1;
		$responce->total = $total_pages; 
		$responce->records = $count;
		$i=0;
		foreach($value AS $v){
			$responce->rows[$i]['id']=$v;
			$responce->rows[$i]['cell']['name']=$v;
			$i++;
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($responce));
	}
	public function jsonStoreBrand(){
		$page = $this->input->post('page');
		$page = (!$page)?1:$page;
		$limit = $this->input->post('rows');
		$sidx =$this->input->post('sidx');
		$sord = $this->input->post('sord');
		$type = $this->input->post('type');
		$type = (!$type)?0:(int)$type;
		$search = $this->input->post('_search');
		$searchField = $this->input->post('searchField');
		$searchString = $this->input->post('searchString');
		$searchOper = $this->input->post('searchOper');
		$searchCont = $this->input->post('searchCont');
		$filters = $this->input->post('filters');
		$filters = !empty($filters) ? json_decode($filters,true) : array();
		if(empty($searchField)) $search='false';
		if($search=='true'){
			$where = $this->catalogo->getWhereClause($searchField,$searchOper,$searchString,$filters);
		} else $where = "";
		$sql="SELECT m.* FROM marcas AS m LEFT JOIN contactos AS c ON c.id=m.supplier$where";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$count = $query->num_rows();
		if( $count > 0 )
			$total_pages = ceil($count/$limit);
		else
			$total_pages = 0;
	
		if ($page > $total_pages)$page=$total_pages;
		if($page==0)$page=1;
		$start = $limit*$page - $limit;
		$sql="SELECT m.*,c.contacto,c.name as c_name FROM marcas AS m LEFT JOIN contactos AS c ON c.id=m.supplier$where ORDER BY $sidx $sord".
		" LIMIT $start,$limit";
		$query = $this->db->query($sql);
		$responce = (object) array();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count;
		$i=0;
		foreach($query->result_array() as $row){
			if(isset($row['id'])) $responce->rows[$i]['id']=$row['id'];
			foreach($row as $key=>$val){
				if($key=='contacto' && empty($val)) $val = $row['c_name'];
				if($key=='c_name' && empty($val)) $val = $row['contacto'];
				$responce->rows[$i]['cell'][$key]=$val;
			}
			$i++; 
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($responce));
	}
	public function jsonStoreItem(){
		$page = $this->input->post('page');
		$page = (!$page)?1:$page;
		$limit = $this->input->post('rows');
		$limit = (!$limit)?12:$limit;
		$sidx =$this->input->post('sidx'); 
		$sidx = (!$sidx)?'name':$sidx;
		$sord = $this->input->post('sord');
		$sord = (!$sord)?"":$sord;
		$type = $this->input->post('type');
		$type = (!$type)?0:(int)$type;
		$search = $this->input->post('_search');
		$searchField = $this->input->post('searchField');
		$searchString = $this->input->post('searchString');
		$searchOper = $this->input->post('searchOper');
		$filters = $this->input->post('filters');
		$filters = !empty($filters) ? json_decode($filters,true) : array();
		if(empty($searchField)) $search='false';
		if($search=='true'){
			$where = $this->catalogo->getWhereClause($searchField,$searchOper,$searchString,$filters);
			$where = str_replace('code','i.code',$where);
			$where = str_replace('i.i.code','i.code',$where);
			$where = str_replace('name','i.name',$where);
			$where = str_replace('i.i.name','i.name',$where);
		} else $where = "";
		$sql="SELECT i.* FROM store_items AS i LEFT JOIN contactos AS c ON c.id=i.supplier ".
		"LEFT JOIN categorias AS k ON i.catid=k.id LEFT JOIN marcas AS m ON i.brand=m.id$where";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		if( $count > 0 ) $total_pages = ceil($count/$limit);
		else $total_pages = 0;
	
		if ($page > $total_pages)$page=$total_pages;
		if($page==0)$page=1;
		$start = $limit*$page - $limit;
		
		$sql="SELECT i.*,c.name AS sname,c.contacto,k.name AS catidname,m.name AS brandname FROM store_items AS i LEFT JOIN ".
		"contactos AS c ON c.id=i.supplier LEFT JOIN categorias AS k ON i.catid=k.id LEFT JOIN marcas AS m ON i.brand=".
		"m.id$where ORDER BY $sidx $sord LIMIT $start,$limit";
		$query = $this->db->query($sql);
		$responce = (object) array();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count;
		$i=0;
		foreach($query->result_array() as $row){
			if(isset($row['id'])) $responce->rows[$i]['id']=$row['id'];
			foreach($row as $key=>$val){
				if($key=='sname' && empty($val)) $val=$row['contacto'];
				if($key=='contacto' && empty($val)) $val=$row['sname'];
				$responce->rows[$i]['cell'][$key]=$val;
			}
			$i++; 
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($responce));
	}
	public function checkContacto(){
		$rfc = $this->input->post('rfc');
		$type = $this->input->post('type');
		$id = $this->input->post('id');
		$sql="SELECT id FROM contactos WHERE rfc LIKE '$rfc' AND id!=$id AND type=$type";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		if($count)
			echo "false";
		else
			echo "true";
	}
	public function checkCode(){
		$code = $this->input->post('code');
		$id = $this->input->post('id');
		$sql="SELECT id FROM store_items WHERE code LIKE '$code' AND id!=$id";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		if($count)
			echo "false";
		else
			echo "true";
	}
	public function jsonContacSearch(){
		$rfc = $this->input->post('rfc');
		$responce = array();
		if(empty($rfc)) return json_encode(array());
		$sql="SELECT * FROM contactos WHERE rfc LIKE '$rfc%' OR name LIKE '%$rfc%' OR contacto LIKE '%$rfc%' ".
			"OR id = '$rfc' OR email LIKE '%$rfc%' OR telnumber LIKE '%$rfc%'";
		$query=$this->db->query($sql);
		$i=0;
		foreach($query->result_array() as $row){
			$row['name']=(empty($row['name']))?$row['contacto']:$row['name'];
			$responce[$i]['label'] = $row['rfc'].'-'.$row['name'];
			$responce[$i]['value'] = $row['rfc'];
			$responce[$i]['row'] = $row;
			$i++;
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($responce));
	}
	public function jsonStoreItemSearch(){
		$word=$this->input->post('word');
		$sql="SELECT i.*,l.price,l.amount,l.off,l.id as lote FROM store_items AS i LEFT JOIN store_lot AS l ON i.id=l.ids ".
			"WHERE i.code LIKE '$word%' OR i.name LIKE '%$word%' ORDER BY i.name";
		$query=$this->db->query($sql);
		$i=0;
		$responce=array();
		foreach($query->result_array() as $row){
			$responce[$i]['label'] = $row['code'].'-'.$row['name'];
			$responce[$i]['value'] = $row['name'];
			$responce[$i]['row'] = $row;
			$i++;
		}
		if(!count($responce)){
			$responce[0]['label'] = "No hay resultados para $word";
			$responce[0]['value'] = "";
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($responce));
	}
	public function jsonStoreLot(){
		$page = $this->input->post('page');
		$page = (!$page)?1:$page;
		$limit = $this->input->post('rows');
		$limit = (!$limit)?12:$limit;
		$sidx =$this->input->post('sidx'); 
		$sidx = (!$sidx)?'name':$sidx;
		$sord = $this->input->post('sord');
		$sord = (!$sord)?"":$sord;
		$type = $this->input->post('type');
		$type = (!$type)?0:(int)$type;
		$search = $this->input->post('_search');
		$searchField = $this->input->post('searchField');
		$searchString = $this->input->post('searchString');
		$searchOper = $this->input->post('searchOper');
		$filters = $this->input->post('filters');
		$filters = !empty($filters) ? json_decode($filters,true) : array();
		if(empty($searchField)) $search='false';
		if($search=='true'){
			$where = $this->catalogo->getWhereClause($searchField,$searchOper,$searchString,$filters);
		} else $where = "";
		$sql="SELECT * FROM store_items AS i LEFT JOIN store_lot AS l ON i.id=l.ids$where";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		if( $count > 0 )
			$total_pages = ceil($count/$limit);
		else
			$total_pages = 0;
	
		if ($page > $total_pages)$page=$total_pages;
		if($page==0)$page=1;
		$start = $limit*$page - $limit;
		
		$sql="SELECT i.*,l.amount,l.off,l.price,l.cost FROM store_items AS i LEFT JOIN store_lot AS l ON i.id=l.ids".
		"$where ORDER BY $sidx $sord LIMIT $start,$limit";
		$query = $this->db->query($sql);
		$responce = (object) array();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count;
		foreach($query->result_array() as $row){
			$i = str_replace(">","",str_replace("LENTES->","",$row['catid']));
			if(isset($row['id'])) $responce->rows[$i]['id']=$row['id'];
			foreach($row as $key=>$val){
				if($key != 'id') $responce->rows[$i]['cell'][$key]=$val;
			}
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($responce));
	}
	public function saveContacto(){
		$post=$this->input->post();
		if(isset($post['rfc'])) $post['rfc'] = strtoupper($post['rfc']);
		$post['oper']=(isset($post['oper']))?$post['oper']:'';
		$return['result']=false;
		
		if($post['oper']=="del"){
			unset($post['oper']);
			if($post['id']=='1000' || $post['id']=='1001'){
				$this->plantillas->set_message(6002,"Este contacto no se puede eliminar.");
				$this->output
					->set_content_type('application/json')
					->set_output(json_encode($return));
				return false;
			}
			$sql="SHOW TABLES LIKE 'ingresos'";
			$query = $this->db->query($sql);
			$count=0;
			if ($query->num_rows()){
				$sql="SELECT id FROM ingresos WHERE receptor LIKE '%{$post['id']}%'";
				$query = $this->db->query($sql);
				$count=$query->num_rows();
				$msg="Este contacto tiene facturas generadas";
			} else {
				$sql="SELECT id FROM store_items WHERE supplier={$post['id']}";
				$query = $this->db->query($sql);
				$count=$query->num_rows();
				$msg="Este contacto tiene registros en tienda";
			}
			if(!$count){
				$return['result']=$this->catalogo->delete('contactos','id',$post);
				if($return['result'])
					$this->plantillas->set_message('Contacto eliminado del catalogo','success');
			} else
				$this->plantillas->set_message(6002,$msg,'warning');
		} else {
			if(isset($post['domicilio'])){
				foreach($post['domicilio'] as $key => $val)
					$post['domicilio'][$key]= htmlentities($val);
				$post['domicilioFiscal']=json_encode($post['domicilio']);
				unset($post['domicilio']);
			}
			if(isset($post['dm'])){
				foreach($post['dm'] as $key => $val)
					$post['dm'][$key]= htmlentities($val);
				$post['domicilio']=json_encode($post['dm']);
				unset($post['dm']);
			}
			if(isset($post['telnumber']))
				$post['telnumber']=json_encode($post['telnumber']);
			$post['name']=htmlentities($post['name']);
			$post['contacto']=htmlentities($post['contacto']);
			
			if(!empty($post['rfc'])){
				htmlentities($post['rfc']);
				$sql="SELECT id FROM contactos WHERE rfc LIKE '{$post['rfc']}' AND type={$post['type']} AND id != '{$post['id']}'";
				$query = $this->db->query($sql);
				if($query->num_rows()){
					$row=$query->row_array();
					$this->plantillas->set_message(6002,"El RFC ya se encuentra registrado.");
					$post['oper']="";
				}
			}
			if($post['oper']=="add"){
				unset($post['oper']);
				$post['id']=0;
				$return['result']=$this->catalogo->save('contactos','id',$post);
				if($return['result'])
					$this->plantillas->set_message('Contacto agregado a catalogo','success');	
			} elseif($post['oper']=="edit"){
				unset($post['oper']);
				$return['result']=$this->catalogo->save('contactos','id',$post);
				if($return['result'])
					$this->plantillas->set_message('Contacto actualizado en catalogo','success');
			}
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
	public function saveCatid(){
		$post=$this->input->post();
		$post['oper']=(isset($post['oper']))?$post['oper']:'';
		$return['result']=false;
		$msg="La categoria no existe";
		$status="error";
		if($post['oper']=="del"){
			unset($post['oper']);
			$sql="SELECT * FROM categorias WHERE padre='{$post['id']}'";
			$query = $this->db->query($sql);
			if(!$query->num_rows()){
				$return['result']=$this->catalogo->delete('categorias','id',$post);
				if($return['result'])
					$this->plantillas->set_message('Categoria <strong>ELIMINADO</strong> del catalogo','success');
			} else
				$this->plantillas->set_message("Esta categoria presede a otras","warning");
		} elseif($post['oper']=="add"){
			unset($post['oper']);
			$post['id']=0;
			$return['result']=$this->catalogo->save('categorias','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Categoria <strong>AGREGADA</strong> al catalogo','success');
		} elseif($post['oper']=="edit"){
			unset($post['oper']);
			$return['result']=$this->catalogo->save('categorias','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Categoria <strong>ACTUALIZADA</strong> en catalogo','success');
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
	public function saveStoreUnit(){
		$post=$this->input->post();
		$post['oper']=(isset($post['oper']))?$post['oper']:'';
		$return['result']=false;
		$msg="La unidad no existe";
		$status="error";
		if($post['oper']=="add"){
			$sql="SELECT value FROM config WHERE name='store_unit'";
			$query = $this->db->query($sql);
			$row=$query->row_array();
			$value = json_decode($row['value'],true);
			$value[] = $post['name'];
			$value = json_encode($value);
			unset($post);
			$post['value']=$value;
			$this->db->where('name','store_unit');
			$return['result']=$this->db->update('config',$post);
			if(!$return['result']) $msg="Fallo en catalogos al eliminar al agregar en la base de datos";
			else $this->plantillas->set_message('Unidad agregada con exito','success');
		} elseif($post['oper']=="edit"){
			$sql="SELECT value FROM config WHERE name='store_unit'";
			$query = $this->db->query($sql);
			$row=$query->row_array();
			$value = json_decode($row['value'],true);
			foreach($value AS $key => $val)
				if($val == $post['id']){
					$value[$key] = $post['name'];
					$msg = "Unidad actualizada con exito";
					$status="success";
				}
			$value = json_encode($value);
			unset($post);
			$post['value']=$value;
			$this->db->where('name','store_unit');
			$return['result']=$this->db->update('config',$post);
			if(!$return['result']) $msg="Fallo en catalogos al editar la base de datos";
			else $this->plantillas->set_message($msg,$status);
		} elseif($post['oper']=="del"){
			$sql="SELECT value FROM config WHERE name='store_unit'";
			$query = $this->db->query($sql);
			$row=$query->row_array();
			$value = json_decode($row['value'],true);
			foreach($value AS $key => $val)
				if($val == $post['id']) unset($value[$key]);
			if(!count($value)) $value[0]="PZ";
			$value = json_encode($value);
			unset($post);
			$post['value']=$value;
			$this->db->where('name','store_unit');
			$return['result']=$this->db->update('config',$post);
			if(!$return['result']) $msg="Fallo en catalogos al eliminar";
			else $this->plantillas->set_message('Unidad eliminada con exito','success');
		}
		if(!$return['result']) show_error($msg);
		else $this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
	public function saveStoreBrand(){
		$post=$this->input->post();
		$post['oper']=(isset($post['oper']))?$post['oper']:'';
		$return['result']=false;
		$msg="La marca no existe";
		$status="error";
		if($post['oper']=="del"){
			unset($post['oper']);
			$return['result']=$this->catalogo->delete('marcas','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Marca <strong>ELIMINADA</strong> del catalogo','success');
		} elseif($post['oper']=="add"){
			unset($post['oper']);
			$post['id']=0;
			$return['result']=$this->catalogo->save('marcas','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Marca <strong>AGREGADA</strong> al catalogo','success');
		} elseif($post['oper']=="edit"){
			unset($post['oper']);
			$return['result']=$this->catalogo->save('categorias','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Marca <strong>ACTUALIZADA</strong> en catalogo','success');
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
	public function saveStoreItem(){
		$post=$this->input->post();
		$post['oper']=(isset($post['oper']))?$post['oper']:'';
		$post['name']=(isset($post['name']))?htmlentities($post['name']):'';
		$return['result']=false;
		
		if($post['oper']=="lente"){
			$db['grad'] = $post['grad'];
			unset($post['grad']);
			unset($post['id']);
			unset($post['name']);
			unset($post['oper']);
			
			foreach($post as $key=>$val){
				$db['catid']=(int)$key;
				$cod1=str_replace(".","",$db['grad']);
				$cod1=substr($cod1,0,4) . substr($cod1,-3,3);
				$cod2=explode("-",$val['name']);
				$db['code']="MF";
				switch($cod2[0]){
					case "PLASTICO":
						$db['code'].="CR";
						break;
					case "POLICARBONATO":
						$db['code'].="PO";
						break;
					case "HIINDEX":
						$db['code'].="HI";
				}
				switch($cod2[1]){
					case "BLANCO":
						$db['code'].="BL";
						break;
					case "ANTIREFLEJANTES":
						$db['code'].="AR";
						break;
					case "FOTOCROMATICO":
						$db['code'].="FT";
						break;
					case "AR/FOTOCROMATICO":
						$db['code'].="AF";
				}
				$db['code'].=$cod1;
				$db['name']="LENTE GRADUADO MF {$val['name']} ({$db['grad']})";
				$db['supplier']=1001;
				$db['unit']="PZ";
				$sql="SELECT id,code FROM store_items WHERE code='{$db['code']}'";
				$query = $this->db->query($sql);
				if($query->num_rows){
					$row = $query->row_array();
					$sql="SELECT id,ids FROM store_lot WHERE ids='{$row['id']}'";
					$query = $this->db->query($sql);
					if($query->num_rows){
						$lot = $query->row_array();
						$lot['amount'] = $val['cant'];
						$return['result']=$this->catalogo->save('store_lot','id',$lot);
					} else {
						$lot['id'] = 0;
						$lot['ids'] = $row['id'];
						$lot['bill'] = "F000";
						$lot['date'] = date('Y-m-d');
						$lot['price'] = $val['precio'];
						$lot['amount'] = $val['cant'];
						$return['result']=$this->catalogo->save('store_lot','id',$lot);
					}
				} else {
					$db['id']=0;
					$db['catid']=$db['catid'];
					$lot['ids']=$this->catalogo->save('store_items','id',$db);
					$lot['id']=0;
					$lot['bill'] = "F000";
					$lot['date'] = date('Y-m-d');
					$lot['price'] = $val['precio'];
					$lot['amount'] = $val['cant'];
					$return['result']=$this->catalogo->save('store_lot','id',$lot);
				}
			}
			if($return['result'])
				$this->plantillas->set_message('Lote <strong>AGREGADO</strong> a la base de datos','success');
		} elseif($post['oper']=="del"){
			unset($post['oper']);
			$sql="SELECT * FROM store_lot WHERE ids='{$post['id']}'";
			$query = $this->db->query($sql);
			if(!$query->num_rows()){
				$return['result']=$this->catalogo->delete('store_items','id',$post);
				if($return['result'])
					$this->plantillas->set_message('Articulo eliminado del catalogo','success');
			} else
				$this->plantillas->set_message("Este articulo tiene lotes generados","warning");
		} elseif($post['oper']=="add"){
			unset($post['oper']);
			$post['id']=0;
			$post['date']=date('Y-m-d H:i:s');
			if(isset($post['code'])&&!empty($post['code'])){
				$sql="SELECT id,code,cant FROM store_items WHERE code='{$post['code']}'";
				$query = $this->db->query($sql);
				if($query->num_rows){
					$row = $query->row_array();
					$post['id']=$row['id'];
					$post['cant']=$row['cant']+$post['cant'];
				}
			}
			$post['catid'] = str_replace("-","->",$post['catid']);
			unset($post['store']);
			$return['result']=$this->catalogo->save('store_items','id',$post);
			if($return['result']) $this->plantillas->set_message('Articulo agregado al catalogo','success');
		} elseif($post['oper']=="edit"){
			unset($post['oper']);
			unset($post['store']);
			$post['date']=date('Y-m-d H:i:s');
			$return['result']=$this->catalogo->save('store_items','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Articulo actualizado en catalogo','success');
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
}
