<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->plantillas->is_session();
		//$this->load->model('home_model','report');
		$this->load->helper('form');
	}
	//Pagina principal
	public function index(){
		$data['date'] = $this->input->post('date');
		$data['top']['title']='Corte de caja';
		$data['top']['scripts'][]['src']=base_url('lib/js/canvasjs.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/report/index.js');
		$data['date'] = empty($data['date'])? date('Y-m-d'):$data['date'];
		$sql="SELECT metodopago AS label,SUM(montopago) AS y FROM ventas WHERE DATE(date)='{$data['date']}' GROUP BY metodopago";
		$query = $this->db->query($sql);
		$data['ventas'] = $query->result_array();
		$sql="SELECT * FROM caja WHERE DATE(date) = '{$data['date']}' LIMIT 2";
		$query = $this->db->query($sql);
		$data['corte'] = $query->result_array();
		$this->plantillas->show_tpl('report/index',$data);
	}
	public function print_ventas(){
		$data['top']['title']='Ventas por fecha';
		$data['top']['scripts'][]['src']=base_url('lib/js/es/grid.locale-es.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/jquery.jqGrid.min.js');
		$data['top']['scripts'][]['src']=base_url('lib/js/view/report/print_ventas.js');
		$data['top']['main'][]=array('click'=>'buscar()','class'=>'search','label'=>'Buscar');
		$data['top']['main'][]=array('click'=>'excel()','class'=>'th','label'=>'Excel','type'=>'success');
		$this->plantillas->show_tpl('report/print_ventas',$data);
	}
	public function print_nota_day(){
		$post=$this->input->post();
		$post['idserver'] = isset($post['idserver'])? date('Y-m-d H:i:s',strtotime($post['idserver'])) : date('Y-m-d');
		$sql="SELECT p.*,c.contacto FROM pedidos AS p LEFT JOIN contactos AS c ON p.idcliente=c.id WHERE ".
		"p.date >= '{$post['idserver']}' ORDER BY p.id";
		$query = $this->db->query($sql);
		$this->load->library('mypdf');
       	$this->mypdf->AddPage();
		$this->mypdf->AliasNbPages();
		$this->mypdf->SetTextColor(0,0,0);
		$this->mypdf->SetFillColor(247,246,240);
		$this->mypdf->SetDrawColor(237,236,230);
		$this->mypdf->SetLineWidth(.2);
		//Generales
		$this->mypdf->SetFont('Arial','B',14);
		$this->mypdf->Cell(0,7,'REPORTE DIARIO DE PEDIDOS','b',1);
		$this->mypdf->SetFont('Arial','',9);
		$this->mypdf->Cell(0,7,dateLong($post['idserver']),'',1);
		if($query->num_rows()){
			$rows = $query->result_array();
			$this->mypdf->Ln(10);
			$w = ($this->mypdf->w-15)/10;
			$this->mypdf->SetFont('Arial','B',10);
			$this->mypdf->Cell($w-8,7,'Folio','TLBR',0,'L',1);
			$this->mypdf->Cell($w*3,7,'Paciente','TLBR',0,'L',1);
			$this->mypdf->Cell($w,7,'Entrega','TLBR',0,'L',1);
			$this->mypdf->Cell($w,7,'Consulta','TLBR',0,'L',1);
			$this->mypdf->Cell($w,7,utf8_decode('Armazón'),'TLBR',0,'L',1);
			$this->mypdf->Cell($w,7,'Lente','TLBR',0,'L',1);
			$this->mypdf->Cell($w,7,'Anticipo','TLBR',0,'L',1);
			$this->mypdf->Cell(0,7,'Pendiente','TLBR',1,'L',1);
			$this->mypdf->SetFont('Arial','',8);
			$consulta=0;$armazon=0;$lente=0;$anticipo=0;$pendiente=0;$descuentos=0;
			foreach($rows AS $row){
				$this->mypdf->Cell($w-8,7,str_pad($row['id'],6,0,STR_PAD_LEFT),'LBTR',0,'R');
				$this->mypdf->Cell($w*3,7,utf8_decode(substr($row['contacto'],0,32)),'LBTR');
				$this->mypdf->Cell($w,7,dateCut($row['fentrega']),'LBTR',0,'C');
				$this->mypdf->Cell($w,7,"$ ".$row['consulta'],'LBTR',0,'R');
				$this->mypdf->Cell($w,7,"$ ".$row['armazon'],'LBTR',0,'R');
				$this->mypdf->Cell($w,7,"$ ".$row['lente'],'LBTR',0,'R');
				$this->mypdf->Cell($w,7,"$ ".$row['anticipo'],'LBTR',0,'R');
				$this->mypdf->Cell(0,7,"$ ".$row['total'],'LBTR',1,'R');
				$consulta+=(float)$row['consulta'];
				$armazon+=(float)$row['armazon'];
				$lente+=(float)$row['lente'];
				$anticipo+=(float)$row['anticipo'];
				$pendiente+=(float)$row['total'];
				$descuentos+=(float)$row['descuento'];
			}
			$this->mypdf->SetFont('Arial','B',10);
			$this->mypdf->Cell($w*5-8,7,'Sumatorias ','LBTR',0,'R');
			$this->mypdf->SetFont('Arial','',8);
			$this->mypdf->Cell($w,7,'$ '.$consulta,'LBTR',0,'R');
			$this->mypdf->Cell($w,7,'$ '.$armazon,'LBTR',0,'R');
			$this->mypdf->Cell($w,7,'$ '.$lente,'LBTR',0,'R');
			$this->mypdf->Cell($w,7,'$ '.$anticipo,'LBTR',0,'R');
			$this->mypdf->Cell(0,7,'$ '.$pendiente,'LBTR',1,'R');
			$this->mypdf->Ln(5);
			$this->mypdf->SetFont('Arial','B',9);
			$this->mypdf->Cell($w*3-8,7,'Descuentos ','LBTR',0,'R');
			$this->mypdf->SetFont('Arial','',8);
			$this->mypdf->Cell($w,7,'$ '.$descuentos,'LBTR',1,'R');
			$this->mypdf->SetFont('Arial','B',9);
			$this->mypdf->Cell($w*3-8,7,'Venta ','LBTR',0,'R');
			$this->mypdf->SetFont('Arial','',8);
			$this->mypdf->Cell($w,7,'$ '.($consulta+$armazon+$lente),'LBTR',1,'R');
			$this->mypdf->SetFont('Arial','B',9);
			$this->mypdf->Cell($w*3-8,7,'Venta total ','LBTR',0,'R');
			$this->mypdf->SetFont('Arial','',8);
			$this->mypdf->Cell($w,7,'$ '.($consulta+$armazon+$lente-$descuentos),'LBTR',1,'R');
			$this->mypdf->SetFont('Arial','B',9);
			$this->mypdf->Cell($w*3-8,7,'Por cobrar ','LBTR',0,'R');
			$this->mypdf->SetFont('Arial','',8);
			$this->mypdf->Cell($w,7,'$ '.($consulta+$armazon+$lente-$descuentos-$anticipo),'LBTR',1,'R');
		} else {
			$this->mypdf->Ln(10);
			$this->mypdf->SetFont('Arial','',10);
			$this->mypdf->Cell(0,7,'No hay registros para esta fecha.','',1);
		}
		$this->mypdf->Output();
	}
	public function print_nota_pdf(){
		$post=$this->input->post();
		if(!isset($post['idserver'])) $post['idserver'] = $post['id'];
		if(!isset($post['idserver']) || !$post['idserver']){
			die('El indentificador de la nota ['.$post['idserver'].'] no es valido o no se encuantra en la base de datos');
		}
		$sql="SELECT p.*,c.contacto,c.telnumber FROM {$post['year']}_pedidos AS p LEFT JOIN contactos AS c ON c.id=p.idcliente".
		"  WHERE p.id = '{$post['idserver']}' LIMIT 1";
		$query=$this->db->query($sql);
		if($query->num_rows()){
			$pedido = $query->row_array();
			$pedido['telnumber'] = json_decode($pedido['telnumber'],true);
			$pedido['banco'] = json_decode($pedido['banco'],true);
			if($pedido['test']>0){
				$sql="SELECT * FROM examenes WHERE id = '{$pedido['test']}' LIMIT 1";
				$query=$this->db->query($sql);
				if($query->num_rows()){
					$examen = $query->row_array();
					$examen['txoptico'] = json_decode($examen['txoptico'],true);
				} else $examen = array();
			} else $examen=array();
		} else $pedido = array();
		
		$confpdf = array('T'=>array(215.9,139.7),'P'=>'l');
		$this->load->library('mypdf',$confpdf);
		$w = ($this->mypdf->w-15)/6;
		$h = ($this->mypdf->h-10)/2;
        for($a=0;$a<2;$a++){ //Numero de copia
			$this->mypdf->AddPage();
			$this->mypdf->AliasNbPages();
			$this->mypdf->SetTextColor(0,0,0);
			$this->mypdf->SetFillColor(255,255,255);
			$this->mypdf->SetDrawColor(0,0,0);
			$this->mypdf->SetLineWidth(.2);
			//Generales
			$this->mypdf->SetFont('Arial','',10);
			$this->mypdf->Cell($w*4,5,$pedido['idcliente'].' - '.utf8_decode(str_replace(array('&Ntilde;','&ntilde;'),array('Ñ','ñ'), $pedido['contacto'])),'B',0,'L');
			
			$this->mypdf->SetFont('Arial','B',10);
			$this->mypdf->Cell($w-15,5,'Telefono','B');
			$this->mypdf->SetFont('Arial','',10);
			if(!empty($pedido['telnumber']['mobil'])) $this->mypdf->Cell(0,5,$pedido['telnumber']['mobil'],'B',1,'R');
			elseif(!empty($pedido['telnumber']['particular'])) $this->mypdf->Cell(0,5,str_replace('312','',$pedido['telnumber']['particular']),'B',1,'R');
			elseif(!empty($pedido['telnumber']['oficina'])) $this->mypdf->Cell(0,5,str_replace('312','',$pedido['telnumber']['oficina']),'B',1,'R');
			else $this->mypdf->Cell(0,5,'--','B',1,'R');
			$this->mypdf->Ln(3);
			//EXAMEN
			if(count($examen)&&$a){
				$this->mypdf->SetY(105);
				$this->mypdf->SetFont('Arial','B',10);
				$this->mypdf->Cell($w/4,4,'');
				$this->mypdf->Cell($w/2,4,'Esfera','TRBL');
				$this->mypdf->Cell($w/2,4,utf8_decode('Cilíndro'),'TRB');
				$this->mypdf->Cell($w/2,4,'Eje','TRB');
				$this->mypdf->Cell($w/2,4,utf8_decode('Adición'),'TRB');
				$this->mypdf->Cell($w/2,4,'D/P','TRB');
				$this->mypdf->Cell($w/2,4,'Altura','TRB',1);
				
				$this->mypdf->Cell($w/4,4,'OD','TRBL');
				$this->mypdf->SetFont('Arial','',9);
				$this->mypdf->Cell($w/2,4,$examen['esferaod']>0?'+'.$examen['esferaod']:$examen['esferaod'],'LB',0,'R');
				$this->mypdf->Cell($w/2,4,$examen['cilindrod'],'LB',0,'R');
				$this->mypdf->Cell($w/2,4,$examen['ejeod'].utf8_decode(' °'),'LB',0,'R');
				$this->mypdf->Cell($w/2,4,"+".$examen['adiciond'],'LB',0,'R');
				$this->mypdf->Cell($w/2,4,$examen['dpod'],'LB',0,'R');
				$this->mypdf->Cell($w/2,4,$examen['alturaod'],'RBL',1,'R');
				
				$this->mypdf->SetFont('Arial','B',10);
				$this->mypdf->Cell($w/4,4,'OI','TRBL');
				$this->mypdf->SetFont('Arial','',9);
				$this->mypdf->Cell($w/2,4,$examen['esferaoi']>0?"+".$examen['esferaoi']:$examen['esferaoi'],'LB',0,'R');
				$this->mypdf->Cell($w/2,4,$examen['cilindroi'],'LB',0,'R');
				$this->mypdf->Cell($w/2,4,$examen['ejeoi'].utf8_decode(' °'),'LB',0,'R');
				$this->mypdf->Cell($w/2,4,"+".$examen['adicioni'],'LB',0,'R');
				$this->mypdf->Cell($w/2,4,$examen['dpoi'],'LB',0,'R');
				$this->mypdf->Cell($w/2,4,$examen['alturaoi'],'RBL',1,'R');
			}
			$items = json_decode($pedido['items'],true);
			
			$this->mypdf->SetY(35);
			$this->mypdf->SetFont('Arial','B',8);
			//$this->mypdf->Cell($w*2-17,4,'');
			$this->mypdf->Cell($w/3,4,'	CANT','B');
			$this->mypdf->Cell($w*4+30,4,'DESCRIPCION','B');
			$this->mypdf->Cell(0,4,'PRECIO','B',1);
			$this->mypdf->SetFont('Arial','',9);
			foreach ($items as $item){
				//$this->mypdf->Cell($w*2-17,4,'');
				$this->mypdf->Cell($w/3,4,$item['und'],'',0,'');
				$this->mypdf->Cell($w*4+30,4,substr("[{$item['code']}] ".$item['item'],0,70),'',0,'');
				$this->mypdf->Cell(0,4,money_format('%.2n',(float)$item['price']),'',1,'R');
			}
			//Totales
			//$this->mypdf->Cell($w*2-17,1,'');
			$this->mypdf->Cell(0,1,'','',1);
			$this->mypdf->SetFont('Arial','B',10);
			$this->mypdf->Cell($w*4,4,'');
			$this->mypdf->Cell($w,4,'Sub-total','B');
			$this->mypdf->SetFont('Arial','',10);
			$this->mypdf->Cell(0,4,money_format('%.2n',(float)$pedido['subtotal']),'B',1,'R');
			$this->mypdf->Cell($w*4,4,'');
			$this->mypdf->SetFont('Arial','B',12);
			$this->mypdf->Cell($w,5,'Anticipo','B');
			$this->mypdf->SetFont('Arial','',12);
			$this->mypdf->Cell(0,5,money_format('%.2n',(float)$pedido['anticipo']),'B',1,'R');
			$this->mypdf->Cell($w*4,4,'');
			$this->mypdf->SetFont('Arial','B',10);
			$this->mypdf->Cell($w,4,'Saldo','B');
			$this->mypdf->Cell(0,4,money_format('%.2n',(float)$pedido['total']),'B',1,'R');

			//Cabeceras
			if($a){
				/*
				$this->mypdf->SetY(15);
				$this->mypdf->SetFont('Arial','',8);
				$this->mypdf->Cell($w*5,5,'NPedido','',0,'R');
				$this->mypdf->Cell(0,5,'','LBTR',0,'R');
				*/
				//Observaciones
				$this->mypdf->SetY($h+30);
				$this->mypdf->SetFont('Arial','B',10);
				$this->mypdf->Cell($w*4-17,4,'');
				$this->mypdf->Cell(0,4,'Observaciones','',1);
				$this->mypdf->SetFont('Arial','',7);
				$this->mypdf->Cell($w*4-17,4,'');
				$this->mypdf->MultiCell(0,3,utf8_decode($pedido['observaciones']),'',1);
			} else {
				$this->mypdf->SetY(-37);
				$this->mypdf->SetFont('Arial','',10);
				$txt = file_get_contents(ROOT.'lib'.DS.'pie_notaventa.txt');
				$this->mypdf->MultiCell(0,3,utf8_decode($txt));
			}
			$this->mypdf->SetY(12);
			$this->mypdf->SetFont('Arial','',10);
			$this->mypdf->Cell($w*5+10,5,'NOTA DE VENTA','',0,'R');
			$this->mypdf->SetFont('Arial','B',12);
			$this->mypdf->Cell(0,5,str_pad($post['idserver'],6,0,STR_PAD_LEFT),'',0,'R');
			/*
			$this->mypdf->SetFont('Arial','',10);
			$this->mypdf->SetY(27);
			$this->mypdf->Cell($w*4+20,5,'Fecha de pedido','',0,'R');
			$this->mypdf->Cell(0,5,dateCut($pedido['date']),'',0,'R');
			*/
		}// Fin de copias
		$this->mypdf->Output();
	}
	public function print_examen(){
		$post=$this->input->post();
		if(!isset($post['id'])){
			die('El indentificador del examen ['.$post['id'].'] no es valido o no se encuantra en la base de datos');
		}
		$sql="SELECT e.*,c.id as idcliente,c.name,c.contacto,c.telnumber FROM examenes as e LEFT JOIN contactos as c ON e.idclient=c.id WHERE e.id = '{$post['id']}'";
		$query=$this->db->query($sql);
		if($query->num_rows()){
			$examen = $query->row_array();
			$examen['txoptico'] = json_decode($examen['txoptico'],true);
			$examen['telnumber'] = json_decode($examen['telnumber'],true);
		} else $examen = array();
		
		$confpdf = array('T'=>array(215.9,139.7),'P'=>'l');
		$this->load->library('mypdf',$confpdf);
		$w = ($this->mypdf->w-15)/6;
		$h = ($this->mypdf->h-10)/2;
		
		$this->mypdf->AddPage();
		$this->mypdf->AliasNbPages();
		$this->mypdf->SetTextColor(0,0,0);
		$this->mypdf->SetFillColor(247,246,240);
		$this->mypdf->SetDrawColor(237,236,230);
		$this->mypdf->SetLineWidth(.2);
		//Generales
		$this->mypdf->SetFont('Arial','B',10);
		$this->mypdf->Cell($w-15,5,'Paciente','TLBR',0,'L',1);
		$this->mypdf->SetFont('Arial','',10);
		$this->mypdf->Cell($w*3,5,$examen['idcliente'].' - '.utf8_decode(str_replace(array('&Ntilde;','&ntilde;'),array('Ñ','ñ'), $examen['contacto'])),'LBTR');
		
		$this->mypdf->SetFont('Arial','B',10);
		$this->mypdf->Cell($w-12,5,'Fecha','TLBR',0,'L',1);
		$this->mypdf->SetFont('Arial','',10);
		$this->mypdf->Cell(0,5,$examen['date']!='0000-00-00'? dateCut($examen['date']):'','LBTR',1,'R');
		$this->mypdf->Ln(4);
		$this->mypdf->SetFont('Arial','B',10);
		$this->mypdf->Cell($w-12,5,'Diagnostico','TLBR',0,'L',1);
		$this->mypdf->SetFont('Arial','',10);
		$this->mypdf->Cell($w*2,5,strtoupper(utf8_decode(str_replace('Hemetrope', 'HemÉtrope', $examen['diagnostico']))),'LBTR');
		$this->mypdf->SetFont('Arial','B',10);
		$this->mypdf->Cell($w-12,5,'Presbicie','TLBR',0,'L',1);
		$this->mypdf->SetFont('Arial','',10);
		$this->mypdf->Cell($w/2,5,$examen['presbicie']? 'SI':'NO','LBTR',1,'C');
		$this->mypdf->Ln(6);
		//EXAMEN
		$this->mypdf->SetFillColor(247,246,240);
		$this->mypdf->SetDrawColor(0,0,0);
		$this->mypdf->SetFont('Arial','B',12);
		$this->mypdf->Cell($w/3,6,'');
		$this->mypdf->Cell($w-10,6,'Esfera','TRBL');
		$this->mypdf->Cell($w-10,6,utf8_decode('Cilíndro'),'TRB');
		$this->mypdf->Cell($w-10,6,'Eje','TRB');
		$this->mypdf->Cell($w-10,6,utf8_decode('Adición'),'TRB');
		$this->mypdf->Cell($w-10,6,'D/P','TRB');
		$this->mypdf->Cell($w-10,6,'Altura','TRB',1);
		$this->mypdf->Cell($w/3,6,'OD','TRBL');
		$this->mypdf->SetFont('Arial','',12);
		$this->mypdf->Cell($w-10,6,$examen['esferaod']>0?'+'.$examen['esferaod']:$examen['esferaod'],'LB',0,'R',1);
		$this->mypdf->Cell($w-10,6,$examen['cilindrod'],'LB',0,'R',1);
		$this->mypdf->Cell($w-10,6,$examen['ejeod'].utf8_decode(' °'),'LB',0,'R',1);
		$this->mypdf->Cell($w-10,6,"+".$examen['adiciond'],'LB',0,'R',1);
		$this->mypdf->Cell($w-10,6,$examen['dpod'],'LB',0,'R',1);
		$this->mypdf->Cell($w-10,6,$examen['alturaod'],'RBL',1,'R',1);
		$this->mypdf->SetFont('Arial','B',12);
		$this->mypdf->Cell($w/3,6,'OI','TRBL');
		$this->mypdf->SetFont('Arial','',12);
		$this->mypdf->Cell($w-10,6,$examen['esferaoi']>0?"+".$examen['esferaoi']:$examen['esferaoi'],'LB',0,'R',1);
		$this->mypdf->Cell($w-10,6,$examen['cilindroi'],'LB',0,'R',1);
		$this->mypdf->Cell($w-10,6,$examen['ejeoi'].utf8_decode(' °'),'LB',0,'R',1);
		$this->mypdf->Cell($w-10,6,"+".$examen['adicioni'],'LB',0,'R',1);
		$this->mypdf->Cell($w-10,6,$examen['dpoi'],'LB',0,'R',1);
		$this->mypdf->Cell($w-10,6,$examen['alturaoi'],'RBL',1,'R',1);
		$this->mypdf->Ln(6);
		
		$this->mypdf->SetFont('Arial','B',12);
		$this->mypdf->Cell($w*4,5,'Tratamiento oftalmico','',1);
		$this->mypdf->SetFont('Arial','',12);
		$this->mypdf->MultiCell($w*4,5,utf8_decode($examen['txoftalmico']));
		
		$this->mypdf->SetFont('Arial','',10);
		$this->mypdf->SetY(-43);
		$txt = file_get_contents(ROOT.'lib'.DS.'responsable.txt');
		$this->mypdf->MultiCell(0,5,utf8_decode($txt),'','R');
		$this->mypdf->Output();
	}
}
