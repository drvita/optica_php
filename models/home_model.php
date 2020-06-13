<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Home_model extends CI_Model {
    function __construct(){
        parent::__construct();
    }
    private $ops = array(
		'eq'=>'=', //equal
		'ne'=>'<>',//not equal
		'lt'=>'<', //less than
		'le'=>'<=',//less than or equal
		'gt'=>'>', //greater than
		'ge'=>'>=',//greater than or equal
		'bw'=>'LIKE', //begins with
		'bn'=>'NOT LIKE', //doesn't begin with
		'in'=>'LIKE', //is in
		'ni'=>'NOT LIKE', //is not in
		'ew'=>'LIKE', //ends with
		'en'=>'NOT LIKE', //doesn't end with
		'cn'=>'LIKE', // contains
		'nc'=>'NOT LIKE'  //doesn't contain
	);
	public function getWhereClause($col,$oper='eq',$val,$filters=array()){
        $ops = $this->ops;
        $items = null;
        if(is_array($filters) && count($filters)){
			for($i=0;$i<count($filters['rules']);$i++){
				if(is_string($filters['rules'][$i]['data'])) $filters['rules'][$i]['data'] = str_replace(array('ñ','Ñ'), array('%','%'), $filters['rules'][$i]['data']);
				if($filters['rules'][$i]['op'] == 'bw' || $filters['rules'][$i]['op'] == 'bn') $filters['rules'][$i]['data'] .= '%';
				if($filters['rules'][$i]['op'] == 'ew' || $filters['rules'][$i]['op'] == 'en' ) $filters['rules'][$i]['data'] = '%'.$filters['rules'][$i]['data'];
				if($filters['rules'][$i]['op'] == 'cn' || $filters['rules'][$i]['op'] == 'nc' || $filters['rules'][$i]['op'] == 'in' || $filters['rules'][$i]['op'] == 'ni') $filters['rules'][$i]['data'] = '%'.$filters['rules'][$i]['data'].'%';
				if(is_string($filters['rules'][$i]['data'])) $filters['rules'][$i]['data'] = "'{$filters['rules'][$i]['data']}'";
				if($filters['rules'][$i]['field']=='date'){
					$items .= empty($items)? " DATE({$filters['rules'][$i]['field']}) {$ops[$filters['rules'][$i]['op']]} {$filters['rules'][$i]['data']}":
						" {$filters['groupOp']} DATE({$filters['rules'][$i]['field']}) {$ops[$filters['rules'][$i]['op']]} {$filters['rules'][$i]['data']}";
				} else {
					$items .= empty($items)? " {$filters['rules'][$i]['field']} {$ops[$filters['rules'][$i]['op']]} {$filters['rules'][$i]['data']}":
						" {$filters['groupOp']} {$filters['rules'][$i]['field']} {$ops[$filters['rules'][$i]['op']]} {$filters['rules'][$i]['data']}";
				}
			}
		} else {
			if(is_string($val)) $val = str_replace(array('ñ','Ñ'), array('%','%'), $val);
			if($oper == 'bw' || $oper == 'bn') $val .= '%';
			if($oper == 'ew' || $oper == 'en' ) $val = '%'.$val;
			if($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni') $val = '%'.$val.'%';
			if(is_string($val)) $val = "'$val'";
			if($col=='date') $items = " DATE($col) {$ops[$oper]} $val";
			else $items = " $col {$ops[$oper]} $val";
		}
		if(!empty($items)) return " WHERE$items";
		else return "";
    }
    //Almacenamos en base de datos
	function save($table='',$id='',$post=array()){
		if(!count($post)) return false;
		if(empty($table)) return false;
		if(empty($id)) return false;
		if(!isset($post[$id])) $post[$id] = null;
		if($post[$id]=='_empty' || $post[$id]=='null') $post[$id]='';
		
		$sql="SELECT * FROM $table WHERE `$id`='{$post[$id]}'";
		$query = $this->db->query($sql);
		if(!$query->num_rows){
			unset($post[$id]);
			if(!$this->db->insert($table,$post)){
				$this->plantillas->set_message(5001,"Fallo en catalogos");
				$return = 0;
			} else $return = $this->db->insert_id();
		} else {
			$this->db->where($id,$post[$id]);
			if(!$this->db->update($table,$post)){
				$this->plantillas->set_message(5001,"Fallo en catalogos");
				$return = 0;
			} else $return = $post[$id];
		}
		return $return;
	}
	//Elimina de la base de datos
	function delete($table='',$id='',$post=array()){
		if(!count($post)) return false;
		if(empty($table)) return false;
		if(empty($id)) return false;
		if($post[$id]=='_empty' || !isset($post[$id]) || $post[$id]=='null') $post[$id]='';
		if($this->db->delete($table,array($id=>$post[$id]))){
			$return = true;
		} else {
			$this->plantillas->set_message(5002,"Fallo en Usuarios");
			$return = false;
		}
		return $return;
	}
	//Buscamos registro
	function row($table='',$id='',$post=array()){
		if(!count($post)) return false;
		if(empty($table)) return false;
		if(empty($id)) return false;
		if($post[$id]=='_empty' || !isset($post[$id])) $post[$id]=0;
		
		$this->db->where($id,$post[$id]);
		$query=$this->db->select($table);
		return $query->result_array();
	}
}
