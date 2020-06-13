<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ingresos extends CI_Controller {
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
	private function getWhereClause($col, $oper, $val){
        $ops = $this->ops;
        if($oper == 'bw' || $oper == 'bn') $val .= '%';
        if($oper == 'ew' || $oper == 'en' ) $val = '%'.$val;
        if($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni') $val = '%'.$val.'%';
        return " WHERE $col {$ops[$oper]} '$val'";
    }
    function __construct(){
		parent::__construct();
		$this->load->model('ingresos_model','ingresos');
		$this->plantillas->is_session();
		$this->load->helper('form');
	}
	//Pagina principal
	public function index(){
		$data['top']['title']='Ingresos';
		$data['top']['cssf'][]['href']=base_url('lib/css/view/ingresos/home.css');
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.validate.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/ingresos/home.js');
		$pac=$this->session->userdata('pac');
		$data['top']['main'][]=array('click'=>'buscar()','class'=>'search','label'=>'Buscar');
		if(!empty($pac['usuario']) && !empty($pac['pass']))
			$data['top']['main'][]=array('click'=>'add()','class'=>'plus','label'=>'Nueva factura','type'=>'success');
		else
			$this->plantillas->set_message("Por el momento no puedes emitir facturas, comunicate atencion a clientes",'warning');
		$dir = ROOT.'lib'.DS.'keycer'.DS;
		if(!file_exists($dir) || !is_dir($dir)){
			$this->plantillas->set_message(6001,"Archivos KEY y CER no fueron encontrados");
			redirect("ingresos", 'refresh');
		}
		$data['mes']=date('m');
		$data['anio']=date('Y');
		$data['start'] = "2014";
		$more = strtotime("+1 year", time());
		$data['end'] = date("Y", $more);
		$sql="SELECT * FROM config WHERE name='store_unit'";
		$query = $this->db->query($sql); 
		$row = $query->row_array();
		$data['unit'] = json_decode($row['value'],true);
		$this->plantillas->show_tpl('ingresos/home',$data);
	}
	public function timbrar(){
		$post= $this->input->post();
		$datos['ruta'] = ROOT;
		$return['result']=false;
		//SAT & PAC
		$sql="SELECT value FROM config WHERE name='PAC'";
		$query = $this->db->query($sql);
		$row=$query->row_array();
		$ruta = ROOT."lib".DS."keycer".DS;
		$datos['pac']= json_decode($row['value'],true);
		$datos['conf']['cer'] = $ruta.$datos['pac']['cer'];
		unset($datos['pac']['cer']);
		$datos['conf']['key'] = $ruta.$datos['pac']['key'];
		unset($datos['pac']['key']);
		$datos['conf']['pass'] = $datos['pac']['SAT']['pass'];
		unset($datos['pac']['SAT']);
		$datos['factura'] = $post['factura'];
		if(!isset($datos['factura']['fecha_expedicion']))
			$datos['factura']['fecha_expedicion']=date('Y-m-d H:i:s',strtotime('-1 hour',strtotime(date('Y-m-d H:i:s'))));
		//RUTA DONDE ALMACENARA EL CFDI
		$datos['tmpfile']=date('Ymd'). '00' .date('His');
		$anio = date("Y",strtotime($datos['factura']['fecha_expedicion']));
		$mes = date("m",strtotime($datos['factura']['fecha_expedicion']));
		$dir = ROOT.'files';
		if(!file_exists($dir) && !is_dir($dir)){
			if(!mkdir($dir,0777,true))
			$this->plantillas->set_message(6000,"Error al crear el directorio: $dir");
		} else @chmod($dir, 0777);
		$dir.=DS."ingresos";
		if(!file_exists($dir) && !is_dir($dir)){
			if(!mkdir($dir,0777,true))
			$this->plantillas->set_message(6000,"Error al crear el directorio: $dir");
		} else @chmod($dir, 0777);
		$dir.=DS.$anio;
		if(!file_exists($dir) && !is_dir($dir)){
			if(!mkdir($dir,0777,true))
			$this->plantillas->set_message(6000,"Error al crear el directorio: $dir");
		} else @chmod($dir, 0777);
		$dir.= DS.$mes;
		if(!file_exists($dir) && !is_dir($dir)){
			if(!mkdir($dir,0777,true))
			$this->plantillas->set_message(6000,"Error al crear el directorio: $dir");
		} else @chmod($dir, 0777);
		if(is_dir($dir))
			$datos['cfdi'] = $dir.DS;
		else
			$this->plantillas->set_message(6000,"Error al acceder al directorio: $dir");
		//Serieyfolio
		if(empty($post['factura']['serie']) && empty($post['factura']['folio'])){
			$sql="SELECT value FROM config WHERE name='factura'";
			$query = $this->db->query($sql);
			$row=$query->row_array();
			$sf= json_decode($row['value'],true);
			$datos['factura']['serie']=$sf['serie'];
			$sql="SELECT * FROM ingresos";
			$query = $this->db->query($sql);
			$datos['factura']['folio']=$sf['folio']+$query->num_rows();
		} else {
			$datos['factura']['serie']=$post['factura']['serie'];
			$datos['factura']['folio']=$post['factura']['folio'];
		}
		//Emisor
		$sql="SELECT value FROM config WHERE name='emisor'";
		$query = $this->db->query($sql);
		$row=$query->row_array();
		$datos['emisor']= json_decode($row['value'],true);
		$datos['factura']['RegimenFiscal'] = (isset($datos['emisor']['RegimenFiscal']))?$datos['emisor']['RegimenFiscal']:'';
		unset($datos['emisor']['RegimenFiscal']);
		$datos['factura']['LugarExpedicion'] = $datos['emisor']['ExpedidoEn']['municipio'].', '.$datos['emisor']['ExpedidoEn']['estado'];
		if(empty($datos['emisor']['DomicilioFiscal']['noInterior'])) unset($datos['emisor']['DomicilioFiscal']['noInterior']);
		if(empty($datos['emisor']['ExpedidoEn']['noInterior'])) unset($datos['emisor']['ExpedidoEn']['noInterior']);
		$datos['receptor'] = $post['receptor'];
		$datos['receptor']['Domicilio'] = json_decode($post['receptor']['Domicilio'],true);
		if(isset($post['impuestos']['retenidos']) && count($post['impuestos']['retenidos'])){
			foreach($post['impuestos']['retenidos'] as $key=>$val)
				if($val['importe']<1)
					unset($post['impuestos']['retenidos'][$key]);
		}
		$datos['conceptos'] = json_decode($post['conceptos'],true);
		$datos['impuestos'] = $post['impuestos'];
		$datos['xslt'] = ROOT.'xslt/cadenaoriginal_3_2.xslt';
		$datos['debug'] = ROOT.'xml_debug'.DS;
		
		
		if($post['timbrar']){
			$this->load->library('satxml',$datos);
			$debug = $this->satxml->satxml_save();
			if($debug!=false) $timbre = $this->satxml->satxml_timbrar();
			else {
				$timbre['error']['code']=0;
				$timbre['error']['text']='Ocurrio un error al formar el XML';
				$debug='debug';
			}
		} else $timbre['error'] = array();
		if(!isset($timbre['error'])){
			@unlink($debug);
			$xml = simplexml_load_string((string)$timbre['cfdi']);
			$ns = $xml->getNamespaces(true);
			$xml->registerXPathNamespace('c', $ns['cfdi']);
			$xml->registerXPathNamespace('t', $ns['tfd']);
			$sat = $xml->xpath('//t:TimbreFiscalDigital');	
			foreach($sat[0]->attributes() as $key => $val)
				$sat_data[$key]=(string)$val[0];
			$db = array(
				'id'=>$post['id'],
				'fecha'=>$datos['factura']['fecha_expedicion'],
				'serie'=>$datos['factura']['serie'],
				'folio'=>$datos['factura']['folio'],
				'subtotal'=>$datos['factura']['subtotal'],
				'total'=>$datos['factura']['total'],
				'uuid'=>$timbre['uuid'],
				'factura'=>json_encode($datos['factura']),
				'receptor'=>json_encode($datos['receptor']),
				'conceptos'=>json_encode($datos['conceptos']),
				'impuestos'=>json_encode($datos['impuestos']),
				'co'=>$timbre['cadenaoriginal'],
				'status'=>'Timbrada',
				'sat'=>json_encode($sat_data)
			);
			$return['result']=$timbre['uuid'];
			$this->plantillas->set_message($timbre['CodEstatus'],'success');
			file_put_contents(PATHLOG."log_timbre.txt","[".date('Y-m-d H:i:s')."]:Debug;$debug\n",FILE_APPEND);
			file_put_contents(PATHLOG."log_timbre.txt","[".date('Y-m-d H:i:s')."]:CFDI;{$timbre['archivo_xml']}\n",FILE_APPEND);
			file_put_contents(PATHLOG."log_timbre.txt","[".date('Y-m-d H:i:s')."]:CodEstatus;{$timbre['CodEstatus']}\n",FILE_APPEND);
			file_put_contents(PATHLOG."log_timbre.txt","[".date('Y-m-d H:i:s')."]:uuid;{$timbre['uuid']}\n",FILE_APPEND);
		} else {
			$db = array(
				'id'=>$post['id'],
				'fecha'=>$datos['factura']['fecha_expedicion'],
				'serie'=>$datos['factura']['serie'],
				'folio'=>$datos['factura']['folio'],
				'subtotal'=>$datos['factura']['subtotal'],
				'total'=>$datos['factura']['total'],
				'factura'=>json_encode($datos['factura']),
				'receptor'=>json_encode($datos['receptor']),
				'conceptos'=>json_encode($datos['conceptos']),
				'impuestos'=>json_encode($datos['impuestos']),
				'status'=>'--'
			);
			if(count($timbre['error'])){
				$this->plantillas->set_message(6000,"{$timbre['error']['code']}, {$timbre['error']['text']} en $debug");
				file_put_contents(PATHLOG."log_error.txt","[".date('Y-m-d H:i:s')."]:datos;".json_encode($datos)."\n",FILE_APPEND);
			} else
				$this->plantillas->set_message('Factura almacenada <strong>sin timbrar</strong>','success');
			$return['result']=0;
		}
		$return['db'] = $this->ingresos->save('ingresos','id',$db);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
	public function editRows($anio=''){
		$anio = (empty($anio))?date('Y'):$anio;
		$oper = $this->input->post('oper');
		$id = $this->input->post('id');
		if($id) $sf = explode("_",$id);
		else $sf = array();
		$post['folio']=(int)$sf[1];
		$r['data']=0;
		if($oper=='del'){
			$result=$this->ingresos->delete('ingresos','folio',$post);
			if($result){
				$this->plantillas->set_message('Factura eliminada','success');
				$r['data']=1;
			} else $this->plantillas->set_message(5002,'Al eliminar factura SQLite');
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($r));
	}
	public function jsonRows($anio=''){
		$mes = $this->input->post('mes');
		if(empty($mes)) $mes=date('m');
		$page = $this->input->post('page');
		$page = (!$page)?1:$page;
		$limit = $this->input->post('rows');
		$limit = (!$limit)?12:$limit;
		$sidx =$this->input->post('sidx'); 
		$sidx = (!$sidx)?'fecha':$sidx; 
		$sord = $this->input->post('sord');
		$sord = (!$sord)?"":$sord;
		$anio = (empty($anio))?date('Y'):$anio;
		$search = $this->input->post('_search');
		$searchField = $this->input->post('searchField');
		$searchString = $this->input->post('searchString');
		$searchOper = $this->input->post('searchOper');
		$catid = $this->input->post('catid');
		
		if($search=='true' && !empty($search)){
			$where = $this->getWhereClause($searchField,$searchOper,$searchString);
			if(!empty($where)) $where .= " AND MONTH(fecha)='$mes'";
		} elseif(!empty($catid))
			$where = " WHERE catid='$catid' AND MONTH(fecha)='$mes'";
		else
			$where = " WHERE MONTH(fecha)='$mes'";
		
		$sql="SELECT * FROM ingresos$where";
		$query = $this->db->query($sql);
		$count = $query->num_rows();
		if( $count >0 )
			$total_pages = ceil($count/$limit);
		else
			$total_pages = 0;
		
		if($page > $total_pages)$page=$total_pages;
		if($page==0)$page=1;
		$start = $limit*$page - $limit; // do not put $limit*($page - 1)
		
		$sql="SELECT * FROM ingresos$where ORDER BY $sidx $sord LIMIT $start,$limit";
		$query = $this->db->query($sql);
		//file_put_contents('logingresos.txt', $sql);
		$responce = (object) array();
		$responce->page = $page; 
		$responce->total = $total_pages; 
		$responce->records = $count;
		$i=0;
		foreach($query->result_array() as $row){
			$responce->rows[$i]['id']=$row['id'];
			foreach($row as $key=>$val){
				$sumaiva=0;
				$responce->rows[$i]['cell'][$key]=$val;
				if($key=='receptor'){
					$receptor = json_decode($val,true);
					$responce->rows[$i]['cell']['nombre']=$receptor['nombre'];
				} elseif($key=='impuestos'){
					$impuestos = json_decode($val,true);
					foreach($impuestos['translados'] AS $imp)
						$sumaiva+=$imp['importe'];
					$responce->rows[$i]['cell']['totaliva']=(float)$sumaiva;
				} elseif($key=='status'){
					$responce->rows[$i]['cell']['status']=(empty($row['status']))?'--':$row['status'];
				}
			}
			if(strtolower($row['status'])=="cancelada"){
				$responce->rows[$i]['cell']['totalshow']=0;
				$responce->rows[$i]['cell']['subtotalshow']=0;
				$responce->rows[$i]['cell']['totaliva']=0;
			} else {
				$responce->rows[$i]['cell']['totalshow']=$row['total'];
				$responce->rows[$i]['cell']['subtotalshow']=$row['subtotal'];
			}
			$responce->rows[$i]['cell']['id']=$row['serie'].'_'.$row['folio'];
			$i++; 
		}
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($responce));
	}
	//Cancelar factura
	public function cancelar(){
		$data = $this->input->post();
		$sql="SELECT id,status,fecha FROM ingresos WHERE uuid='{$data['uuid']}'";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$data['pac']=$this->session->userdata('pac');
		$data['pac']['SAT']=$this->session->userdata('conf');
		$data['emisor']=$this->session->userdata('emisor');
		if($query->num_rows() && strtolower($row['status']) =='timbrada'){
			$data['pac']['SAT']['cer']=ROOT."lib".DS."keycer".DS.$data['pac']['SAT']['cer'];
			$data['pac']['SAT']['key']=ROOT."lib".DS."keycer".DS.$data['pac']['SAT']['key'];
			$data['anio']=date('Y',strtotime($row['fecha']));
			$data['mes']=date('m',strtotime($row['fecha']));
			$data['cfdi']=ROOT."files".DS."ingresos".DS.$data['anio'].DS.$data['mes'].DS.$data['uuid'].DS.$data['uuid'].".xml";
			$data['png']=str_replace('xml','png',$data['cfdi']);
			$data['zip']=str_replace('xml','zip',$data['cfdi']);
			$data['pdf']=str_replace('xml','pdf',$data['cfdi']);
			
			if(!is_file($data['pac']['SAT']['cer']) || !is_file($data['pac']['SAT']['key'])){
				$this->plantillas->set_message(6002,"Los archivos CER y KEY no fueron encontrados.");
				return false;
			}
			$cer_file = fopen($data['pac']['SAT']['cer'],"r");
			$cer_content = fread($cer_file,filesize($data['pac']['SAT']['cer']));
			fclose($cer_file);
			$key_file = fopen($data['pac']['SAT']['key'],"r");
			$key_content = fread($key_file,filesize($data['pac']['SAT']['key']));
			fclose($key_file);
			$params = array(
				"UUIDS" => array('uuids'=>array($data['uuid'])),
				"username" =>$data['pac']['usuario'],
				"password" =>$data['pac']['pass'],
				"taxpayer_id" =>$data['emisor']['rfc'],
				"cer" => $cer_content,
				"key" => $key_content
			);
			$this->load->library('satxml',$data);
			$response = $this->satxml->satxml_cancel($params);
			$status = (isset($response->cancelResult->Folios->Folio->EstatusUUID))?
				$response->cancelResult->Folios->Folio->EstatusUUID : 0;
			
			if($status==201){
				@unlink($data['zip']);
				@unlink($data['png']);
				@unlink($data['pdf']);
				$db = array(
					'id'=>$row['id'],
					'fecha'=>date('Y-m-d H:i:s'),
					'status'=>'Cancelada',
					'sat'=>json_encode($response)
				);
				$db = $this->ingresos->save('ingresos','id',$db);
				$this->plantillas->set_message('201: Factura <strong>cancelada</strong> con exito','success');
				$pdf=$this->toPdf($data['uuid'],$data['pdf']);
				file_put_contents(PATHLOG."log_timbre.txt","[".date('Y-m-d H:i:s')."]:CFDI;{$data['uuid']}\n",FILE_APPEND);
				file_put_contents(PATHLOG."log_timbre.txt","[".date('Y-m-d H:i:s')."]:CodEstatus;$status\n",FILE_APPEND);
			} else $this->plantillas->set_message(6002,"$status: EL CFDI no se cancelo correctamente");
			file_put_contents(PATHLOG."log_timbre.txt","[".date('Y-m-d H:i:s')."]:Response;".json_encode($response)."\n",FILE_APPEND);
		} else $this->plantillas->set_message(6002,"EL CFDI no esta registrado");
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array('result'=>true,'db'=>$db,'status'=>$status)));
	}
	//Reporte de cancelación
	public function report_cancel(){
		$post = $this->input->post();
		$sql="SELECT folio,id,status,fecha,sat,receptor FROM ingresos WHERE uuid='{$post['uuid']}'";
		$query = $this->db->query($sql);
		$row = $query->row_array();
		$row['sat'] = json_decode($row['sat'],true);
		$row['receptor'] = json_decode($row['receptor'],true);
		$this->load->library('mypdf');
        $this->mypdf->AddPage();
        $this->mypdf->AliasNbPages();
        $this->mypdf->SetFont('Arial','B',13);
		$this->mypdf->Cell(0,10,'Cancelacion de UUID '.$post['uuid'],'B',0,'C');
		$this->mypdf->Ln(10);
		$this->mypdf->SetFont('Arial','',9);
		$this->mypdf->Cell(0,10,utf8_decode('Aviso al SAT de la cancelación del documento fiscal digital (CFDI)'),'',0,'L');
		$this->mypdf->Ln(10);
		$this->mypdf->SetFont('Arial','B',9);
		$this->mypdf->Cell(25,10,'Fecha y hora:','',0,'L');
		$this->mypdf->SetFont('Arial','',9);
		$this->mypdf->Cell(60,10,$row['fecha'],'',0,'L');
		$this->mypdf->SetFont('Arial','B',9);
		$this->mypdf->Cell(12,10,'Folio:','',0,'L');
		$this->mypdf->SetFont('Arial','',9);
		$this->mypdf->Cell(0,10,$row['folio'],'',0,'L');
		$this->mypdf->Ln(10);
		$this->mypdf->SetFont('Arial','B',9);
		$this->mypdf->Cell(12,10,'RFC:','',0,'L');
		$this->mypdf->SetFont('Arial','',9);
		$this->mypdf->Cell(30,10,$row['receptor']['rfc'],'',0,'L');
		$this->mypdf->SetFont('Arial','B',9);
		$this->mypdf->Cell(15,10,'Nombre:','',0,'L');
		$this->mypdf->SetFont('Arial','',9);
		$this->mypdf->Cell(90,10,$row['receptor']['nombre'],'',0,'L');
		$this->mypdf->SetFont('Arial','B',9);
		$this->mypdf->Cell(15,10,utf8_decode('Estado: '),'',0,'L');
		$this->mypdf->SetFont('Arial','',8);
		$this->mypdf->Cell(0,10,$row['sat']['cancelResult']['Folios']['Folio']['EstatusUUID'],0,'L');
		$this->mypdf->Ln(10);
		$this->mypdf->SetFont('Arial','B',9);
		$this->mypdf->Cell(0,10,utf8_decode('Acuse: '),'',0,'L');
		$this->mypdf->Ln(1);
		$this->mypdf->SetFont('Arial','',8);
		$this->mypdf->MultiCell(0,10,utf8_decode($row['sat']['cancelResult']['Acuse']),0,'L');
		$this->mypdf->Output();
	}
	//Descarga un registro en PDF
	public function toPdf($uuid,$file){
		if(empty($file)) die('Parametros no enviados');
		$sql="SELECT * FROM ingresos WHERE uuid='$uuid'";
		$query = $this->db->query($sql);
		$row=$query->row_array();
		if(!count($row)) die('Error: Folio no encontrado en la DB.');
		$factura = json_decode($row['factura'],true);
		$receptor = json_decode($row['receptor'],true);
		$conceptos = json_decode($row['conceptos'],true);
		$impuestos = json_decode($row['impuestos'],true);
		$timbre = json_decode($row['sat'],true);
		
		$sql="SELECT value FROM config WHERE name='emisor'";
		$query = $this->db->query($sql);
		$conf=$query->row_array();
		$emisor = json_decode($conf['value'],true);
		
        $this->load->library('mypdf');
        $this->mypdf->AddPage();
        $this->mypdf->AliasNbPages();
        $this->mypdf->SetFont('Arial','B',10);
        $this->mypdf->SetTextColor(0);
        $this->mypdf->SetFillColor(247,246,240);
		$this->mypdf->SetDrawColor(247,246,240);
		$this->mypdf->SetLineWidth(.3);
        //Expedido en
		$this->mypdf->Cell(0,0,'Expedido en','',0,'R');
		$this->mypdf->Ln(4);
        if(!count($emisor['ExpedidoEn']) && count($emisor['DomicilioFiscal']))
        $emisor['ExpedidoEn']=$emisor['DomicilioFiscal'];
        
        $this->mypdf->SetFont('Arial','I',8);
        $calle = (!empty($emisor['ExpedidoEn']['calle']))?$emisor['ExpedidoEn']['calle']:'Domicilio conocido';
        $calle .= ($calle=='Domicilio conocido' || empty($emisor['ExpedidoEn']['noExterior']))?'':" #{$emisor['ExpedidoEn']['noExterior']}";
        $calle .= ($calle=='Domicilio conocido' || empty($emisor['ExpedidoEn']['noInterior']))?'':" INT. {$emisor['ExpedidoEn']['noInterior']}";
        $this->mypdf->Cell(0,0,utf8_decode($calle),'',0,'R');
        $this->mypdf->Ln(3);
        $colonia = (!empty($emisor['ExpedidoEn']['colonia']))?"COL. {$emisor['ExpedidoEn']['colonia']}  ":'';
        $colonia .= (!empty($emisor['ExpedidoEn']['CodigoPostal']))?"C.P. {$emisor['ExpedidoEn']['CodigoPostal']}":'';
		if(!empty($colonia)){	
			$this->mypdf->Cell(0,0,utf8_decode($colonia),'',0,'R');
			$this->mypdf->Ln(3);
		}
		$localidad="";
		if($emisor['ExpedidoEn']['localidad']!=$emisor['ExpedidoEn']['municipio'])
			$localidad = (!empty($emisor['ExpedidoEn']['localidad']))?"LOC. {$emisor['ExpedidoEn']['localidad']}  ":'';
		$localidad .= (!empty($emisor['ExpedidoEn']['municipio']))?"{$emisor['ExpedidoEn']['municipio']}":'';
		if(!empty($localidad)){		
			$this->mypdf->Cell(0,0,utf8_decode($localidad),'',0,'R');
			$this->mypdf->Ln(3);
		}
		$estado = (!empty($emisor['ExpedidoEn']['estado']))?"{$emisor['ExpedidoEn']['estado']}":'';
		$estado .= (!empty($estado) && !empty($emisor['ExpedidoEn']['pais']))?", {$emisor['ExpedidoEn']['pais']}":$emisor['ExpedidoEn']['pais'];
        if(!empty($localidad)){		
			$this->mypdf->Cell(0,0,utf8_decode($estado),'',0,'R');
		}
		$this->mypdf->Ln(-8);
        
        $serie=(empty($row['serie']))?"-":$row['serie']." ";
		$folio=(empty($row['folio']))?"-":$row['folio'];
        $this->mypdf->SetFont('Arial','B',10);
        $str_width=$this->mypdf->GetStringWidth(utf8_decode('Serie: '));
        $this->mypdf->Cell($str_width+1,3,'Serie: ');
        $this->mypdf->SetFont('Arial','',10);
        $str_width=$this->mypdf->GetStringWidth(utf8_decode($serie));
        $this->mypdf->Cell($str_width+10,3,$serie);
        $this->mypdf->SetFont('Arial','B',10);
        $str_width=$this->mypdf->GetStringWidth(utf8_decode('Folio: '));
        $this->mypdf->Cell($str_width+1,3,'Folio: ');
        $this->mypdf->SetFont('Arial','',10);
        $str_width=$this->mypdf->GetStringWidth(utf8_decode($folio));
        $this->mypdf->Cell(0,3,$folio);
		$this->mypdf->Ln(11);
		
        $this->mypdf->SetFont('Arial','B',12);
        $this->mypdf->Cell(0,0,"Resumen");
        $this->mypdf->Ln(4);
        $this->mypdf->SetFont('Arial','B',10);
        $this->mypdf->Cell(12,7,'UUID','TBLR',0,'L',1);
        $this->mypdf->SetFont('Arial','I',8);
        $this->mypdf->Cell(75,7,strtoupper($row['uuid']),'TBLR',0,'L',0);
        $this->mypdf->SetFont('Arial','B',10);
        $this->mypdf->Cell(13,7,'Fecha','TBLR',0,'L',1);
        $this->mypdf->SetFont('Arial','I',8);
        $this->mypdf->Cell(30,7,$row['fecha'],'TBLR',0,'L',0);
        $this->mypdf->SetFont('Arial','B',10);
		$this->mypdf->Cell(30,7,'T. comprobante','TBLR',0,'L',1);
        $this->mypdf->SetFont('Arial','I',8);
        $this->mypdf->Cell(30,7,utf8_decode($factura['tipocomprobante']),'TBLR',0,'L',0);
        $this->mypdf->Ln(8);
        $this->mypdf->SetFont('Arial','B',10);
		$this->mypdf->Cell(20,7,'T. cambio','TBLR',0,'L',1);
        $this->mypdf->SetFont('Arial','I',8);
        $tcambio=(empty($factura['tipocambio']))?"1.00":number_format((float)$factura['tipocambio'],2);
        $this->mypdf->Cell(13,7,$tcambio,'TBLR',0,'L',0);
        $this->mypdf->SetFont('Arial','B',10);
		$this->mypdf->Cell(20,7,'F. de pago','TBLR',0,'L',1);
        $this->mypdf->SetFont('Arial','I',8);
        $this->mypdf->Cell(50,7,strtolower(utf8_decode($factura['forma_pago'])),'TBLR',0,'L',0);
        $this->mypdf->SetFont('Arial','B',10);
		$this->mypdf->Cell(20,7,'M. de pago','TBLR',0,'L',1);
        $this->mypdf->SetFont('Arial','I',8);
        $this->mypdf->Cell(30,7,strtolower(utf8_decode($factura['metodo_pago'])),'TBLR',0,'L',0);
        $this->mypdf->SetFont('Arial','B',10);
		$this->mypdf->Cell(12,7,'N. cta','TBLR',0,'L',1);
        $this->mypdf->SetFont('Arial','I',8);
        $ncta = (empty($factura['NumCtaPago']))?"-":str_pad($factura['NumCtaPago'],16,'*',STR_PAD_LEFT);
        $this->mypdf->Cell(25,7,utf8_decode($ncta),'TBLR',0,'L',0);
        $this->mypdf->Ln(11);
		/////////////EMISOR//////////////////////
		$this->mypdf->SetFont('Arial','B',12);
        $this->mypdf->Cell(0,0,"Receptor");
        $this->mypdf->Ln(4);
        $this->mypdf->SetFont('Arial','B',10);
		$this->mypdf->Cell(10,7,'RFC','TBLR',0,'L',1);
        $this->mypdf->SetFont('Arial','I',7);
        $this->mypdf->Cell(30,7,strtoupper(utf8_decode($receptor['rfc'])),'TBLR',0,'L',0);
        $this->mypdf->SetFont('Arial','B',10);
		$this->mypdf->Cell(17,7,'Nombre','TBLR',0,'L',1);
        $this->mypdf->SetFont('Arial','I',7);
        $this->mypdf->Cell(0,7,strtoupper(utf8_decode($receptor['nombre'])),'TBLR',0,'L',0);
        $this->mypdf->Ln(8);
        $this->mypdf->SetFont('Arial','B',10);
		$this->mypdf->Cell(30,7,'Domicilio fiscal','TBLR',0,'L',1);
        $this->mypdf->SetFont('Arial','I',7);
        $street=(empty($receptor['Domicilio']['calle']))?"Domicilio conocido":html_entity_decode($receptor['Domicilio']['calle']);
		$street.=(empty($receptor['Domicilio']['noExterior']))?" SN":" ".html_entity_decode($receptor['Domicilio']['noExterior']);
		$street.=(empty($receptor['Domicilio']['noInterior']))?"":" (".html_entity_decode($receptor['Domicilio']['noInterior']).")";
		$street.=(empty($receptor['Domicilio']['colonia']))?"":", ".html_entity_decode($receptor['Domicilio']['colonia']);
		$street.=(empty($receptor['Domicilio']['localidad']))?"":", Loc. ".html_entity_decode($receptor['Domicilio']['localidad']);
		$street.=(empty($receptor['Domicilio']['municipio']))?"":", ".html_entity_decode($receptor['Domicilio']['municipio']);
		$street.=(empty($receptor['Domicilio']['estado']))?"":", ".html_entity_decode($receptor['Domicilio']['estado']);
		$street.=(empty($receptor['Domicilio']['pais']))?"":", ".html_entity_decode($receptor['Domicilio']['pais']);
		$street.=(empty($receptor['Domicilio']['codigoPostal']))?"":", C.P.".html_entity_decode($receptor['Domicilio']['codigoPostal']);
        $this->mypdf->Cell(0,7,strtoupper(utf8_decode($street)),'TBLR',0,'L',0);
        $this->mypdf->Ln(11);
		$this->mypdf->SetFont('Arial','B',12);
        $this->mypdf->Cell(0,0,"Conceptos");
        $this->mypdf->Ln(3);
        $this->mypdf->SetFillColor(247,246,240);
		$this->mypdf->SetDrawColor(247,246,240);
        $this->mypdf->SetFont('Arial','TBL',10);
        $this->mypdf->Cell(15,7,'CANT','TBL',0,'L','1');
        $this->mypdf->Cell(17,7,'UNIDAD','TBL',0,'R','1');
        $this->mypdf->Cell(96,7,'DESCRIPCION','TBL',0,'L','1');
        $this->mypdf->Cell(31,7,'UNITARIO','TBL',0,'R','1');
        $this->mypdf->Cell(31,7,'IMPORTE','TBR',0,'R','1');
        $this->mypdf->Ln(8);
        $importe=0;
        $moneda=(empty($factura['Moneda']))?"MXN":$factura['Moneda'];
        $moneda=(strlen($moneda)>3)?"MXN":$moneda;
		$this->mypdf->SetFont('Arial','',8);
		// Datos
        foreach($conceptos as $item){
			$importe+=(float)$item['importe'];
            $this->mypdf->Cell(15,5,number_format((float)$item['cantidad'],2),'LBR',0,'L');
            $this->mypdf->Cell(17,5,utf8_decode($item['unidad']),'BR',0,'R');
            $this->mypdf->Cell(100,5,utf8_decode($item['descripcion']),'BR',0,'L');
            $this->mypdf->Cell(29,5,money_format('%.2n',(float)$item['valorunitario']) ." ".$moneda,'BR',0,'R');
            $this->mypdf->Cell(29,5,money_format('%.2n',(float)$item['importe']) ." ".$moneda,'BR',0,'R');
            $this->mypdf->Ln(5);
		}
		//Footer de conceptos
		$this->mypdf->SetFillColor(247,246,240);
		$this->mypdf->SetFont('Arial','B',9);
		$this->mypdf->Cell(161,7,"Subtotal",'LBR',0,'R',1);
		$this->mypdf->Cell(0,7,money_format('%.2n',(float)$importe)." ".$moneda,'LBR',0,'R',1);
		$this->mypdf->Ln(7);
		$impuesto=0;
		foreach($impuestos as $key=>$val){
			if(!count($val)) continue;
			$this->mypdf->Cell(161,7,"Impuesto $key",'LB',0,'R',1);
			$this->mypdf->Cell(0,7,"",'BR',0,'',1);
			$this->mypdf->Ln(7);
			foreach($val as $imp){
				if($key=='retenidos'){
					$impuesto+=(float)$imp['importe'] * -1;
					$this->mypdf->Cell(161,7,"{$imp['impuesto']}",'LBR',0,'R',1);
				} else {
					$impuesto+=(float)$imp['importe'];
					$this->mypdf->Cell(161,7,"{$imp['impuesto']}: {$imp['tasa']}%",'LBR',0,'R',1);
				}
				$this->mypdf->Cell(0,7,money_format('%.2n',(float)$imp['importe'])." ".$moneda,'BR',0,'R',1);
				$this->mypdf->Ln(7);
			}
		}
		$this->mypdf->Cell(161,7,"Total",'LBR',0,'R',1);
		$this->mypdf->Cell(0,7,money_format('%.2n',$importe+$impuesto)." ".$moneda,'LBR',0,'R',1);
		$this->mypdf->Ln(8);
		$this->mypdf->SetFont('Arial','',12);
		$this->mypdf->Cell(0,7,num2letras(number_format((float)$importe+$impuesto,2),$moneda),'',0,'C');
		if(strtolower($row['status'])=='timbrada'){
			$this->mypdf->Ln(10);
			$img = str_replace('pdf','png',$file);
			if(file_exists($img)){
				$this->mypdf->Image($img);
				$this->mypdf->Ln(-47);
			} else die("No se encontro el archivo PNG:<br/>$img");
			$this->mypdf->Ln(10);
			$this->mypdf->SetFont('Arial','B',7);
			$this->mypdf->Cell(40,3,"");
			$this->mypdf->Cell(0,6,"Sello digital del CFDI:");
			$this->mypdf->Ln(4);
			$this->mypdf->SetFont('Arial','',6);
			$this->mypdf->Cell(40,3,"");
			$this->mypdf->MultiCell(0,4,$timbre['selloCFD']);
			///////
			$this->mypdf->Ln(2);
			$this->mypdf->SetFont('Arial','B',7);
			$this->mypdf->Cell(40,3,"");
			$this->mypdf->Cell(0,6,"Sello del SAT:");
			$this->mypdf->Ln(4);
			$this->mypdf->SetFont('Arial','',6);
			$this->mypdf->Cell(40,3,"");
			$this->mypdf->MultiCell(0,4,$timbre['selloSAT']);
			/////
			$this->mypdf->Ln(2);
			$this->mypdf->SetFont('Arial','B',7);
			$this->mypdf->Cell(40,3,"");
			$this->mypdf->Cell(0,6,"Cadena original:");
			$this->mypdf->Ln(4);
			$this->mypdf->SetFont('Arial','',6);
			$this->mypdf->Cell(40,3,"");
			$this->mypdf->MultiCell(0,4,$row['co']);
			/////
			$this->mypdf->Ln(6);
			$this->mypdf->SetFont('Arial','B',7);
			$this->mypdf->Cell(48,3,"No de Serie del Certificado del SAT:");
			$this->mypdf->SetFont('Arial','',6);
			$this->mypdf->MultiCell(0,3,$timbre['noCertificadoSAT']);
			///////
			$this->mypdf->Ln(2);
			$this->mypdf->SetFont('Arial','B',7);
			$this->mypdf->Cell(35,3,"Fecha de certificacion:");
			$this->mypdf->SetFont('Arial','',6);
			$this->mypdf->MultiCell(0,3,dateLong(date("Y-m-d",strtotime(str_replace("T", " ",$timbre['FechaTimbrado'])))));
			$this->mypdf->SetY(-35);
			$this->mypdf->SetFont('Arial','',9);
			$this->mypdf->Cell(0,0,utf8_decode("Este documento es una representación impresa de un CFDI"),0,'','C');
			$this->mypdf->Ln(3);
			$this->mypdf->Cell(0,0,utf8_decode("*Efectos fiscales al pago"),0,'','C');
		} elseif(strtolower($row['status'])=='cancelada') {
			$this->mypdf->SetFont('Arial','B',50);
			$this->mypdf->SetTextColor(255,192,203);
			$this->mypdf->RotatedText(60,190,'C A N C E L A D A',45);
		} else {
			$this->mypdf->SetFont('Arial','B',50);
			$this->mypdf->SetTextColor(255,192,203);
			$this->mypdf->RotatedText(60,190,'N O    V A L I D A',45);
		}
        if(!empty($uuid)) $out="F"; else $out="I";
        $this->mypdf->Output($file,$out);
        return (file_exists($file))? $file : false;
	}
	public function download(){
		$post=$this->input->post();
		$uuid=$this->input->post('uuid');
		$sql="SELECT id,fecha FROM ingresos WHERE uuid='$uuid'";
		$query = $this->db->query($sql);
		$row=$query->row_array();
		$anio = date('Y', strtotime($row['fecha']));
		$mes = date('m', strtotime($row['fecha']));
		$pdf=ROOT."files".DS."ingresos".DS."$anio".DS."$mes".DS.$uuid.".pdf";
		$xml = str_replace('pdf','xml',$pdf);
		$fileZip = str_replace('pdf','zip',$pdf);
		$png = str_replace('pdf','png',$pdf);
		if(!file_exists($pdf) && !file_exists($fileZip)){
			$pdf=$this->toPdf($uuid,$pdf);
			if(!$pdf) die('Error: al crear el archivo PDF');
		}
		if(!file_exists($fileZip)){
			if(!file_exists($xml)) die('Error: Se perdio el archivo XML');
			$zip = new ZipArchive();
			if($zip->open($fileZip,ZIPARCHIVE::CREATE) === true) {
				$zip->addFile($pdf,$uuid.'.pdf');
				$zip->addFile($xml,$uuid.'.xml');
				$zip->addFile($png,$uuid.'.png');
				$zip->close();
				unlink($pdf);
				unlink($xml);
				unlink($png);
			} else die('Error: al crear archivo ZIP');
		}
		header("Content-type:application/zip");
		header("Content-Disposition: attachment; filename={$uuid}.zip");
		header("Content-Transfer-Encoding: binary");
		readfile($fileZip);
	}
	public function showpdf(){
		$post=$this->input->post();
		$uuid=$this->input->post('uuid');
		$sql="SELECT id,fecha FROM ingresos WHERE uuid='$uuid'";
		$query = $this->db->query($sql);
		$row=$query->row_array();
		$anio = date('Y', strtotime($row['fecha']));
		$mes = date('m', strtotime($row['fecha']));
		$pdf=ROOT."files".DS."ingresos".DS."$anio".DS."$mes".DS.$uuid.DS.$uuid.".pdf";
		$xml = str_replace('pdf','xml',$pdf);
		$fileZip = str_replace('pdf','zip',$pdf);
		$png = str_replace('pdf','png',$pdf);
		if(!file_exists($pdf)){
			$pdf=$this->toPdf($uuid,$pdf);
			if(!$pdf) die('Error: al crear el archivo PDF');
		}
		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="'.$pdf.'"'); 
		readfile($pdf);
	}
	public function send_email(){
		$post=$this->input->post();
		$config['protocol']    = 'smtp';
		$config['smtp_crypto'] = 'tls';
		$config['smtp_host']    = 'smtp-mail.outlook.com';
		$config['smtp_port']    = '587';
		$config['smtp_timeout'] = '5';
		$config['smtp_user']    = 'ceci2911@hotmail.com';
		$config['smtp_pass']    = '01mami40';
		$config['charset']    = 'utf-8';
		$config['newline']    = "\r\n";
		$config['mailtype'] = 'html'; // or html
		$config['validate'] = TRUE; // bool whether to validate email or not      
		$this->load->library('email');
		$this->email->initialize($config);
		$this->email->from('ceci2911@hotmail.com', 'Optica Madero');
		$post['diremail'] = explode(',',$post['diremail']);
		$this->email->to($post['diremail']);
		$this->email->subject('Sistema de facturacion openadmin');
		$this->email->message($post['msg']);
		if(isset($post['uuid']) && $post['uuid']!=0){
			$sql="SELECT id,fecha,status FROM ingresos WHERE uuid='{$post['uuid']}'";
			$query = $this->db->query($sql);
			$row=$query->row_array();
			$anio = date('Y',strtotime($row['fecha']));
			$mes = date('m',strtotime($row['fecha']));
			if(strtolower($row['status'])=='timbrada' || strtolower($row['status'])=='cancelada'){
				$url = ROOT.'files'.DS.'ingresos'.DS.$anio.DS.$mes.DS.$post['uuid'].DS.$post['uuid'];
				if(!is_file($url.'.xml')){
					$this->plantillas->set_message(6001,"Error: El XML ($url.xml) no fue encontrado, documento corrompido");
					die("Error: El XML no fue encontrado, $url.xml");
				}
				if(!is_file($url.'.pdf')){
					$pdf=$this->toPdf($post['uuid'],$url.'.pdf');
					if(!$pdf){
						$this->plantillas->set_message(6001,"Error: Al crear el documento $url.pdf");
						die('Error: al crear el documento PDF, $url.xml');
					}
				}
				$this->email->attach($url.'.xml');
				$this->email->attach($url.'.pdf');
			}
		}
		$return['result']=$this->email->send();
		$return['debug']=$this->email->print_debugger();
		$return['email']=$post['diremail'];
		$return['msg']=$post['msg'];
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($return));
	}
}
