<?php
date_default_timezone_set('America/Mexico_City');
class satxml {
	public	$xml='',$cadena_original='',$sello='',$root='',$xslt='',$debug='',$num_certificado='',
			$certificado='',$conf='',$factura='',$tmpfile='',$cfdi='',$emisor='',$receptor='',
			$conceptos='',$impuestos='',$pac='',$ruta='',$sat='',$urlcancel='',$urltimbre='';
	function __construct($data){
		$this->xml = new DOMdocument("1.0","UTF-8");
		if(isset($data['conf'])) $this->conf = $data['conf'];
		if(isset($data['factura'])) $this->factura = $data['factura'];
		if(isset($data['tmpfile'])) $this->tmpfile = $data['tmpfile'];
		if(isset($data['cfdi'])) $this->cfdi = $data['cfdi'];
		if(isset($data['emisor'])) $this->emisor = $data['emisor'];
		if(isset($data['receptor'])) $this->receptor = $data['receptor'];
		if(isset($data['conceptos'])) $this->conceptos = $data['conceptos'];
		if(isset($data['impuestos'])) $this->impuestos = $data['impuestos'];
		if(isset($data['xslt'])) $this->xslt = $data['xslt'];
		if(isset($data['debug'])) $this->debug = $data['debug'];
		if(isset($data['pac'])) $this->pac = $data['pac'];
		if(isset($data['ruta'])) $this->ruta = $data['ruta'];
		if(isset($data['sat'])) $this->sat = $data['sat'];
		$this->urlcancel = "http://demo-facturacion.finkok.com/servicios/soap/cancel.wsdl";
		$this->urltimbre = "http://demo-facturacion.finkok.com/servicios/soap/stamp.wsdl";
		$cer = $data['conf']['cer'];
		$key = $data['conf']['key'];
		if(!file_exists($cer) || !file_exists($key) || !file_exists("$cer.txt")){
			$pem = $this->satxml_certificado_pem();
			$this->num_certificado=(isset($pem['certificado_no_serie']))?($pem['certificado_no_serie']):'';
			$this->certificado=(isset($pem['certificado_pem_txt']))?($pem['certificado_pem_txt']):'';
			$file="$cer.txt";
			@unlink($file);
			if(!empty($this->num_certificado)){
				if(file_exists($file))
					@chmod($file, 0777);
				if(($wh = fopen($file,'wb')) === false)
					return false;
				if (fwrite($wh,$this->num_certificado) === false){
					fclose($wh);
					return false;
				}
				fclose($wh);
				@chmod($file, 0777);
			}
		} else {
			$this->num_certificado = file_get_contents("$cer.txt");
			$this->certificado = $this->satxml_certificado_pub();
		}
	}
	function satxml_get(){
		if(!$this->satxml_genera_xml()) return false;
		$this->satxml_genera_cadena_original();
		$this->satxml_sella();
		return $this->satxml_getxml_string();
	}
	function satxml_save(){
		if(!$this->satxml_genera_xml()) return false;
		$this->satxml_genera_cadena_original();
		$this->satxml_sella();
		$xml = $this->satxml_getxml_string();
		$file = $this->debug.$this->tmpfile.".xml";
		$wh = '';
        if(strlen($xml)>10){
            @unlink($file);
            if (file_exists($file))
				@chmod($file, 0777);
            if (($wh = fopen($file, 'wb')) === false) die("ERROR ESCRITURA EN  $file");
            if (fwrite($wh,$xml) === false){
				fclose($wh);
                die("ERROR ESCRITURA EN  $file");
            }
            fclose($wh);
            @chmod($file, 0777); 
		}
		return $file;
	}
	function satxml_genera_xml() {
		if(!$this->satxml_generales()) return false;
		if(!$this->satxml_emisor()) return false;
		if(!$this->satxml_receptor()) return false;
		if(!$this->satxml_conceptos()) return false;
		if(!$this->satxml_impuestos()) return false;
		return true;
	}
	function satxml_generales(){
		$factura=$this->factura;
		if(!count($factura)) return false;
		$this->root = $this->xml->createElement("cfdi:Comprobante");
		$this->root = $this->xml->appendChild($this->root);
		$this->satxml_cargaAtt($this->root,
			array("xmlns:cfdi"=>"http://www.sat.gob.mx/cfd/3",
				"xmlns:xsi"=>"http://www.w3.org/2001/XMLSchema-instance",
				"xsi:schemaLocation"=>"http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd"
			)
		);
		$this->satxml_cargaAtt($this->root,
			array("version"=>"3.2",
				"serie"=>$factura['serie'],
				"folio"=>$factura['folio'],
				"fecha"=>str_replace(' ','T',$factura['fecha_expedicion']),
				"sello"=>"@",
				"formaDePago"=>$factura['forma_pago'],
				"noCertificado"=>$this->num_certificado,
				"certificado"=>$this->certificado,
				"subTotal"=>$factura['subtotal'],
				"descuento"=>(isset($factura['descuento']))?$factura['descuento']:'',
				"total"=>$factura['total'],
				"tipoDeComprobante"=>$factura['tipocomprobante'],
				"metodoDePago"=>$factura['metodo_pago'],
				"LugarExpedicion"=>$factura['LugarExpedicion'],
				"TipoCambio"=>$factura['tipocambio'],
				"Moneda"=>$factura['moneda'],
				"NumCtaPago"=>(isset($factura['NumCtaPago']))?$factura['NumCtaPago']:'')
		);
		return true;
	}
	function satxml_emisor(){
		$emisor_data = $this->emisor;
		if(!count($emisor_data)) return false;
		$emisor = $this->xml->createElement("cfdi:Emisor");
		$emisor = $this->root->appendChild($emisor);
		$this->satxml_cargaAtt($emisor,
			array("rfc"=>$emisor_data['rfc'],
				"nombre"=>$emisor_data['nombre']
			)
		);
		$emisor_dom = $emisor_data['DomicilioFiscal'];
		$domfis = $this->xml->createElement("cfdi:DomicilioFiscal");
		$domfis = $emisor->appendChild($domfis);
		$this->satxml_cargaAtt($domfis,
			array("calle"=>$emisor_dom['calle'],
				"noExterior"=>$emisor_dom['noExterior'],
				"noInterior"=>$emisor_dom['noInterior'],
				"colonia"=>$emisor_dom['colonia'],
				"localidad"=>(isset($factura['localidad']))?$factura['localidad']:'',
				"municipio"=>$emisor_dom['municipio'],
				"estado"=>$emisor_dom['estado'],
				"pais"=>$emisor_dom['pais'],
				"codigoPostal"=>$emisor_dom['CodigoPostal']
			)
		);
		$regimen = $this->xml->createElement("cfdi:RegimenFiscal");
		$expedido = $emisor->appendChild($regimen);
		$this->satxml_cargaAtt($regimen,array("Regimen"=>$this->factura['RegimenFiscal']));
		return true;
	}
	//Datos del Receptor
	function satxml_receptor(){
		$receptor_data = $this->receptor;
		if(!count($receptor_data)) return false;
		$receptor = $this->xml->createElement("cfdi:Receptor");
		$receptor = $this->root->appendChild($receptor);
		$this->satxml_cargaAtt($receptor,array(
			"rfc"=>$receptor_data['rfc'],
			"nombre"=>$receptor_data['nombre']
             )
		);
		$receptor_dom = $receptor_data['Domicilio'];
		$domicilio = $this->xml->createElement("cfdi:Domicilio");
		$domicilio = $receptor->appendChild($domicilio);
		$this->satxml_cargaAtt($domicilio,array(
				"calle"=>$receptor_dom['calle'],
                "noExterior"=>$receptor_dom['noExterior'],
                "noInterior"=>$receptor_dom['noInterior'],
                "colonia"=>$receptor_dom['colonia'],
                "localidad"=>(isset($factura['localidad']))?$factura['localidad']:'',
                "municipio"=>$receptor_dom['municipio'],
                "estado"=>$receptor_dom['estado'],
                "pais"=>$receptor_dom['pais'],
                "codigoPostal"=>$receptor_dom['CodigoPostal']
			)
		);
		return true;
	}
	//Detalle de los conceptos/productos de la factura
	function satxml_conceptos(){
		$conseptos_data = $this->conceptos;
		if(!count($conseptos_data)) return false;
		$conceptos = $this->xml->createElement("cfdi:Conceptos");
		$conceptos = $this->root->appendChild($conceptos);
		foreach($conseptos_data AS $val){
			$concepto = $this->xml->createElement("cfdi:Concepto");
			$concepto = $conceptos->appendChild($concepto);
			$this->satxml_cargaAtt($concepto,array(
				"noIdentificacion"=>$val['ID'],
				"cantidad"=>$val['cantidad'],
                "unidad"=>$val['unidad'],
                "descripcion"=>$val['descripcion'],
                "valorUnitario"=>$val['valorunitario'],
                "importe"=>$val['importe'])
			);
		}
		return true;
	}
	//Impuesto (IVA)
	function satxml_impuestos(){
		$impuestos_data = $this->impuestos;
		if(!count($impuestos_data)) return false;
		$impuestos = $this->xml->createElement("cfdi:Impuestos");
		$impuestos = $this->root->appendChild($impuestos);
		$importe = 0.0;
		if(isset($impuestos_data['retenidos'])){
			$traslados = $this->xml->createElement("cfdi:Retenciones");
			$traslados = $impuestos->appendChild($traslados);
			$impuesto = $this->xml->createElement("cfdi:Retencion");
			$impuesto = $traslados->appendChild($impuesto);
			foreach($impuestos_data['retenidos'] AS $value){
				$this->satxml_cargaAtt($impuesto,array(
					"impuesto"=>$value['impuesto'],
					"importe"=>$value['importe'])
				);
				$importe += $value['importe'];
			}
			$impuestos->SetAttribute("totalImpuestosRetenidos",sprintf('%1.2f',$importe));	
		}
		if(isset($impuestos_data['translados'])){
			$traslados = $this->xml->createElement("cfdi:Traslados");
			$traslados = $impuestos->appendChild($traslados);
			$impuesto = $this->xml->createElement("cfdi:Traslado");
			$impuesto = $traslados->appendChild($impuesto);
			foreach($impuestos_data['translados'] AS $value){
				$this->satxml_cargaAtt($impuesto,array(
					"impuesto"=>$value['impuesto'],
					"tasa"=>$value['tasa'],
					"importe"=>$value['importe'])
				);
				$importe += $value['importe'];
			}
			$impuestos->SetAttribute("totalImpuestosTrasladados",sprintf('%1.2f',$importe));	
		}
		return true;
	}
	//genera_cadena_original
	private function satxml_genera_cadena_original(){
		$file=$this->xslt;
		if(!class_exists('XsltProcessor')) die("La libreria XSLT no ha sido instalada");
		if(!file_exists($file)) die("El archivo '$file', no existe.");
		$xmlDoc = new DOMDocument;
		$xmlDoc->loadXML($this->xml->saveXML());
		$xslDoc = new DOMDocument;
		$xslDoc->load($file);
		$xslt = new XSLTProcessor;
		@$xslt->importStyleSheet($xslDoc);
		$cadena = @$xslt->transformToXML($xmlDoc);
		$this->cadena_original = (empty($cadena))?"error":$cadena;
	}
	//Calculo de sello
	function satxml_sella(){
		$cadena_generada = null;
		$pkeyid=null;
		$cadena = $this->cadena_original;
		$certificado = (!empty($this->certificado))?$this->certificado:$this->satxml_certificado_pub();
		$key=$this->conf['key'];
		$cer=$this->conf['cer'];
		$pass=$this->conf['pass'];
		$num_serie = (!empty($this->num_certificado))?$this->num_certificado:file_get_contents("$cer.txt");
		if(!file_exists($key) || !file_exists($cer) || empty($pass))
			die('Los parametros de confirugacion del SAT no han sido establecido correctamente');
		if(!function_exists('openssl_sign'))
			die ("La libreria OPENSSL no ha sido instalada");
		$pkeyid = openssl_get_privatekey(file_get_contents($key));
    	openssl_sign($cadena,$cadena_generada,$pkeyid,OPENSSL_ALGO_SHA1);
    	openssl_free_key($pkeyid);
    	$this->sello = base64_encode($cadena_generada);
		$this->root->setAttribute("sello",$this->sello);
		$this->root->setAttribute("certificado",$certificado);
		$this->root->setAttribute("noCertificado",$num_serie);
	}
	//Termina, graba en edidata o genera archivo en el disco
	private function satxml_getxml_string(){
		$this->xml->formatOutput = true;
		return (string) $this->xml->saveXML();
	}
	//Funcion que carga los atributos a la etiqueta XML
	private function satxml_cargaAtt(&$nodo, $attr) {
		$quitar = array('sello'=>1,'noCertificado'=>1,'certificado'=>1);
		foreach ($attr as $key => $val) {
			$val = preg_replace('/\s\s+/', ' ', $val);   // Regla 5a y 5c
			$val = trim($val);                           // Regla 5b
			if (strlen($val)>0) {   // Regla 6
				$val = utf8_encode(str_replace("|","/",$val)); // Regla 1
				$nodo->setAttribute($key,$val);
			}
		}
	}
	private function satxml_certificado_pem(){
		$conf = $this->conf;
		if(!count($conf) || !is_array($conf) || empty($conf['cer']) || empty($conf['key']) || empty($conf['pass']))
			return false;
		$cer = str_replace('.pem','',$conf['cer']);
		$key = str_replace('.pem','',$conf['key']);
		$pass = $conf['pass'];
		@unlink("$cer.pem");
		@unlink("$key.pem");
		//genera PEM privado
		$comando="openssl pkcs8 -inform DER -in $key -out $key.pem -passin pass:$pass";
		$resultado=shell_exec($comando);
		//genera PEM publico
		$comando="openssl x509 -inform DER -outform PEM -in $cer -pubkey >$cer.pem";
		$resultado=shell_exec($comando);
		//datos generales
		$comando="openssl x509 -in $cer.pem  -issuer -noout";
		$resultado1=shell_exec($comando);
		$resultado1="$resultado1";
		//fecha valides
		$comando="openssl x509 -in $cer.pem  -startdate -enddate -noout";
		$resultado2=shell_exec($comando);
		// serie matriz
		$comando="openssl x509 -in $cer.pem  -subject -noout";
		$resultado3=shell_exec($comando);
		//serie
		$comando="openssl x509 -in $cer.pem  -serial -noout";
		$resultado4=shell_exec($comando);
		$resultado="$resultado1
		$resultado2
		$resultado3
		$resultado4";
		if(filesize("$key.pem")<10)
			unlink("$key.pem");
		if(filesize("$cer.pem")<10)
			unlink("$cer.pem");
		$resultado=str_replace('\x','%',$resultado);
		$resultado=rawurldecode($resultado);
		list($fecha_inicial_tmp,$fecha_final_tmp)=explode("\n",$resultado2);
		list($tmp,$fecha_inicial_txt)=explode('=',$fecha_inicial_tmp);
		list($tmp,$fecha_final_txt)=explode('=',$fecha_final_tmp);
		$fecha_inicial_time=strtotime($fecha_inicial_txt);
		$fecha_final_time=strtotime($fecha_final_txt);
		list($tmp,$tmp,$rfc)=explode('/',$resultado3);
		$rfc=str_replace(' ','',$rfc);
		list($tmp,$serial)=explode('=',$resultado4);
		$serial=str_replace(' ','',$serial);
		$cer_txt = $this->satxml_certificado_pub();
		return array(
			'certificado_pem_txt'=>$cer_txt,
			'certificado_info'=>$resultado,
			'fecha_valido_inicia'=>$fecha_inicial_time,
			'fecha_valido_fin'=>$fecha_final_time,
			'certificado_no_serie'=>$serial
		);
	}
	private function satxml_certificado_pub(){
		$certificado = ''; 
		$datos = file($this->conf['cer']);
		$carga=false;
		for ($i=0;$i<sizeof($datos);$i++){
			if (strstr($datos[$i],"END CERTIFICATE")) $carga=false;
			if ($carga)$certificado .= trim($datos[$i]);
			if (strstr($datos[$i],"BEGIN CERTIFICATE")) $carga=true;
		}
		return str_replace(' ','',$certificado);
	}
	//Envio el XML  a timbrar
	public function satxml_timbrar(){
		if(!class_exists('nusoap_client')){
			if(function_exists('libreria_mash'))
				libreria_mash('nusoap');
			else
				include $this->ruta."lib/nusoap/nusoap.php";
		}
		$username = $this->pac['usuario'];
		$password   = $this->pac['pass'];
		$cfdi = $this->satxml_getxml_string();
		// WEBSERVICE TIMBRADO
		$client = new SoapClient($this->urltimbre);
		$params = array(
			"xml" => $cfdi,
			"username" => $username,
			"password" => $password
		);
		$response = $client->__soapCall("stamp", array($params));
		//Verificamos que el llamado se pudo hacer correctamente
		if(count(get_object_vars($response->stampResult->Incidencias))){
			$valor['error']['text'] = $response->stampResult->Incidencias->Incidencia->MensajeIncidencia;
			$valor['error']['code'] = $response->stampResult->Incidencias->Incidencia->CodigoError;
		}else{
			$valor['cadenaoriginal']=$this->cadena_original;
			$valor['cfdi']=$response->stampResult->xml;
			$valor['uuid']=$response->stampResult->UUID;
			$valor['SatSeal']=$response->stampResult->SatSeal;
			$valor['Fecha']=$response->stampResult->Fecha;
			$valor['CodEstatus']=$response->stampResult->CodEstatus;
			$valor['NoCertificadoSAT']=$response->stampResult->NoCertificadoSAT;
			$original = 'xsi:schemaLocation="http://www.sat.gob.mx/cfd/3                           http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd                           http://www.sat.gob.mx/TimbreFiscalDigital                           http://www.sat.gob.mx/sitio_internet/TimbreFiscalDigital/TimbreFiscalDigital.xsd http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/sitio_internet/TimbreFiscalDigital/TimbreFiscalDigital.xsd"';
			$nuevo = 'xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd http://www.sat.gob.mx/TimbreFiscalDigital http://www.sat.gob.mx/sitio_internet/TimbreFiscalDigital/TimbreFiscalDigital.xsd"';
			$valor['cfdi'] = str_replace($original,$nuevo,$valor['cfdi']);
			$dir = $this->cfdi.DS.$valor['uuid'];
			if(!mkdir($dir,0777,true)){
				$this->plantillas->set_message(6000,"Error al crear el directorio: $dir");
				$dir = DS.'xml_debug';
			}
			$valor['archivo_xml'] = $dir.DS.$valor['uuid'].".xml";
			@unlink($this->debug.$this->tmpfile.".xml");
			
			if(strlen($valor['cfdi'])>10){
				@unlink($valor['archivo_xml']);
				if (file_exists($valor['archivo_xml']))
					@chmod($valor['archivo_xml'],0777);
				if (($wh=fopen($valor['archivo_xml'],'wb')) === false)
					die("ERROR ESCRITURA EN  $file_target");
				if (fwrite($wh,$valor['cfdi']) === false) {
					fclose($wh);
					die("ERROR ESCRITURA EN  $file_target");
				}
				fclose($wh);
				@chmod($valor['archivo_xml'],0777);
			}
			$valor['archivo_png'] = str_replace('xml', 'png',$valor['archivo_xml']);
			$valor['archivo_png'] = str_replace('XML', 'png', $valor['archivo_png']);
			include $this->ruta."lib".DS."phpqrcode".DS."qrlib.php";
			$data = "?re={$this->emisor['rfc']}&rr={$this->receptor['rfc']}&tt={$this->factura['total']}&id={$valor['uuid']}";
			$r=QRcode::png($data,$valor['archivo_png'],4,3);
		}
		return $valor;
	}
	//Envio el XML  a cancelar
	public function satxml_cancel($params){
		$client = new SoapClient($this->urlcancel);
		return $client->__soapCall("cancel", array($params));
	}
	/*
	/* valida que el xml coincida con esquema XSD
	function satxml_valida($docu) {
		global $xml;
		$xml->formatOutput=true;
		$paso = new DOMDocument;
		$texto = $xml->saveXML();
		$paso->loadXML($texto);
		libxml_use_internal_errors(false);
		$maquina = trim(`uname -n`);
		$ruta = ($maquina == "www.fjcorona.com.mx") ? "/home/httpd/htdocs/cfds/" : "./cfds/";
		if (strpos($texto,"detallista:")===FALSE) {
			$file=$ruta."cfdv22.xsd";
			$ok = $paso->schemaValidate($file);
		} else {
			$file=$ruta."cfdv22detallista.xsd";
			$ok = $paso->schemaValidate($file);
		}
		return $ok;
	}
	*/
}?>
