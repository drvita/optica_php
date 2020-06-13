<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {
	function __construct(){
		//file_put_contents('logfile.txt',"del: ".json_encode($post)."\n",FILE_APPEND);
		parent::__construct();
		$this->load->model('home_model','home');
		$this->load->helper('form');
	}
	//Pagina principal
	public function index(){
		$this->plantillas->is_session();
		$kindof=$this->session->userdata('kindof');
		if($kindof==1)
			$return="pedidos";
		elseif($kindof==2)
			$return="optica";
		else
			$return="inbox";
		redirect(base_url($return),'refresh');
	}
	//Pagina principal
	public function users(){
		$this->plantillas->is_session();
		$data['top']['title']='Lista de usuarios';
		$data['top']['cssf'][]['href']=base_url('lib/css/view/home/users.css');
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.validate.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.md5.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/home/users.js');
		$data['top']['main'][]=array('click'=>'buscar()','class'=>'search','label'=>'Buscar');
		$data['top']['main'][]=array('click'=>'add()','class'=>'plus','label'=>'Nuevo','type'=>'success');
		$data['top']['main'][]=array('click'=>'edit()','class'=>'pencil','label'=>'Editar');
		$data['top']['main'][]=array('click'=>'eliminar()','class'=>'trash','label'=>'Eliminar');
		$this->plantillas->show_tpl('home/users',$data);
	}
	public function autho(){
		if($this->plantillas->getUser()) redirect(base_url(),'refresh');
		$data['top']['title']='Inicio de session';
		$data['top']['cssf'][]['href']=base_url('lib/css/view/home/autho.css');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.validate.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.md5.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/home/autho.js');
		$this->load->view('home/autho', $data);
	}
	public function session(){
		$user= strip_tags(strtolower($this->input->post('user')));
		$user= str_replace(" ", "", $user);
		$pass= strip_tags($this->input->post('pass'));
		$token= $this->input->post('pass');
		$return['next']= "home/autho";
		$return['rows']=0;
		$sql="SELECT * FROM users WHERE username = '$user' AND pass = '".hash_hmac('md5',$pass,$pass)."'";
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			$return['rows']=$query->num_rows();
			$data=$query->row_array();
			unset($data['pass']);
			$sql="SELECT value FROM config WHERE name='emisor'";
			$query = $this->db->query($sql);
			if($query->num_rows()){
				//Datos emisor
				$row=$query->row_array();
				$data['emisor']= json_decode($row['value'],true);
				unset($row);
				//Datos PAC
				$sql="SELECT value FROM config WHERE name='PAC'";
				$query = $this->db->query($sql);
				if($query->num_rows()){
					$row=$query->row_array();
					$datos['pac']= json_decode($row['value'],true);
					$datos['conf']['cer'] = $datos['pac']['cer'];
					unset($datos['pac']['cer']);
					$datos['conf']['key'] = $datos['pac']['key'];
					unset($datos['pac']['key']);
					$datos['conf']['pass'] = $datos['pac']['SAT']['pass'];
					unset($datos['pac']['SAT']);
					$data['pac']= $datos['pac'];
					$data['conf']= $datos['conf'];
				}
				$this->plantillas->set_message("Bienvenido ".utf8_decode($data['name']),'information');
				$data['setup']=false;
				if($data['kindof']==1)
					$return['next']="optica";
				elseif($data['kindof']==2)
					$return['next']="inbox";
				else
					$return['next']="pedidos";
			} else {
				$this->plantillas->set_message('Establesca los parametros de configuracion inicial','warning');
				$return['next']= "settings/index";
				$data['setup']=true;
			}
			$this->session->set_userdata($data);
		} else {
			$return['rows']=$query->num_rows();
			$this->plantillas->set_message(104,"Datos de usuario incorrecto");
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url('home/autho'),'refresh');
	}
	/*Muestra errores de la session, mediante AJAX*/
	public function getmessage(){
		$messages=$this->plantillas->show_message();
		if(is_array($messages) && count($messages)){
			$data['records']=count($messages);
			$data['messages']=$messages;
		} else
			$data['records']=0;
		$data['session'] = $this->plantillas->getUser();
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	public function jsonUsers(){
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
		$user=$this->session->userdata('username');
		$filters = $this->input->post('filters');
		$filters = !empty($filters) ? json_decode($filters,true) : array();
		if(empty($searchField)) $search='false';
		if($search=='true'){
			$where = $this->home->getWhereClause($searchField,$searchOper,$searchString,$filters);
		} else $where = "";
		
		$sql="SELECT * FROM users$where";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		if( $count > 0 )
			$total_pages = ceil($count/$limit);
		else
			$total_pages = 0;
	
		if ($page > $total_pages)$page=$total_pages;
		if($page==0)$page=1;
		$start = $limit*$page - $limit;
		
		$sql="SELECT * FROM users$where ORDER BY $sidx $sord LIMIT $start,$limit";
		$query = $this->db->query($sql);
		$responce = (object) array();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count;
		$i=0;
		foreach($query->result_array() as $row){
			foreach($row as $key=>$val){
				if($key != 'iduser'){
					if($key=="kindof"){
						if($val==0)
							$val="ADMINISTRADOR";
						elseif($val==1)
							$val="DOCTOR";
						elseif($val==2)
							$val="EMPLEADO";
					} elseif($key=="nemploy"){
						if(!$val) $val="No vinculado";
					}
					$responce->rows[$i]['cell'][$key]=strtoupper($val);
				} else {
					$responce->rows[$i]['id']=$val;
					$responce->rows[$i]['cell']['id']=$val;
				}
			}
			$i++; 
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($responce));
	}
	public function saveUser(){
		$post=$this->input->post();
		$post['oper']=(isset($post['oper']))?$post['oper']:'';
		$return['result']=false;
		if(isset($post['pass'])){
			$pass=strip_tags($this->input->post('pass'));
			$post['pass']=hash_hmac('md5',$pass,$pass);
		}
		if(isset($post['passr'])) unset($post['passr']);
		if($post['oper']=="del"){
			$post['iduser']=$post['id'];
			unset($post['id']);
			unset($post['oper']);
			$return['result']=$this->home->delete('users','iduser',$post);
			if($return['result'])
				$this->plantillas->set_message('Usuario eliminado con exito','success');
		} elseif($post['oper']=="add"){
			unset($post['oper']);
			$post['iduser']=0;
			$return['result']=$this->home->save('users','iduser',$post);
			if($return['result'])
				$this->plantillas->set_message('Usuario agregado con exito','success');
		} elseif($post['oper']=="edit"){
			unset($post['oper']);
			if(!isset($post['pass']) || empty($post['pass'])) unset($post['pass']);
			$return['result']=$this->home->save('users','iduser',$post);
			if($return['result'])
				$this->plantillas->set_message('Usuario actualizado con exito','success');
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
	public function getSql(){
		$sql = $this->input->post('sql');
		$query = $this->db->query($sql);
		$responce = (object) array();
		$responce->records = $query->num_rows();
		$responce->sql = $sql;
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
}
