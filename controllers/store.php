<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Store extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->plantillas->is_session();
		$this->load->model('home_model','store');
		$this->load->helper('form');
	}
	//Pagina principal
	public function index(){
		$data['top']['title']='Almacen';
		$data['top']['cssf'][]['href']=base_url('lib/css/view/store/index.css');
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.validate.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/store/index.js');
		$data['top']['main'][]=array('click'=>'buscar()','class'=>'search','label'=>'Buscar');
		$data['top']['main'][]=array('click'=>'add()','class'=>'plus','label'=>'Nuevo','type'=>'success');
		$data['top']['main'][]=array('click'=>'edit()','class'=>'pencil','label'=>'Editar');
		$data['top']['main'][]=array('click'=>'eliminar()','class'=>'trash','label'=>'Eliminar');
		$this->plantillas->show_tpl('store/index',$data);
	}
	public function pedidos(){
		$data['top']['title']='Pedidos (Almacen)';
		//$data['top']['cssf'][]['href']=base_url('lib/css/view/store/index.css');
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/store/pedidos.js');
		$data['top']['main'][]=array('click'=>'buscar()','class'=>'search','label'=>'Buscar');
		$data['top']['main'][]=array('click'=>'edit()','class'=>'cog','label'=>'Procesar','type'=>'success');
		$data['top']['main'][]=array('click'=>'eliminar()','class'=>'trash','label'=>'Eliminar');
		$sql="SELECT id,name FROM categorias WHERE name LIKE 'MONOFOCAL' OR name LIKE 'BIFOCAL' OR name LIKE 'PROGRESIVO%'";
		$query = $this->db->query($sql);
		if($query->num_rows()){
			$data['catid'] = $query->result_array();
			foreach($data['catid'] AS $k => $row){
				$data['catid'][$row['name']]=$row;
				unset($data['catid'][$k]);
				$sql="SELECT id,name FROM categorias WHERE padre={$row['id']}";
				$query = $this->db->query($sql);
				if($query->num_rows()){
					$data['catid'][$row['name']]['material'] = $query->result_array();
					foreach($data['catid'][$row['name']]['material'] AS $key => $val){
						$data['catid'][$row['name']][$val['name']]=$val;
						unset($data['catid'][$row['name']]['material']);
						$sql="SELECT id,name,precio FROM categorias WHERE padre={$val['id']}";
						$query = $this->db->query($sql);
						if($query->num_rows()){
							$data['catid'][$row['name']][$val['name']]['tx'] = $query->result_array();
							foreach($data['catid'][$row['name']][$val['name']]['tx'] AS $ll => $con){
								$data['catid'][$row['name']][$val['name']][$con['name']]=$con;
								unset($data['catid'][$row['name']][$val['name']]['tx']);
							}
						} else $data['catid'][$row['name']][$val['name']] = array();
					}
				} else $data['catid'][$row['name']]=array();
			} 
		} else $data['catid']=array();
		$sql="SELECT id,name FROM contactos WHERE type=1";
		$query = $this->db->query($sql);
		$data['supplier'] = $query->result_array();
		$this->plantillas->show_tpl('store/pedidos',$data);
	}
	public function lentes(){
		$data['top']['title']='Inventario (Lentes)';
		$data['top']['cssf'][]['href']=base_url('lib/css/view/store/lentes.css');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.validate.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/store/lentes.js');
		$sql="SELECT id FROM categorias WHERE name='MONOFOCAL'";
		$query = $this->db->query($sql);
		if($query->num_rows()){
			$row = $query->row_array();
			$sql="SELECT id,name FROM categorias WHERE padre={$row['id']}";
			$query = $this->db->query($sql);
			if($query->num_rows()){
				$data['catid'] = $query->result_array();
				foreach($data['catid'] AS $key => $val){
					$sql="SELECT id,name,precio FROM categorias WHERE padre={$val['id']}";
					$query = $this->db->query($sql);
					if($query->num_rows()){
						$data['catid'][$key]['tx'] = $query->result_array();
					} else $data['catid'][$key]['tx'] = array();
				}
			} else $data['catid']=array();
		} else $data['catid']=array();
		$sql="SELECT i.grad,SUM(l.amount) AS amount FROM store_items AS i LEFT JOIN store_lot AS l ON i.id=l.ids ".
			"WHERE i.code LIKE 'MF%' AND i.supplier=1001 GROUP BY i.grad";
		$query = $this->db->query($sql);
		if($query->num_rows){
			$row = $query->result_array();
			foreach($row AS $val){
				$data['items'][$val['grad']]=$val['amount'];
			}
		}
		$this->plantillas->show_tpl('store/lentes',$data);
	}
	public function saveLot(){
		$post=$this->input->post();
		$post['oper']=(isset($post['oper']))?$post['oper']:'';
		$return['result']=false;
		if($post['oper']=="del"){
			unset($post['oper']);
			$return['result']=$this->store->delete('store_lot','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Lote eliminado del almacen','success');
		} elseif($post['oper']=="add"){
			unset($post['oper']);
			$post['id']=0;
			$return['result']=$this->store->save('store_lot','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Lote agregado al almac&eacute;n','success');
		} elseif($post['oper']=="edit"){
			unset($post['oper']);
			$return['result']=$this->store->save('store_lot','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Lote actualizado en almac&eacute;n','success');
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
	public function jsonStoreLot(){
		$page = $this->input->post('page');
		$page = (!$page)?1:$page;
		$limit = $this->input->post('rows');
		$limit = (!$limit)?20:$limit;
		$sidx =$this->input->post('sidx'); 
		$sidx = (!$sidx)?'date':$sidx;
		$sord = $this->input->post('sord');
		$sord = (!$sord)?"":$sord;
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
			$where = $this->store->getWhereClause($searchField,$searchOper,$searchString,$filters);
			$where .= " AND amount > off";
		} else $where = " WHERE amount > off";
			
		
		$sql="SELECT l.* FROM store_lot AS l LEFT JOIN store_items AS i ON l.ids=i.id$where";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		if( $count > 0 )
			$total_pages = ceil($count/$limit);
		else
			$total_pages = 0;
	
		if ($page > $total_pages)$page=$total_pages;
		if($page==0)$page=1;
		$start = $limit*$page - $limit;
		
		$sql="SELECT l.*,i.code,i.name,i.unit,c.name AS catidname,k.name AS catidpadre FROM store_lot AS l ".
		"LEFT JOIN store_items AS i ON l.ids=i.id LEFT JOIN categorias AS c ON i.catid=c.id LEFT JOIN categorias ".
		"AS k ON c.padre=k.id$where ORDER BY $sidx $sord LIMIT $start,$limit";
		$query = $this->db->query($sql);
		$responce = (object) array();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count;
		$responce->searchString = is_array($searchString)? $searchString[0] : $searchString;
		$i=0;
		foreach($query->result_array() as $row){
			if(isset($row['id'])) $responce->rows[$i]['id']=$row['id'];
			foreach($row as $key=>$val){
				$responce->rows[$i]['cell'][$key]=$val;
			}
			$i++; 
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($responce));
	}
	public function jsonItemSearch(){
		$id=$this->input->post('id');
		$sql="SELECT l.*,s.name FROM store_lot AS l LEFT JOIN store_items AS s ON l.ids=s.id ".
		"WHERE s.id = '$id' ORDER BY date";
		$query=$this->db->query($sql);
		$i=0;
		$responce=array();
		foreach($query->result_array() as $row){
			$responce[$i]['label'] = $row['date'].'-'.$row['name'];
			$responce[$i]['value'] = $row['price'];
			$responce[$i]['row'] = $row;
			$i++;
		}
		if(!count($responce)){
			$responce[0]['label'] = "No hay resultados";
			$responce[0]['value'] = "";
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($responce));
	}
	public function jsonPedidos(){
		$page = $this->input->post('page');
		$page = (!$page)?1:$page;
		$limit = $this->input->post('rows');
		$limit = (!$limit)?200:$limit;
		$sidx =$this->input->post('sidx'); 
		$sord = $this->input->post('sord');
		$search = $this->input->post('_search');
		$searchField = $this->input->post('searchField');
		$searchString = $this->input->post('searchString');
		$searchString = $searchField=='id'? (int)$searchString : $searchString;
		$searchOper = $this->input->post('searchOper');
		$searchCont = $this->input->post('searchCont');
		$filters = $this->input->post('filters');
		$filters = !empty($filters) ? json_decode($filters,true) : array();
		if(empty($searchField)) $search='false';
		if($search=='true'){
			$where = $this->store->getWhereClause($searchField,$searchOper,$searchString,$filters);
		} else $where = "";
		$where = str_replace('id','p.id',$where);
		$sql="SELECT p.* FROM pedidos AS p LEFT JOIN contactos AS c ON p.idcliente=c.id$where";
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
		$sql="SELECT p.*,c.contacto,c.telnumber FROM pedidos AS p LEFT JOIN contactos AS c ON p.idcliente=c.id".
		"$where ORDER BY $sidx $sord LIMIT $start,$limit";
		$query = $this->db->query($sql);
		$responce = (object) array();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count;
		$responce->sql = $sql;
		$responce->searchString = is_array($searchString)? $searchString[0] : $searchString;
		$i=0;
		foreach($query->result_array() as $row){
			if(isset($row['id'])) $responce->rows[$i]['id']=$row['id'];
			if(isset($row['id'])) $row['id']=str_pad($row['id'],6,0,STR_PAD_LEFT);
			foreach($row as $key=>$val){
				if($key=='status'){
					switch($val){
						case 1:
						$responce->rows[$i]['cell']['statusname']='EN LABORATORIO';
						break;
						case 2:
						$responce->rows[$i]['cell']['statusname']='EN BICELACIÓN';
						break;
						case 3:
						$responce->rows[$i]['cell']['statusname']='COMPLETO';
						break;
						case 4:
						$responce->rows[$i]['cell']['statusname']='TERMINADO';
						break;
						default:
						$responce->rows[$i]['cell']['statusname']='EN PROCESO';
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
	public function jsonVentas(){
		$page = $this->input->post('page');
		$page = (!$page)?1:$page;
		$limit = $this->input->post('rows');
		$limit = (!$limit)?200:$limit;
		$sidx = $this->input->post('sidx'); 
		$sord = $this->input->post('sord');
		$search = $this->input->post('_search');
		$searchField = $this->input->post('searchField');
		$searchString = $this->input->post('searchString');
		$searchString = $searchField=='id'? (int)$searchString : $searchString;
		$searchOper = $this->input->post('searchOper');
		$searchCont = $this->input->post('searchCont');
		$filters = $this->input->post('filters');
		$filters = !empty($filters) ? json_decode($filters,true) : array();
		if(empty($searchField)) $search='false';
		if($search=='true'){
			if(isset($filters['rules'])){
				foreach($filters['rules'] as $k=>$val){
					if($val['field']=='p.idserver') $filters['rules'][$k]['field']='v.idserver';
					if($val['field']=='idserver') $filters['rules'][$k]['field']='v.idserver';
					if($val['field']=='id') $filters['rules'][$k]['field']='p.id';
				}
			}
			$where = $this->store->getWhereClause($searchField,$searchOper,$searchString,$filters);
		} else $where = "";
		//$where = str_replace('id','p.id',$where);
		$sql="SELECT v.* FROM ventas AS v ". 
		"LEFT JOIN contactos AS c ON c.id = v.idclient$where";
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
		$sql="SELECT DATE(v.date) AS date,v.accion,v.metodopago,v.montopago,v.banco,v.idclient ,c.contacto,v.items,v.idserver ".
		"FROM ventas AS v ". 
		"LEFT JOIN contactos AS c ON c.id = v.idclient$where ORDER BY $sidx $sord LIMIT $start,$limit";
		$query = $this->db->query($sql);
		$responce = (object) array();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count;
		$responce->searchString = $searchString;
		$i=0;
		foreach($query->result_array() as $row){
			if(isset($row['id'])) $responce->rows[$i]['id']=$row['id'];
			if(isset($row['id'])) $row['id']=str_pad($row['id'],6,0,STR_PAD_LEFT);
			foreach($row as $key=>$val){
				$responce->rows[$i]['cell'][$key]=$val;
			}
			$i++; 
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($responce));
	}
	public function savePedido(){
		$post=$this->input->post();
		$return['result']=array();
		$test= isset($post['test']['id']) ?$post['test']['id']:0;
		if(isset($post['contact']['telnumber']) && !empty($post['contact']['telnumber']['mobil'])){
			$post['contact']['id']= $post['contact']['id']<1002? $post['idcliente'] : $post['contact']['id'];
			$post['contact']['telnumber']['particular']="";
			$post['contact']['telnumber']['oficina']="";
			$post['contact']['telnumber']=json_encode($post['contact']['telnumber']);
			$return['result']['contact']=$this->store->save('contactos','id',$post['contact']);
			unset($post['contact']);
		}
		if($test>0){
			$post['test']['txoptico']=(isset($post['test']['txoptico']))?json_encode($post['test']['txoptico']):'{}';
			$post['test']['status']=2;
			$return['result']['test']=$this->store->save('examenes','id',$post['test']);
			unset($post['test']);
		}
		if(isset($post['contact'])) unset($post['contact']);
		if(isset($post['test'])) unset($post['test']);
		$post['id']=isset($post['id'])? $post['id'] : 0;
		if(!$post['id']) $post['date']=date('Y-m-d H:i:s');
		$session = $this->session->all_userdata();
		unset($session['emisor']);
		unset($session['pac']);
		unset($session['conf']);
		unset($session['setup']);
		$post['user']=json_encode($session);
		if($test>0)$post['test']=$test;
		if(!isset($post['status'])){
			if(isset($post['npedidolab']) && !empty($post['npedidolab']) && $post['npedidolab'])
				$post['status']=1;
			if(isset($post['ncaja']) && !empty($post['ncaja']) && $post['ncaja']>0)
				$post['status']=2;
		}
		$return['result']['pedido']=$this->store->save('pedidos','id',$post);
		if(isset($post['anticipo']) && $post['anticipo']>0 && !$post['id']>0 && $return['result']['pedido']){
			$v['id']=0;
			$v['date']=date('Y-m-d H:i:s');
			$v['idserver']=$return['result']['pedido'];
			$v['idtest']=$test;
			$v['idclient']=$post['idcliente'];
			$v['items']='[{"id":"0","und":"1","item":"ANTICIPO","price":"'.$post['anticipo'].'"}]';
			$v['total']=$post['anticipo'];
			$v['metodopago']=$post['metodopago'];
			$v['banco']=$post['banco'];
			$v['user']=$post['user'];
			$return['result']['venta']=$this->store->save('ventas','id',$v);
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
}
