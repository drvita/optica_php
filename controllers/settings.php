<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->plantillas->is_session();
		$this->load->helper('form');
	}
	//Pagina principal
	public function index(){
		$data['top']['scripts'][]['src']=base_url('lib/js/view/settings/home.js');
		$data['top']['cssf'][]['href']=base_url('lib/css/view/settings/home.css');
		$data['top']['main'][]=array('click'=>'save()','class'=>'floppy-disk','label'=>'Guardar','type'=>'success');
		$data['top']['main'][]=array('click'=>'document.location.href=base_url','class'=>'ban-circle','label'=>'Cancelar');
		
		$sql="SELECT value FROM config WHERE name='emisor'";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$data['row']['emisor']= (isset($row['value']))?json_decode($row['value'],true):array();
		$sql="SELECT value FROM config WHERE name='PAC'";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$data['row']['PAC']= (isset($row['value']))?json_decode($row['value'],true):array();
		$sql="SELECT value FROM config WHERE name='factura'";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$data['row']['factura']= (isset($row['value']))?json_decode($row['value'],true):array();
		$this->plantillas->show_tpl('settings/home',$data);
	}
	//Guardar datos
	public function save(){
		$msg=null;
		if(isset($_FILES['file_key']) &&
		$_FILES['file_key']['error']==0 &&
		isset($_FILES['file_cer']) &&
		$_FILES['file_cer']['error']==0){		
			$cer = $_FILES['file_cer']['tmp_name'];
			$key = $_FILES['file_key']['tmp_name'];
			$tmp = ROOT."tmp".DS."key.pem";
			$pass = $_POST['PAC']['SAT']['pass'];
			$comando="openssl pkcs8 -inform DER -in $key -out $tmp -passin pass:$pass";
			$resultado=shell_exec($comando);
			if(filesize($tmp)<10)
				$msg="La llave (archivo .key), no corresponde con el password";
			unlink($tmp);
			$comando="openssl x509 -inform DER -outform PEM -in $cer -pubkey >$tmp";
			$resultado=shell_exec($comando);
			$comando="openssl x509 -in $tmp  -subject -noout";
			$resultado3=shell_exec($comando);
			$contribuyente=explode('/',$resultado3);
			foreach($contribuyente AS $val){
				list($llave,$valor) = explode('=',$val);
				if($llave=="x500UniqueIdentifier")
					$rfc=$valor;
				if($llave=="name")
					$name=$valor;
			}
			$rfc = trim(strtoupper($rfc));
			$name = trim(strtoupper($name));
			$_POST['emisor']['rfc'] = trim(strtoupper($_POST['emisor']['rfc']));
			if($rfc!=$_POST['emisor']['rfc'])
				$msg="El certificado (archivo .cer), no corresponde con el RFC";
			unlink($tmp);
			if(empty($msg)){
				$namecer = strtolower($rfc).".cer";
				$namekey = str_replace("cer","key",$namecer);
				$_POST['emisor']['nombre'] = trim(strtoupper($_POST['emisor']['nombre']));
				$_POST['PAC']['cer'] = $namecer.".pem";
				$_POST['PAC']['key'] = $namekey.".pem";
				$dir = ROOT."lib".DS."keycer".DS;
				if(empty($msg)){
					if(!move_uploaded_file($_FILES['file_cer']['tmp_name'], $dir.$namecer))
						$msg="Error al mover el certificado a la carpeta del usuario";
					if(!move_uploaded_file($_FILES['file_key']['tmp_name'], $dir.$namekey))
						$msg="Error al mover la llave a la carpeta del usuario";
				}
			}
		} elseif(isset($_FILES['file_key']['error']) || isset($_FILES['file_cer']['error']))
			$msg="Error en la subida del los archivos";
		if(!empty($msg)){
			$this->plantillas->set_message($msg,'error');
			redirect("settings", 'refresh');
		}
		//Emisor
		if(!empty($_POST['emisor']['DomicilioFiscal']['calle']) && empty($_POST['emisor']['ExpedidoEn']['calle'])){
			$_POST['emisor']['ExpedidoEn']['calle'] = $_POST['emisor']['DomicilioFiscal']['calle'];
			$_POST['emisor']['ExpedidoEn']['noExterior'] = $_POST['emisor']['DomicilioFiscal']['noExterior'];
			$_POST['emisor']['ExpedidoEn']['noInterior'] = $_POST['emisor']['DomicilioFiscal']['noInterior'];
			$_POST['emisor']['ExpedidoEn']['colonia'] = $_POST['emisor']['DomicilioFiscal']['colonia'];
			$_POST['emisor']['ExpedidoEn']['localidad'] = $_POST['emisor']['DomicilioFiscal']['localidad'];
			$_POST['emisor']['ExpedidoEn']['municipio'] = $_POST['emisor']['DomicilioFiscal']['municipio'];
			$_POST['emisor']['ExpedidoEn']['estado'] = $_POST['emisor']['DomicilioFiscal']['estado'];
			$_POST['emisor']['ExpedidoEn']['pais'] = $_POST['emisor']['DomicilioFiscal']['pais'];
			$_POST['emisor']['ExpedidoEn']['CodigoPostal'] = $_POST['emisor']['DomicilioFiscal']['CodigoPostal'];
		}
		$emisor = json_encode($this->input->post('emisor'));
		$sql="SELECT COUNT(*) AS count FROM config WHERE name='emisor'";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$count = (int)$row['count'];
		if(!$count){
			$sql="INSERT INTO config (name,value) VALUES ('emisor','$emisor')";
			$msg="Configuracion guardada";
		} else {
			$sql="UPDATE config SET value='$emisor' WHERE name='emisor'";
			$msg="Configuracion actualizada";
		}
		if(!$this->db->query($sql))
			$this->plantillas->set_message(5001,"Configuraciones emisor");
		//PAC
		$datos['pac']= $this->input->post('PAC');
		$datos['conf']['cer'] = $datos['pac']['cer'];
		unset($datos['pac']['cer']);
		$datos['conf']['key'] = $datos['pac']['key'];
		unset($datos['pac']['key']);
		$datos['conf']['pass'] = $datos['pac']['SAT']['pass'];
		unset($datos['pac']['SAT']);
		//cambio de sesion
		$session=$datos;
		$session['emisor']=$this->input->post('emisor');
		$pac = json_encode($this->input->post('PAC'));
		$sql="SELECT COUNT(*) AS count FROM config WHERE name='PAC'";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$count = (int)$row['count'];
		if(!$count){
			$sql="INSERT INTO config (name,value) VALUES ('PAC','$pac')";
			$msg="Configuracion guardada";
		} else {
			$sql="UPDATE config SET value='$pac' WHERE name='PAC'";
			$msg="Configuracion actualizada";
		}
		if(!$this->db->query($sql))
			$this->plantillas->set_message(5001,"Configuraciones PAC");
		//Factura
		$factura = json_encode($this->input->post('factura'));
		$sql="SELECT COUNT(*) AS count FROM config WHERE name='factura'";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$count = (int)$row['count'];
		if(!$count){
			$sql="INSERT INTO config (name,value) VALUES ('factura','$factura')";
			$msg="Configuracion guardada";
		} else {
			$sql="UPDATE config SET value='$factura' WHERE name='factura'";
			$msg="Configuracion actualizada";
		}
		if(!$this->db->query($sql))
			$this->plantillas->set_message(5001,"Configuraciones factura");
		else {
			$this->plantillas->set_message($msg,'success');
			$this->session->set_userdata($session);
		}
		redirect("settings", 'refresh');
	}
}
