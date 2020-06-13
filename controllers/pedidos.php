<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pedidos extends CI_Controller {
	private $kinfof;
	function __construct(){
		parent::__construct();
		$this->plantillas->is_session();
		$this->load->model('home_model','pedidos');
		$this->load->helper('form');
		$this->kinfof = $this->session->userdata('kindof');
	}
	//Pagina principal
	public function index(){
		$data['top']['title']='Pedidos';
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/pedidos/index.js');
		$data['top']['main'][]=array('click'=>'printed()','class'=>'print','label'=>'Imprimir');
		$data['top']['main'][]=array('click'=>'buscar()','class'=>'search','label'=>'Buscar');
		$data['top']['main'][]=array('click'=>'edit()','class'=>'pencil','label'=>'Editar','type'=>'success');
		if(!$this->kinfof)
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
		$this->plantillas->show_tpl('pedidos/index',$data);
	}
	public function pedido(){
		$post = $this->input->post();
		$data['top']['title']='Pedidos';
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/pedidos/pedido.js');
		
		$sql="SELECT p.*,c.contacto,c.telnumber FROM {$post['year']}_pedidos AS p LEFT JOIN contactos AS c ON p.idcliente=c.id ".
		"WHERE p.id={$post['id']}";
		$query = $this->db->query($sql);
		if($query->num_rows()){
			$data['pedido']=$query->row_array();
			if($data['pedido']['test']>0){
				$sql="SELECT * FROM examenes WHERE id={$data['pedido']['test']}";
				$query = $this->db->query($sql);
				if($query->num_rows()){
					$data['pedido']['test']=$query->row_array();
					$data['pedido']['test']['txoptico']=json_decode($data['pedido']['test']['txoptico'],true);
				}
			}
			if($data['pedido']['status']!=4){
				$data['top']['main'][]=array('click'=>'save()','class'=>'floppy-disk','label'=>'Guardar','type'=>'success');
				$data['top']['main'][]=array('click'=>'cancelar()','class'=>'ban-circle','label'=>'Cancelar');
			} else $data['top']['main'][]=array('click'=>'cancelar()','class'=>'ban-circle','label'=>'Cerrar');
			$sql="SELECT id,name,contacto FROM contactos WHERE type=1";
			$query = $this->db->query($sql);
			$data['supplier'] = $query->result_array();
			$data['pfx'] = $post['year'];
			$this->plantillas->show_tpl('pedidos/pedido',$data);
		} else redirect("pedidos", 'refresh');
			
	}
	public function nuevo(){
		$post = $this->input->post();
		if($post['idclient']>0){
			$data['top']['title']='Pedidos';
			$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
			$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
			$data['top']['scripts'][]['src']=base_url('lib/js/view/pedidos/nuevo.js');
			$data['top']['main'][]=array('click'=>'save()','class'=>'floppy-disk','label'=>'Guardar','type'=>'success');
			$data['top']['main'][]=array('click'=>'cancelar()','class'=>'ban-circle','label'=>'Cancelar');
			$sql="SELECT id,contacto,name,telnumber FROM contactos WHERE id={$post['idclient']}";
			$query = $this->db->query($sql);
			if($query->num_rows()){
				$data['contacto'] = $query->row_array();
				$sql="SELECT * FROM examenes WHERE idclient={$post['idclient']} ORDER BY date DESC LIMIT 5 ";
				$query = $this->db->query($sql);
				$data['examenes'] = $query->result_array();
				$sql="SELECT id,name FROM categorias WHERE name LIKE 'MONOFOCAL%' OR name LIKE 'BIFOCAL' OR name LIKE 'PROGRESIVO%'";
				$query = $this->db->query($sql);
				if($query->num_rows()){
					$data['catid'] = $query->result_array();
					foreach($data['catid'] AS $k => $row){
						$data['catid'][$row['name']]=array();
						unset($data['catid'][$k]);
						$sql="SELECT id,name FROM categorias WHERE padre={$row['id']}";
						$query = $this->db->query($sql);
						if($query->num_rows()){
							$data['catid'][$row['name']]['material'] = $query->result_array();
							foreach($data['catid'][$row['name']]['material'] AS $key => $val){
								$data['catid'][$row['name']][$val['name']]=array();
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
				$this->plantillas->show_tpl('pedidos/nuevo',$data);
			}
		} else {
			echo "ID {$post['idclient']}, no existe.";
		}
	}
	public function jsonPedidos(){
		$page = $this->input->post('page');
		$page = (!$page)?1:$page;
		$limit = $this->input->post('rows');
		$limit = (!$limit)?200:$limit;
		$sidx =$this->input->post('sidx');
		$sidx = (!empty($sidx) && $sidx=='statusname')?'status':$sidx;
		$sord = $this->input->post('sord');
		$search = $this->input->post('_search');
		$searchField = $this->input->post('searchField');
		$searchField = (!empty($searchField) && $searchField=='statusname')?'status':$searchField;
		$searchString = $this->input->post('searchString');
		$searchString = $searchField=='id'? (int)$searchString : $searchString;
		$searchOper = $this->input->post('searchOper');
		$filters = $this->input->post('filters');
		$filters = !empty($filters) ? json_decode($filters,true) : array();
		if(empty($searchField)) $search='false';
		$year = date('Y');
		$sql="";
		if($search=='true'){
			if(isset($filters['rules'])){
				foreach($filters['rules'] as $k=>$val){
					if($val['field']=='id') $filters['rules'][$k]['field']='p.id';
					if($val['field']=='contacto') $filters['rules'][$k]['field']='c.contacto';
					if($val['field']=='statusname') $filters['rules'][$k]['field']='p.status';
					if($val['field']=='year'){
						$year = $filters['rules'][$k]['data'];
						unset($filters['rules'][$k]);
						if(!is_numeric($year)){
							$year=date('Y');
						}
						$query = $this->db->query("show tables like '".$year."_pedidos'");
						if(!$query->num_rows()){
							$fp = fopen("lib/db/pedidos", "r");
							while(!feof($fp)) {
								$linea = fgets($fp);
								$sql .= $linea."\n";
							}
							fclose($fp);
							$sql = str_replace("pedidos",$year."_pedidos",$sql);
							$query = $this->db->query($sql);
						}
					}
				}
			}
			if($searchField=='id') $searchField='p.id';
			$where = $this->pedidos->getWhereClause($searchField,$searchOper,$searchString,$filters);
		} else $where = "";
		
		$sql="SELECT p.* FROM ".$year."_pedidos AS p LEFT JOIN contactos AS c ON p.idcliente=c.id".
		" LEFT JOIN contactos AS s ON p.laboratorio=s.id$where";
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
		$sql="SELECT p.*,c.contacto,c.telnumber,s.contacto as laboratorioname FROM ".$year."_pedidos AS p LEFT JOIN contactos AS c ON p.idcliente=c.id".
		" LEFT JOIN contactos AS s ON p.laboratorio=s.id$where ORDER BY $sidx $sord LIMIT $start,$limit";
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
	public function savePedido(){
		$post=$this->input->post();
		$return['result']=array();
		if($post['oper']=="del"){
			unset($post['oper']);
			$return['result']=$this->pedidos->delete('pedidos','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Numero de pedido eliminado','success');
		} elseif($post['oper']=="add"){
			unset($post['oper']);
			if(isset($post['contact']['telnumber']) && !empty($post['contact']['telnumber']['mobil'])){
				$post['contact']['id']= $post['contact']['id']<1002? $post['idcliente'] : $post['contact']['id'];
				$post['contact']['telnumber']['particular']="";
				$post['contact']['telnumber']['oficina']="";
				$post['contact']['telnumber']=json_encode($post['contact']['telnumber']);
				$return['result']['contact']=$this->pedidos->save('contactos','id',$post['contact']);
				unset($post['contact']);
			}
			$test= isset($post['test']['id']) ?$post['test']['id']:0;
			if($test>0){
				$post['test']['status']=2;
				$return['result']['test']=$this->pedidos->save('examenes','id',$post['test']);
				unset($post['test']);
			}
			if(isset($post['contact'])) unset($post['contact']);
			if(isset($post['test'])) unset($post['test']);
			$post['id']=0;
			$post['date']=date('Y-m-d H:i:s');
			$session = $this->session->all_userdata();
			unset($session['emisor']);
			unset($session['pac']);
			unset($session['conf']);
			unset($session['setup']);
			$post['user']=json_encode($session);
			$post['test']=$test;
			if(isset($post['idcliente']) && $post['idcliente']>999)
				$return['result']['pedido']=$this->pedidos->save(date('Y').'_pedidos','id',$post);
			else $return['result']['pedido'] = 1;
			if(isset($post['anticipo']) && $post['anticipo']>0 && !$post['id']>0 && $return['result']['pedido']){
				$v['id']=0;
				$v['date']=date('Y-m-d H:i:s');
				$v['idserver']=$return['result']['pedido'];
				$v['idtest']=$test;
				$v['idclient']=$post['idcliente'];
				$v['items']='[{"id":"998","und":"1","item":"ANTICIPO","price":"'.$post['anticipo'].'"}]';
				$v['accion']='INGRESO';
				$v['montopago']=$post['anticipo'];
				$v['metodopago']=$post['metodopago'];
				$v['banco']=$post['banco'];
				$v['user']=$post['user'];
				$return['result']['venta']=$this->pedidos->save('ventas','id',$v);
			}
		} elseif($post['oper']=="edit"){
			unset($post['oper']);
			$pfx = $post['pfx'];
			if(!is_numeric($pfx) && $pfx>0) $pfx = date('Y');
			unset($post['pfx']);
			$session = $this->session->all_userdata();
			unset($session['emisor']);
			unset($session['pac']);
			unset($session['conf']);
			unset($session['setup']);
			if($post['status']!=1){
				unset($post['laboratorio']);
				unset($post['npedidolab']);
			}
			$post['user']=json_encode($session);
			$test= isset($post['test']['id']) ?$post['test']['id']:0;
			if($test>0){
				$post['test']['status']=2;
				$return['result']['test']=$this->pedidos->save('examenes','id',$post['test']);
				unset($post['test']);
			}
			$return['result']['pedido']=$this->pedidos->save($pfx.'_pedidos','id',$post);
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
}
