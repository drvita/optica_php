<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Optica extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->plantillas->is_session();
		$this->load->model('home_model','optica');
		$this->plantillas->is_session();
		$this->load->helper('form');
		$this->kinfof = $this->session->userdata('kindof');
	}
	public function index(){
		$data['top']['title']='Examenes';
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/optica/index.js');
		$data['top']['main'][]=array('click'=>'buscar()','class'=>'search','label'=>'Buscar');
		$data['top']['main'][]=array('click'=>'printed()','class'=>'print','label'=>'Imprimir');
		if($this->kinfof==2 || $this->kinfof==0)
			$data['top']['main'][]=array('click'=>'dpaltura()','class'=>'pencil','label'=>'DP/Altura');
		if($this->kinfof==1 || $this->kinfof==0){
			$data['top']['main'][]=array('click'=>'add()','class'=>'plus','label'=>'Nuevo','type'=>'success');
			$data['top']['main'][]=array('click'=>'edit()','class'=>'pencil','label'=>'Editar');
			$data['top']['main'][]=array('click'=>'eliminar()','class'=>'trash','label'=>'Eliminar');
		}
		$this->plantillas->show_tpl('optica/index',$data);
	}
	public function dpaltura(){
		$data['top']['title']='Establecer DP / Altura';
		$data['top']['scripts'][]['src']=base_url('lib/js/view/optica/dpaltura.js');
		$data['top']['main'][]=array('click'=>'save()','class'=>'floppy-disk','label'=>'Guardar','type'=>'success');
		$data['top']['main'][]=array('click'=>'cancelar()','class'=>'ban-circle','label'=>'Cancelar');
		$this->plantillas->show_tpl('optica/dpaltura',$data);
	}
	public function examen(){
		$id = (int)$this->input->post('id');
		$data['top']['title']='Examen';
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.validate.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/optica/examen.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/bootstrap-toggle.min.js');
		$data['top']['cssf'][]['href']=base_url('lib/css/bootstrap-toggle.min.css');
		$data['top']['main'][]=array('click'=>'save()','class'=>'floppy-disk','label'=>'Guardar','type'=>'success');
		if($id){
			$sql="SELECT e.*,c.contacto,c.telnumber,c.birthday FROM examenes AS e LEFT JOIN contactos AS c ON e.idclient=c.id ".
				"WHERE e.id=$id";
			$query = $this->db->query($sql);
			if($query->num_rows()){
				$data['row']['now'] = $query->row_array();
				$data['row']['now']['telnumber']=json_decode($data['row']['now']['telnumber'],true);
				$sql="SELECT * FROM examenes WHERE idclient={$data['row']['now']['idclient']} AND ".
				"id != {$data['row']['now']['id']} AND date < '{$data['row']['now']['date']}' AND status=2 ORDER BY date DESC LIMIT 4";
				$query = $this->db->query($sql);
				if($query->num_rows()){
					$data['row']['ant'] = $query->result_array();
					if($query->num_rows()>1)
						$data['top']['main'][]=array('click'=>'historial()','class'=>'calendar','label'=>'Historial');
				} else $data['row']['ant'][0] = $data['row']['now'];
			} else {
				$this->plantillas->set_message("El examen [$id] no fue encontrado en el historial",'error');
				redirect("optica",'refresh');
			}
		} else {
			$this->plantillas->set_message("El indentificador del examen [$id] no es valido",'error');
			redirect("optica",'refresh');
		}
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
		$data['top']['main'][]=array('click'=>'cancelar()','class'=>'ban-circle','label'=>'Cancelar');
		$this->plantillas->show_tpl('optica/examen',$data);
	}
	public function jsonExamenes(){
		$page = $this->input->post('page');
		$page = (!$page)?1:$page;
		$limit = $this->input->post('rows');
		$limit = (!$limit)?50:$limit;
		$sidx =$this->input->post('sidx'); 
		$sidx = !$sidx?'date':$sidx;
		$sidx = $sidx=='statusname'?'status':$sidx;
		$sord = $this->input->post('sord');
		$sord = (!$sord)?"":$sord;
		$type = $this->input->post('type');
		$type = (!$type)?0:(int)$type;
		$search = $this->input->post('_search');
		$searchField = $this->input->post('searchField');
		$searchString = $this->input->post('searchString');
		//$searchString = !empty($searchString) && is_string($searchString)? $searchString : date('Y-m-d');
		$searchString = $searchField=='date'? date('Y-m-d H:i:s',strtotime(date('Y-m-d',strtotime($searchString)))) : $searchString;
		$searchString = $searchField=='id'? (int)$searchString : $searchString;
		$searchOper = $this->input->post('searchOper');
		$filters = $this->input->post('filters');
		$filters = !empty($filters) ? json_decode($filters,true) : array();
		if(empty($searchField)) $search='false';
		if($search=='true'){
			if(isset($filters['rules'])){
				foreach($filters['rules'] as $k=>$val){
					if($val['field']=='statusname') $filters['rules'][$k]['field']='status';
					if($val['field']=='id') $filters['rules'][$k]['field']='e.id';
				}
			}
			if($searchField=='id') $searchField='e.id';
			$where = $this->optica->getWhereClause($searchField,$searchOper,$searchString,$filters);
		} else $where = "";
		$sql="SELECT e.* FROM examenes AS e LEFT JOIN contactos AS c ON e.idclient=c.id$where";
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
		
		$sql="SELECT e.*,c.name,c.contacto,c.name,c.telnumber FROM examenes AS e LEFT JOIN contactos AS c ON e.idclient=c.id$where".
		" ORDER BY $sidx $sord LIMIT $start,$limit";
		$query = $this->db->query($sql);
		$responce = (object) array();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count;
		$i=0;
		foreach($query->result_array() as $row){
			if(isset($row['id'])) $responce->rows[$i]['id']=$row['id'];
			if(isset($row['id'])) $row['id']=str_pad($row['id'],6,0,STR_PAD_LEFT);
			foreach($row as $key=>$val){
				if($key=='status' && $val==0) $responce->rows[$i]['cell']['statusname'] = 'Por hacer';
				if($key=='status' && $val==1) $responce->rows[$i]['cell']['statusname'] = 'Hecho';
				if($key=='status' && $val==2) $responce->rows[$i]['cell']['statusname'] = 'Con pedido';
				if($key=='status' && $val==3) $responce->rows[$i]['cell']['statusname'] = 'Baja';
				$responce->rows[$i]['cell'][$key]=$val;
			}
			$i++; 
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($responce));
	}
	public function saveExamenes(){
		$post=$this->input->post();
		$post['oper']=(isset($post['oper']))?$post['oper']:'';
		$return['result']=false;
		$post['date']=date('Y-m-d H:i:s');
		$session = $this->session->all_userdata();
		$kindof=$session['kindof'];
		unset($session['emisor']);
		unset($session['pac']);
		unset($session['conf']);
		unset($session['setup']);
		$post['user']=json_encode($session);
		if($kindof==2 && $post['oper']=="del"){
			$post['oper']="edit";
			$post['status']=3;
		}
		if($post['oper']=="del"){
			unset($post['oper']);
			$return['result']=$this->optica->delete('examenes','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Examen eliminado con exito','success');
		} elseif($post['oper']=="add"){
			unset($post['oper']);
			if(isset($post['contact']))unset($post['contact']);
			if(isset($post['contacto']))unset($post['contacto']);
			$post['id']=0;
			$return['result']=$this->optica->save('examenes','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Examen almacenado con exito','success');
		} elseif($post['oper']=="edit"){
			unset($post['oper']);
			if(isset($post['txoptico']) && is_array($post['txoptico'])){
				if(count($post['txoptico']['tipo'])<2) $post['txoptico']['tipo'][]=0;
				if(count($post['txoptico']['material'])<2) $post['txoptico']['material'][]=0;
				if(count($post['txoptico']['tx'])<2) $post['txoptico']['tx'][]=0;
				$post['txoptico']=json_encode($post['txoptico']); 
			}
			if(isset($post['contact'])){
				$post['contact']['id']=$post['idclient'];
				$post['contact']['telnumber']['particular']="";
				$post['contact']['telnumber']['oficina']="";
				$post['contact']['telnumber']=json_encode($post['contact']['telnumber']);
				$post['idclient']=$this->optica->save('contactos','id',$post['contact']);
				unset($post['contact']);
			}
			$post['frontal'] = (isset($post['frontal'])&&$post['frontal'])?1:0;
			$post['occipital'] = (isset($post['occipital'])&&$post['occipital'])?1:0;
			$post['generality'] = (isset($post['generality'])&&$post['generality'])?1:0;
			$post['temporaod'] = (isset($post['temporaod'])&&$post['temporaod'])?1:0;
			$post['temporaoi'] = (isset($post['temporaoi'])&&$post['temporaoi'])?1:0;
			$post['cefalea'] = (isset($post['cefalea'])&&$post['cefalea'])?1:0;
			$post['presbicie'] = (isset($post['presbicie'])&&$post['presbicie'])?1:0;
			$post['pc'] = (isset($post['pc'])&&$post['pc'])?1:0;
			$post['tablet'] = (isset($post['tablet'])&&$post['tablet'])?1:0;
			$post['movil'] = (isset($post['movil'])&&$post['movil'])?1:0;
			$post['lap'] = (isset($post['lap'])&&$post['lap'])?1:0;
			unset($post['idclient']);
			unset($post['date']);
			$return['result']=$this->optica->save('examenes','id',$post);
			if($return['result'])
				$this->plantillas->set_message('Examen actualizado con exito','success');
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
}
