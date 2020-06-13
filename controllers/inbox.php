<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inbox extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->plantillas->is_session();
		$this->load->model('home_model','inbox');
		$this->load->helper('form');
	}
	public function index(){
		$data['top']['title']='Caja';
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/inbox/index.js');
		$data['top']['cssf'][]['href']=base_url('lib/css/view/inbox/index.css');
		$this->plantillas->show_tpl('inbox/index',$data);
	}
	public function corte(){
		$data['top']['title']='Inicio y corte';
		$data['top']['scripts'][]['src']=base_url('lib/js/view/inbox/corte.js');
		$sql="SELECT * FROM caja WHERE DATE(date) = '".date('Y-m-d')."' AND type=0 LIMIT 1";
		$query = $this->db->query($sql);
		$data['in'] = $query->row_array();
		$sql="SELECT * FROM caja WHERE DATE(date) = '".date('Y-m-d')."' AND type=1 LIMIT 1";
		$query = $this->db->query($sql);
		$data['off'] = $query->row_array();
		$this->plantillas->show_tpl('inbox/corte',$data);
	}
	public function saveinoff(){
		$post=$this->input->post();
		$return['result']=false;
		$post['date']=date('Y-m-d H:i:s');
		
		$sql="SELECT * FROM caja WHERE DATE(date) = DATE('{$post['date']}') AND type = {$post['type']}";
		$query = $this->db->query($sql);
		if(!$query->num_rows()){
			$return['result']=$this->inbox->save('caja','id',$post);
			if($return['result']) $this->plantillas->set_message('Caja actualizada con exito','success');
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
	public function saveventa(){
		$post=$this->input->post();
		$post['oper']=(isset($post['oper']))?$post['oper']:'';
		$post['date']=date('Y-m-d H:i:s');
		$serie = $post['serie'];
		unset($post['serie']);
		if(isset($post['metodopago'])) $post['metodopago'] = strtoupper($post['metodopago']);
		$session = $this->session->all_userdata();
		unset($session['emisor']);
		unset($session['pac']);
		unset($session['conf']);
		unset($session['setup']);
		$post['user']=json_encode($session);
		$return['result']=true;
		$return['type']='main';
		$items = json_decode($post['items'],true);
		if(is_array($items) && count($items)){
			unset($post['oper']);
			unset($post['total']);
			foreach ($items as $item){
				$item['id'] = empty($item['id'])? '0' :(int) $item['id'];
				if(!empty($item['code']) && $item['code']!='0' && $item['und']>0 && $item['id']<990){
					$sql="SELECT id,cant FROM store_items WHERE id='{$item['id']}' OR code LIKE '{$item['code']}' LIMIT 1";
					$query = $this->db->query($sql);
					$row = $query->row_array();
					if($query->num_rows()){
						$row['cant']-=$item['und'];
						if($row['cant'] < 0){
							$log = date('Y-m-d H:i:s').",{$post['idserver']},{$row['id']},{$item['und']},1,Articulo sin darse de baja por actualmente estar en CERO\n";
						} else {
							$row['date']=date('Y-m-d H:i:s');
							$return['result']=$this->inbox->save('store_items','id',$row);
							$return['type']='Save in store_items '.$item['code'];
							$log = date('Y-m-d H:i:s').",{$post['idserver']},{$row['id']},{$item['und']},0,Articulo dado de baja\n";
						}
					} else $log = date('Y-m-d H:i:s').",{$post['idserver']},{$item['id']},{$item['und']},2,Articulo no encontrado en la base de datos\n";
					@file_put_contents(PATHLOG."store_sales_".date('mY').".log",$log,FILE_APPEND); 
				} elseif($item['id']>1000 && $item['id']<1100){
					$post['montopago']=$item['price']*-1;
					$post['metodopago']=strtoupper($item['item']);
					$post['banco']=($item['item']=='EFECTIVO' || $item['item']=='TRANSFERENCIA BANCARIA')?'':$post['banco'];
					$post['type']=$item['id'];
					$post['accion']='INGRESO';
					$post['id']=0;
					$return['result']=$this->inbox->save('ventas','id',$post);
					$return['type']='Metodo de pago';
				} elseif($item['id']==996 || $item['id']==997){
					$post['montopago']=$item['price'];
					$post['metodopago']=strtoupper($item['item']);
					$post['banco']='';
					$post['type']=$item['id'];
					$post['accion']= $item['id']==996? 'CREDITO':'DESCUENTO';
					$post['id']=0;
					$return['result']=$this->inbox->save('ventas','id',$post);
					$return['type']='Creditos y descuentos';
				}
			}
		}
		if($return['result']){
			$this->plantillas->set_message('Gracias por su compra','success');
			$test['id']=$post['idserver'];
			$test['status']=4;
			$result = $this->inbox->save($serie."_pedidos",'id',$test);
			$return['type']='Cambiar estatus de pedido';
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
}
