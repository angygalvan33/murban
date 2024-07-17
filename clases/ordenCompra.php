<?php
include_once 'conexion.php';
include_once 'comodin.php';

class OrdenCompra {
    public $id;
    private $IdObra;
    private $IdProveedor;
    private $IdUsuario;
    private $IdEstadoOC;
    private $Subtotal;
    private $Iva;
    private $Total;
    private $Pagada;
    private $PagoRequerido;
    private $Anticipo;
    private $IdMetodoPago;
    private $IdTipoOC;
    public $DetalleOC;
    private $Descripcion;
    private $IdUsuarioAutoriza;
    private $NumCotizacion;
    public  $permisosAutorizar;

    public function __construct() {
        $this->id = NULL;
        $this->IdObra = NULL;
        $this->IdProveedor = NULL;
        $this->IdUsuario = NULL;
        $this->IdEstadoOC = NULL;
        $this->Subtotal = NULL;
        $this->Iva = NULL;
        $this->Total = NULL;
        $this->Pagada = NULL;
        $this->PagoRequerido = NULL;
        $this->Anticipo = NULL;
        $this->IdMetodoPago = NULL;
        $this->IdTipoOC = NULL;
        $this->DetalleOC = NULL;
        $this->Descripcion = NULL;
        $this->NotasProveedor = NULL;
        $this->IdUsuarioAutoriza = NULL;
        $this->NumCotizacion = "";
        $this->permisosAutorizar = 0;
    }
    
    public function llenaDatos($id_,
                            $IdProveedor_,
                            $IdUsuario_,
                            $IdEstadoOC_,
                            $Subtotal_,
                            $Iva_,
                            $Total_,
                            $Pagada_,
                            $PagoRequerido_,
                            $Anticipo_,
                            $IdMetodoPago_,
                            $Descripcion_,
                            $NotasProveedor,
                            $IdTipoOC_,
                            $DetalleOC_,
                            $IdUsuarioAutoriza_,
                            $NumCotizacion_) {
        $this->id = $id_;
        $this->IdProveedor = $IdProveedor_;
        $this->IdUsuario = $IdUsuario_;
        $this->IdEstadoOC = $IdEstadoOC_;
        $this->Subtotal = $Subtotal_;
        $this->Iva = $Iva_;
        $this->Total = $Total_;
        $this->Pagada = $Pagada_;
        $this->PagoRequerido = $PagoRequerido_;
        $this->Anticipo = $Anticipo_;
        $this->IdMetodoPago = $IdMetodoPago_;
        $this->Descripcion = $Descripcion_;
        $this->NotasProveedor = $NotasProveedor;
        $this->IdTipoOC = $IdTipoOC_;
        $this->DetalleOC = $DetalleOC_;
        $this->IdUsuarioAutoriza = $IdUsuarioAutoriza_;
        $this->NumCotizacion = $NumCotizacion_;
    }
    
    public function insertaOC() {
		$hoyf = date('Y-m-d');
		$datef = new DateTime($hoyf);
        $aniof = $datef->format('y');
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;

        $conexion->abrirBD();
        $query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
        $result = mysqli_query($conexion->mysqli, $query);
        $result = mysqli_fetch_assoc($result);
        $maximoSinAutorizacion = $result["MaximoSinAutorizacion"];

        if ($this->permisosAutorizar == 1 && $this->Total <= $maximoSinAutorizacion) {
            $this->IdEstadoOC = 2;
            $this->IdUsuarioAutoriza = $comodin->idUsuarioSession();
            $this->IdTipoOC = 4;
        }

        $queryf = "SELECT (Folio + 1) AS newFolio FROM FolioOC";
		$resultf = mysqli_query($conexion->mysqli, $queryf);
        $resultf = mysqli_fetch_assoc($resultf);
        $afolio = $resultf["newFolio"];
		$afolio = $afolio;

		if ($afolio !== NULL AND $afolio !== 0) {
		    $queryf = "UPDATE FolioOC SET Folio = ". $afolio;
            if (!mysqli_query($conexion->mysqli, $queryf)) {
               $band_query_exito = 0;
            }
		}
        else {
			$band_query_exito = 0;
		}

        $conexion->cerrarBD();
		
   		if ($band_query_exito == 1) {
            try {
                $conexion->obtenerNuevoIdTabla('OrdenCompra');
                $nueviId = $conexion->result['result'];
                $conexion->result['IdOrdenCompra'] = -1;

                if ($conexion->abrirBD() != NULL) {
                    $conexion->iniciaTransaccion();
                    $tipoDP = '';
                    $valorFactura = 0;
                    $monto = 0;
                    $estadoPago = '1';

                    if ($this->Pagada == 1) {
                        $estadoPago = '2';
                        $tipoDP = 'Liquidación';
                        $monto = $this->Total;
                        $valorFactura = $this->Total;
                    }
                    else if ($this->Anticipo != 0) {
                        $tipoDP = 'Anticipo';
                        $monto = $this->Anticipo;
                    }

                    $idUsuarioAutoriza = "NULL";
                    $AutorizarCompra = 0;

                    if ($this->IdUsuarioAutoriza != NULL) {
                        $idUsuarioAutoriza = $this->IdUsuarioAutoriza;
                        $AutorizarCompra = 1;
                    }

                    $query = "INSERT INTO OrdenCompra
                    (IdOrdenCompra,
                    IdProveedor,
                    IdUsuario,
                    IdEstadoOC,
                    IdMetodoPago,
                    Subtotal,
                    Iva,
                    Total,
                    Pagada,
                    PagoRequerido,
                    ValorFactura,
                    IdUsuarioAutoriza,
                    Descripcion,
                    NotasProveedor,
                    IdTipoOC,
                    NumCotizacion,
                    AutorizarCompra,
                    FolioOC)
                    VALUES
                    (". $nueviId .",
                    ". $this->IdProveedor .",
                    ". $this->IdUsuario .",
                    ". $this->IdEstadoOC .",
                    ". $this->IdMetodoPago .",
                    ". $this->Subtotal .",
                    ". $this->Iva .",
                    ". $this->Total .",
                    ". $this->Pagada .",
                    ". $this->PagoRequerido .",
                    ". $valorFactura .",
                    ". $idUsuarioAutoriza .",
                    '$this->Descripcion',
                    '$this->NotasProveedor',
                    ". $this->IdTipoOC .",
                    '$this->NumCotizacion',
                    ". $AutorizarCompra .",
                    'oc". $aniof ."-". $afolio ."');";

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -1;
                    }

                    if ($tipoDP == 'Liquidación' || $tipoDP == 'Anticipo') {
                        $query = "INSERT INTO OrdenCompraDetallePagos
                        (IdOrdenCompra,
                        IdMetodoPago,
                        TipoDP,
                        Monto)
                        VALUES
                        (". $nueviId .",
                        ". $this->IdMetodoPago .",
                        '$tipoDP',
                        ". $monto .");";

                        if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -2;
                        }
                    }

                    foreach ($this->DetalleOC AS $value) {
                        //echo $value['Adjunto']."***";
                        if ($value['Adjunto'] == 'null') {
                            $arch = "NULL";
                            $nombreArch = "NULL";
                        }
                        else {
                            //ext tiene la extensión del archivo
                            $ext = strtolower(pathinfo($value['Adjunto']['name'], PATHINFO_EXTENSION));
                            $encoded_image = base64_encode(file_get_contents($value['Adjunto']['tmp_name']));
                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                            $mimect= finfo_file($finfo, $value['Adjunto']['tmp_name']);
                            finfo_close($finfo);
                            $src = 'data:'. $mimect .';base64,'. $encoded_image;
                            //$src es el adjunto en base64 para guardar en bd
                            $arch = "'". $encoded_image ."'";
                            $nombreArch = "'". $value['Adjunto']['name'] ."'";
                        }

                        $query = "INSERT INTO DetalleOrdenCompra
                        (IdOrdenCompra,
                        IdMaterial,
                        IdObra,
                        NombreMaterial,
                        Cantidad,
                        PrecioUnitario,
                        Subtotal,
                        Recibido,
                        FechaRecepcion,
                        Archivo,
                        NombreArchivo,
                        Descripcion,
                        IdUsuarioSolicita)
                        VALUES
                        (". $nueviId .",
                        ". $value['IdMaterial'] .",
                        ". $value['IdObra'] .",
                        '". utf8_encode($value['Material']) ."',
                        ". $value['Cantidad'] .",
                        ". $value['PrecioUnitario'] .",
                        ". $value['Subtotal'] .",
                        0,
                        NULL,
                        ". $arch .",
                        ". $nombreArch .",
                        '',
                        ". $value['IdSolicita'] .");";
                        
                        if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -3;
                        }

                        $query = "INSERT INTO ObraGasto
                        (IdObra,
                        IdOrdenCompra,
                        Tipo,
                        EstadoPago,
                        IdMaterial,
                        NombreMaterial,
                        Cantidad,
                        Total,
                        FechaMovimiento)
                        VALUES
                        (". $value['IdObra'] .",
                        ". $nueviId .",
                        'Orden de Compra',
                        ". $estadoPago .",
                        ". $value['IdMaterial'] .",
                        '". utf8_encode($value['Material']) ."',
                        ". $value['Cantidad'] .",
                        ". $value['Subtotal'] .",
                        NOW());";

                        if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -4;
                        }
                    }
                    
                    if ($band_query_exito == 1) {
                        $conexion->commit();
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = 'REGISTRO INSERTADO... FOLIO = OC23-'. $afolio;
                        $conexion->result['IdOrdenCompra'] = $nueviId;
                    }
                    else {
                        $conexion->rollback();
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = "REGISTRO NO INSERTADO";
                    }

                    $conexion->cerrarBD();
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
                }
            }
            catch (Exception $ex) {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO INSERTADO";
            }
        }
		else {
		    $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO INSERTADO ERROR AL ACTUALIZAR EL FOLIO";
		}
        return $conexion->result;
    }
    
    public function insertaOCRequisicion($IdRequisDetalle) {
        $hoyf = date('Y-m-d');
		$datef = new DateTime($hoyf);
        $aniof = $datef->format('y');
        $conexion = new Conexion();
        $comodin = new Comodin();
        $band_query_exito = 1;
        
        $conexion->abrirBD();
        $query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
        $result = mysqli_query($conexion->mysqli, $query);
        $result = mysqli_fetch_assoc($result);
        $maximoSinAutorizacion = $result["MaximoSinAutorizacion"];
        $conexion->cerrarBD();

        if($this->permisosAutorizar == 1 && $this->Total <= $maximoSinAutorizacion) {
            $this->IdEstadoOC = 2;
            $this->IdUsuarioAutoriza =  $comodin->idUsuarioSession();
            $this->IdTipoOC = 4;
        }
		
        $conexion->abrirBD();
		$queryf = "SELECT (Folio + 1) AS newFolio FROM FolioOC";
		$resultf = mysqli_query($conexion->mysqli, $queryf);
        $resultf = mysqli_fetch_assoc($resultf);
        $afolio = $resultf["newFolio"];
		$afolio = $afolio;

		if($afolio !== null and $afolio !== 0) {
		    $queryf = "UPDATE FolioOC SET Folio = ". $afolio;
            if(!mysqli_query($conexion->mysqli, $queryf)) {
               $band_query_exito = 0;
            }
		}
        else {
			$band_query_exito = 0;
		}

		$conexion->cerrarBD();
		if($band_query_exito == 1) {
            try {
                $conexion->obtenerNuevoIdTabla('OrdenCompra');
                $nueviId = $conexion->result['result'];
                $conexion->result['IdOrdenCompra'] = -1;

                if($conexion->abrirBD() != NULL) {
                    $conexion->iniciaTransaccion();
                    $tipoDP = '';
                    $valorFactura = 0;
                    $monto = 0;
                    $estadoPago = '1';

                    if($this->Pagada == 1) {
                        $estadoPago = '2';
                        $tipoDP = 'Liquidación';
                        $monto = $this->Total;
                        $valorFactura = $this->Total;
                    }
                    else if($this->Anticipo != 0) {
                        $tipoDP = 'Anticipo';
                        $monto = $this->Anticipo;
                    }

                    $idUsuarioAutoriza = "NULL";
                    $AutorizarCompra = 0;
                    if($this->IdUsuarioAutoriza != NULL) {
                        $idUsuarioAutoriza = $this->IdUsuarioAutoriza;
                        $AutorizarCompra = 1;
                    }
                    //echo "****".$this->IdProveedor."****";
                    $query = "INSERT INTO OrdenCompra
                    (IdOrdenCompra,
                    IdProveedor,
                    IdUsuario,
                    IdEstadoOC,
                    IdMetodoPago,
                    Subtotal,
                    Iva,
                    Total,
                    Pagada,
                    PagoRequerido,
                    ValorFactura,
                    IdUsuarioAutoriza,
                    Descripcion,
                    NotasProveedor,
                    IdTipoOC,
                    NumCotizacion,
                    AutorizarCompra,
                    OrigenRequisicion,
                    FolioOC)
                    VALUES
                    (". $nueviId .",
                    ". $this->IdProveedor .",
                    ". $this->IdUsuario .",
                    ". $this->IdEstadoOC .",
                    ". $this->IdMetodoPago .",
                    ". $this->Subtotal .",
                    ". $this->Iva .",
                    ". $this->Total .",
                    ". $this->Pagada .",
                    ". $this->PagoRequerido .",
                    ". $valorFactura .",
                    ". $idUsuarioAutoriza .",
                    '$this->Descripcion',
                    '$this->NotasProveedor',
                    ". $this->IdTipoOC .",
                    '$this->NumCotizacion',
                    ". $AutorizarCompra .",
                    1,
                    'oc". $aniof ."-". $afolio ."');";

                    if(!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = -1;
                    }

                    if($tipoDP == 'Liquidación' || $tipoDP == 'Anticipo') {
                        $query = "INSERT INTO OrdenCompraDetallePagos
                        (IdOrdenCompra,
                        IdMetodoPago,
                        TipoDP,
                        Monto)
                        VALUES
                        (". $nueviId .",
                        ". $this->IdMetodoPago .",
                        '$tipoDP',
                        ". $monto .");";

                        if(!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -2;
                        }
                    }

                    foreach($this->DetalleOC as $value) {
                        if($value['Adjunto'] == 'null') {
                            $arch = "NULL";
                            $nombreArch = "NULL";
                        }
                        else {
                            //ext tiene la extensión del archivo
                            $ext = strtolower(pathinfo($value['Adjunto']['name'], PATHINFO_EXTENSION));
                            $encoded_image = base64_encode(file_get_contents($value['Adjunto']['tmp_name']));
                            $finfo = finfo_open(FILEINFO_MIME_TYPE);
                            $mimect= finfo_file($finfo, $value['Adjunto']['tmp_name']);
                            finfo_close($finfo);
                            $src = 'data:'. $mimect .';base64,'. $encoded_image;
                            $arch = "'". $encoded_image ."'";
                            $nombreArch = "'". $value['Adjunto']['name'] ."'";
                        }

                        $query = "INSERT INTO DetalleOrdenCompra
                        (IdOrdenCompra,
                        IdMaterial,
                        IdObra,
                        NombreMaterial,
                        Cantidad,
                        PrecioUnitario,
                        Subtotal,
                        Recibido,
                        FechaRecepcion,
                        Archivo,
                        NombreArchivo,
                        Descripcion,
                        IdUsuarioSolicita,
                        IdRequisicionDetalle)
                        VALUES
                        (". $nueviId .",
                        ". $value['IdMaterial'] .",
                        ". $value['IdObra'] .",
                        '". utf8_encode($value['Material']) ."',
                        ". $value['Cantidad'] .",
                        ". $value['PrecioUnitario'] .",
                        ". $value['Subtotal'] .",
                        0,
                        NULL,
                        ". $arch .",
                        ". $nombreArch .",
                        '.',
                        ". $value['IdSolicita'] .",
                        ". $value['IdReqDetalle'] .");";
                        if(!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -3;
                        }

                        $query = "INSERT INTO ObraGasto
                        (IdObra,
                        IdOrdenCompra,
                        Tipo,
                        EstadoPago,
                        IdMaterial,
                        NombreMaterial,
                        Cantidad,
                        Total,
                        FechaMovimiento)
                        VALUES
                        (". $value['IdObra'] .",
                        ". $nueviId .",
                        'Orden de Compra',
                        ". $estadoPago .",
                        ". $value['IdMaterial'] .",
                        '". utf8_encode($value['Material']) ."',
                        ". $value['Cantidad'] .",
                        ". $value['Subtotal'] .",
                        NOW());";

                        if(!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -4;
                        }

                        $query = "UPDATE RequisicionAtendida SET IdOrdenCompra = ". $nueviId .", SurtidoDesde = 'OC' WHERE IdRequisicionAtendida = ". $value['IdReqAtendida'];

                        if(!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -5;
                        }

                        $query = "UPDATE RequisicionDetalle SET IdOC = ". $nueviId .", EdoParcialmenteAtendida = 1, CantidadPedida = CantidadPedida + ". $value['Cantidad'] ." WHERE IdRequisicionDetalle = ". $value['IdReqDetalle'];

                        if(!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -6;
                        }

                        $query = "SELECT * FROM RequisicionDetalle WHERE IdRequisicionDetalle = ". $value['IdReqDetalle'];

                        $result = mysqli_query($conexion->mysqli, $query);
                        $row = mysqli_fetch_assoc($result);
                        $CantidadSolicitada = $row['CantidadSolicitada'];
                        $CantidadPedida = $row['CantidadPedida'];
                        $requisicion = $row['IdRequisicion'];

                        if($CantidadPedida >= $CantidadSolicitada) {
                            $query = "UPDATE RequisicionDetalle SET IdOC = ". $nueviId .", EdoAtendida = 1 WHERE IdRequisicionDetalle = ". $value['IdReqDetalle'];

                            if(!mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = -7;
                            }
                        }
                    }

                    $queryUpdate = "UPDATE Requisicion SET EdoAtendida = 1 WHERE IdRequisicion = ". $requisicion;
                    //echo "****".$queryUpdate."****";
                    if(!mysqli_query($conexion->mysqli, $queryUpdate)) {
                        $band_query_exito = -8;
                    }
                    //echo "****".$band_query_exito."****";
                    if($band_query_exito == 1) {
                        $conexion->commit();
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = 'REGISTRO INSERTADO... FOLIO = OC23-'. $afolio;
                        $conexion->result['IdOrdenCompra'] = $nueviId;
                    }
                    else {
                        $conexion->rollback();
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = "REGISTRO NO INSERTADO";
                    }
                    
                    $conexion->cerrarBD();
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
                }
            }
            catch (Exception $ex) {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO INSERTADO";
            }
        }
	    else {
		    $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO INSERTADO ERROR AL ACTUALIZAR EL FOLIO";
	    }
        return $conexion->result;
    }
    
    public function insertaCxPEspecial($datos) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        try {
            $conexion->obtenerNuevoIdTabla('OrdenCompra');
            $nueviId = $conexion->result['result'];
            $conexion->result['IdOrdenCompra'] = -1;
            
            if( $conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "INSERT INTO OrdenCompra
                (IdOrdenCompra,
                IdProveedor,
                IdUsuario,
                IdEstadoOC,
                IdMetodoPago,
                Pagada,
                NumeroFactura,
                ValorFactura,
                FechaFactura,
                Descripcion,
                NotasProveedor,
                IdUsuarioAutoriza,
                IdTipoOC,
                PagoRequerido,
                AutorizarCompra,
                FolioOC,
                Subtotal,
                Iva,
                Total)
                VALUES
                (". $nueviId .",
                ". $datos['IdProveedor'] .",
                ". $datos['IdUsuario'] .",
                4,
                ". $datos['IdMetodoPago'] .",
                0,
                '". $datos['NumeroFactura'] ."',
                ". $datos['ValorFactura'] .",
                '". $datos['FechaFactura'] ."',
                '". $datos['Descripcion'] ."',
                '". $datos['NotasProveedor'] ."',
                ". $datos['IdUsuario'] .",
                3, 0, 1,
                '". $nueviId ."-CPPE',
                ". $datos['Subtotal'] .",
                ". $datos['IVA'] .",
                ". $datos['Total'] .");";
                
                if(!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                foreach($datos['DetalleOC'] as $value) {
                    if($value['Adjunto'] == 'null') {
                        $arch = "NULL";
                        $nombreArch = "NULL";
                    }
                    else {
                        //ext tiene la extensión del archivo
                        $ext = strtolower(pathinfo($value['Adjunto']['name'], PATHINFO_EXTENSION));
                        $encoded_image = base64_encode(file_get_contents($value['Adjunto']['tmp_name']));
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mimect = finfo_file($finfo, $value['Adjunto']['tmp_name']);
                        finfo_close($finfo);
                        $src = 'data:'. $mimect .';base64,'. $encoded_image;
                        $arch = "'". $encoded_image ."'";
                        $nombreArch = "'". $value['Adjunto']['name'] ."'";
                    }
                    
                    $query = "INSERT INTO DetalleOrdenCompra
                    (IdOrdenCompra,
                    IdMaterial,
                    IdObra,
                    NombreMaterial,
                    Cantidad,
                    PrecioUnitario,
                    Subtotal,
                    Recibido,
                    FechaRecepcion,
                    Archivo,
                    NombreArchivo,
                    IdUsuarioSolicita)
                    VALUES
                    (". $nueviId .",
                    ". $value['IdMaterial'] .",
                    ". $value['IdObra'] .",
                    '". utf8_encode($value['Material']) ."',
                    ". $value['Cantidad'] .",
                    ". $value['PrecioUnitario'] .",
                    ". $value['Subtotal'] .",
                    1,
                    NULL,
                    ". $arch .",
                    ". $nombreArch .",
                    ". $value['IdSolicita'] .");";

                    if(!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }
                    
                    $query = "INSERT INTO ObraGasto
                    (IdObra,
                    IdOrdenCompra,
                    Tipo,
                    EstadoPago,
                    IdMaterial,
                    NombreMaterial,
                    Cantidad,
                    Total,
                    FechaMovimiento)
                    VALUES
                    (". $value['IdObra'] .",
                    ". $nueviId .",
                    'Orden de Compra',
                    1,
                    ". $value['IdMaterial'] .",
                    '". $value['Material'] ."',
                    ". $value['Cantidad'] .",
                    ". $value['Subtotal'] .",
                    NOW());";

                    if(!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }
                }
                
                if($band_query_exito == 1) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO INSERTADO...';
                    $conexion->result['IdOrdenCompra'] = $nueviId;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "REGISTRO NO INSERTADO";
                }
                
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO INSERTADO";
        }
        echo json_encode($conexion->result);
    }
    
    public function setIdUsuarioAutoriza($idOC_, $idUsuarioAutoriza_, $tipo_) {
        $conexion = new Conexion();
        $conexion->abrirBD();

        $query = "UPDATE OrdenCompra SET IdUsuarioAutoriza = ". $idUsuarioAutoriza_ ." WHERE IdOrdenCompra = ". $idOC_;

        mysqli_query($conexion->mysqli, $query);

        if($tipo_ == 1) {
            $query = "UPDATE OrdenCompra SET Proponer = 1, Autorizar = 1, NumeroFactura = 'PENDIENTE', ValorFactura = Total, FechaFactura = now() WHERE IdOrdenCompra = ". $idOC_;

            mysqli_query($conexion->mysqli, $query);
        }
        $conexion->cerrarBD();
    }
    
    public function cambiarEstadoOC($idOC_, $idEstadoOC_) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        try {
            if($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "UPDATE OrdenCompra SET IdEstadoOC = ". $idEstadoOC_ .", AutorizarCompra = 1 WHERE IdOrdenCompra = ". $idOC_;
                
                if(!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if($idEstadoOC_ == 5) {
                    $query = "UPDATE RequisicionDetalle SET EdoPendienteAtender = 1, EdoAtendida = 0, EdoRecibida = 0, EdoCancelada = 0, IdOC = NULL WHERE IdOC = ". $idOC_;
                    
                    if(!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }
                }
                
                if($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = "Accion realizada";
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
        echo json_encode($conexion->result);
    }
    
    public function recibirCompraCompleta($idOC_) {
        $hoyf = date('Y-m-d H:i:s');
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        try {
            if($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "UPDATE OrdenCompra SET IdEstadoOC = 3, Recibida = 1 WHERE IdOrdenCompra = ". $idOC_;
                
                if(!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                $query = "UPDATE DetalleOrdenCompra SET Recibido = Cantidad, FechaRecepcion = '". $hoyf ."' WHERE IdOrdenCompra = ". $idOC_;
                
                if(!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }

                if($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = "Se ha recibido la Orden de Compra.";
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
        echo json_encode($conexion->result);
    }
    
    public function insertaFechaFacturaOC($idOC_, $fecha_) {
        $conexion = new Conexion();

        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE OrdenCompra SET FechaFactura = '". $fecha_ ."' WHERE IdOrdenCompra = ". $idOC_;
                
                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
    }
    
    public function setNumeroFactura($idOC_, $noFactura_, $valorFact_) {
        $conexion = new Conexion();
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE OrdenCompra SET NumeroFactura = '$noFactura_', ValorFactura = ". $valorFact_ ." WHERE IdOrdenCompra = ". $idOC_;
                
                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
    }
    
    public function cambiaRecibido($idOC_, $idDetalleOC_, $edo_) {
        $conexion = new Conexion();
        
        try {
            if($conexion->abrirBD() != NULL) {
                $valor = 'now()';
                
                if($edo_ == "false") {
                    $valor = 'NULL';
                }
                
                $query = "UPDATE DetalleOrdenCompra SET Recibido = " . $edo_ . ", FechaRecepcion = ". $valor." WHERE IdOrdenCompra = ". $idOC_ ." AND IdDetalleOrdenCompra = ". $idDetalleOC_;
                
                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    if($edo_ == 1)
                        $val = 'now()';
                    else
                        $val = 'NULL';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
        
        echo json_encode($conexion->result);
    }
    
    public function comprobarCompleta($idOC_) {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            $query = "SELECT COUNT(*) AS numeroRegistros FROM DetalleOrdenCompra WHERE IdOrdenCompra = ". $idOC_ ." AND Eliminado IS NULL";

            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $numRegistros = $result['numeroRegistros'];
            
            $query = "SELECT COUNT(*) AS numeroRecibidos FROM DetalleOrdenCompra WHERE IdOrdenCompra = ". $idOC_ ." AND Recibido = 1 AND Eliminado IS NULL";

            $result = mysqli_query($conexion->mysqli, $query);
            $result = mysqli_fetch_assoc($result);
            $numRecibidos = $result['numeroRecibidos'];
            
            $conexion->cerrarBD();
            
            if($numRegistros == $numRecibidos) {
                $conexion->result['error'] = 0;
                $conexion->result['result'] = 0;
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = 1;
            }
        }
        else {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "Error conexion bd";
        }
        
        echo json_encode($conexion->result);
    }
    
    public function PagarOrdenCompra($idOC_, $IdMetodoPago_, $tipoPago_, $cantidad_, $cantidadFact_, $concepto_, $deuda_, $fecha_) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        $estadoOrdenCompra = NULL;
        $comodin = new Comodin();

        try {
            $estadoOrdenCompra = $this->EstadoProponerAutorizarOC($idOC_);

            if($estadoOrdenCompra['error'] == 1) {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
                echo json_encode($conexion->result);
                exit();
            }

            if($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                if($cantidad_ == $deuda_) {
                    $tipoPago_ = 'Liquidación';
                }

                $query = "INSERT INTO OrdenCompraDetallePagos
                (IdOrdenCompra,
                IdMetodoPago,
                TipoDP,
                Monto,
                FechaPago,
                IdUsuarioPago,
                Concepto)
                VALUES
                (". $idOC_ .",
                ". $IdMetodoPago_ .",
                '$tipoPago_',
                ". $cantidad_ .",
                STR_TO_DATE('$fecha_', '%d/%m/%Y'),
                ". $comodin->idUsuarioSession() .",
                '$concepto_');";

                if(!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                $query = "UPDATE OrdenCompra SET Proponer = 0, Autorizar = 0, Discrepancia = ". $cantidadFact_ ." WHERE IdOrdenCompra = ". $idOC_ .";";

                if(!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                
                if($estadoOrdenCompra['error'] == 0) {
                    if($estadoOrdenCompra['result']['Proponer'] == '1') {
                        $query = "UPDATE Proveedor SET TotalPropuesto = TotalPropuesto - ". $deuda_ ." WHERE IdProveedor = (SELECT IdProveedor FROM OrdenCompra WHERE IdOrdenCompra = ". $idOC_ .")";

                        if(!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = 0;
                        }
                    }

                    if($estadoOrdenCompra['result']['Autorizar'] == '1') {
                        $query = "UPDATE Proveedor SET TotalAutorizado = TotalAutorizado - ". $deuda_ ." WHERE IdProveedor = (SELECT IdProveedor FROM OrdenCompra WHERE IdOrdenCompra = ". $idOC_ .")";

                        if(!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = 0;
                        }
                    }
                }
                if($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = "PAGO REGISTRADO";
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
        
        echo json_encode($conexion->result);
    }
    
    public function EstadoProponerAutorizarOC($idOC) {
        $conexion = new Conexion();
        $lista = array();
        
        if($conexion->abrirBD() != NULL) {
            $query = "SELECT Proponer, Autorizar FROM OrdenCompra WHERE IdOrdenCompra = ". $idOC .";";

            $result = mysqli_query($conexion->mysqli, $query);
            $fila = $result->fetch_assoc();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $fila;
            $conexion->cerrarBD();
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result;
    }
    
    public function cambiarEstadoPropuesta($idOC_, $estado_) {
        $conexion = new Conexion();
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE OrdenCompra SET Proponer = ". $estado_ ." WHERE IdOrdenCompra = ". $idOC_;
                
                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
        
        return $conexion->result;
    }
    
    public function cambiarEstadoAutorizada($idOC_, $estado_) {
        $conexion = new Conexion();
        $comodin = new Comodin();
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE OrdenCompra SET Autorizar = ". $estado_ .", IdUsuarioAutoriza = ". $comodin->idUsuarioSession() .", AutorizaDate = now() WHERE IdOrdenCompra = ". $idOC_;
                
                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
        
        return $conexion->result;
    }
    
    public function listarPagosOC($idOC) {
        $conexion = new Conexion();
        $lista = array();
        
        if($conexion->abrirBD() != NULL) {
            $query = "CALL OrdenCompraDetallePagos(". $idOC .");";

            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc()) {
                $lista[] = $fila;
            }
            $conexion->cerrarBD();
            
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    public function getDeudaTotal() {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            $query = "SELECT SUM(Deuda) AS Deuda FROM VistaCuentasPorPagar";
            
            $result = mysqli_query($conexion->mysqli, $query);
            $fila = $result->fetch_assoc();
            $conexion->cerrarBD();
            
            $conexion->result['result'] = $fila['Deuda'];
        }
        else {
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    public function getDeudaPropuesta() {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            $query = "SELECT SUM(Deuda) AS Deuda FROM VistaCuentasPorPagar WHERE Proponer = 1";
            
            $result = mysqli_query($conexion->mysqli, $query);
            $fila = $result->fetch_assoc();
            $conexion->cerrarBD();
            $conexion->result['result'] = $fila['Deuda'];
        }
        else {
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    public function getDeudaAutorizada() {
        $conexion = new Conexion();
        
        if($conexion->abrirBD() != NULL) {
            $query = "SELECT SUM(Deuda) AS Deuda FROM VistaCuentasPorPagar WHERE Autorizar = 1";
            
            $result = mysqli_query($conexion->mysqli, $query);
            $fila = $result->fetch_assoc();
            $conexion->cerrarBD();
            
            $conexion->result['result'] = $fila['Deuda'];
        }
        else {
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    public function cancelaCXP($idOC_, $motivo_) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        try {
            if( $conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "SELECT COUNT(*) AS NumRegistrosOC FROM Inventario WHERE IdOrdenCompra = ". $idOC_;

                $result = mysqli_query($conexion->mysqli, $query);
                $result = mysqli_fetch_assoc($result);
                $NumRegistrosOC = $result['NumRegistrosOC'];
                
                if($NumRegistrosOC == 0) {
                    $query = "UPDATE OrdenCompra SET IdEstadoOC = 5, MotivoCancelacion = '$motivo_' WHERE IdOrdenCompra = ". $idOC_;

                    if(!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }
                    else {
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = "CUENTA POR PAGAR CANCELADA";
                    }
                }
                else if($NumRegistrosOC != 0) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = "CUENTA POR PAGAR NO CANCELADA, YA SE HA RECIBIDO MATERIAL";
                }
                
                if($band_query_exito) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
        
        echo json_encode($conexion->result);
    }

    public function getSaldoProveedor($fechaIni, $fechaFin, $idProveedor, $idEstado) {
        $conexion = new Conexion();
        $lista = array();
        $saldo = 0;
        $total = 0;
        $pagos = 0;

        if($conexion->abrirBD() != NULL) {
            $query = "SELECT Total AS Total FROM VistaHistoricoPagosOc WHERE 1 = 1";

            if($fechaIni != -1)
                $query = $query ." AND Creado BETWEEN '". $fechaIni ."' AND '". $fechaFin ."'";

            if($idProveedor != 0)
                $query = $query ." AND IdProveedor = ". $idProveedor;

            if($idEstado == 1)
                $query = $query ." AND IdEstadoOC != 5";

            else if($idEstado == 5)
                $query = $query ." AND IdEstadoOC = ". $idEstado;

            $query = $query. " GROUP BY IdOrdenCompra";

            $result = mysqli_query($conexion->mysqli, $query);

            while($fila = $result->fetch_assoc()) {
                $total += $fila['Total'];
            }

            $query = "SELECT SUM(Pago) AS Pagos FROM VistaHistoricoPagosOc WHERE 1 = 1";

            if($fechaIni != -1)
                $query = $query ." AND Creado BETWEEN '". $fechaIni ."' AND '". $fechaFin ."'";

            if($idProveedor != 0)
                $query = $query ." AND IdProveedor = ". $idProveedor;

            if($idEstado == 1)
                $query = $query ." AND IdEstadoOC != 5";

            else if($idEstado == 5)
                $query = $query ." AND IdEstadoOC = ". $idEstado;

            $result = mysqli_query($conexion->mysqli, $query);

            while($fila = $result->fetch_assoc()) {
                $pagos += $fila['Pagos'];
            }

            $saldo = round(($total - $pagos), 3);
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $saldo;
            $conexion->cerrarBD();
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result['result'];
    }

    function actualizarPrecios($idOC_) {
        $conexion = new Conexion();
        $band_query_exito = 1;

        $conexion->abrirBD();
        try {
            
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $querySelect = "SELECT IdProveedor FROM OrdenCompra WHERE IdOrdenCompra = ". $idOC_ ." AND Eliminado IS NULL";
                
                $result = mysqli_query($conexion->mysqli, $querySelect);
                $result = mysqli_fetch_assoc($result);
                $idProveedor = $result['IdProveedor'];

                if (!mysqli_query($conexion->mysqli, $querySelect)) {
                    $band_query_exito = 0;
                }

                $queryDetOC = "SELECT * FROM DetalleOrdenCompra WHERE IdOrdenCompra = ". $idOC_ ." AND Eliminado IS NULL";
                
                if (!mysqli_query($conexion->mysqli, $queryDetOC)) {
                    $band_query_exito = -1;
                }

                $result = mysqli_query($conexion->mysqli, $queryDetOC);
                while($row = mysqli_fetch_assoc($result)) {
                    $queryPrecio = "SELECT Precio FROM ProveedorByMaterial WHERE IdMaterial = ". $row['IdMaterial'] ." AND IdProveedor = ". $idProveedor;
                    //echo $queryPrecio."***";
                    $resultPrecio = mysqli_query($conexion->mysqli, $queryPrecio);
                    $resultPrecio = mysqli_fetch_assoc($resultPrecio);
                    $precio_actual = $resultPrecio['Precio'];

                    if (!mysqli_query($conexion->mysqli, $queryPrecio)) {
                        $band_query_exito = -2;
                    }

                    $queryUpdate = "UPDATE DetalleOrdenCompra SET PrecioUnitario = ". $precio_actual .", SubTotal = (Cantidad * ". $precio_actual .") WHERE IdDetalleOrdenCompra = ". $row['IdDetalleOrdenCompra'];

                    if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                        $band_query_exito = -3;
                    }
                }

                if ($band_query_exito == 1) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'SE HAN ACTUALIZADO TODOS LOS PRECIOS';
                }
                else {
                    $conexion->rollback();
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "PRECIOS NO ACTUALIZADOS";
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "PRECIOS NO ACTUALIZADOS, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "PRECIOS NO ACTUALIZADOS";
        }
        return $conexion->result;
    }

    function actualizarTotal($idOC_) {
        $conexion = new Conexion();
        $band_query_exito = 1;

        $conexion->abrirBD();
        try {
            
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();

                $queryDetOC = "SELECT SUM(SubTotal) AS SubTotal FROM DetalleOrdenCompra WHERE IdOrdenCompra = ". $idOC_ ." AND Eliminado IS NULL";
                
                $result = mysqli_query($conexion->mysqli, $queryDetOC);
                $result = mysqli_fetch_assoc($result);
                $subtotal = $result['SubTotal'];

                if (!mysqli_query($conexion->mysqli, $queryDetOC)) {
                    $band_query_exito = 0;
                }

                $iva = $subtotal * 0.16;
                $total = $subtotal * 1.16;

                $queryUpdate = "UPDATE OrdenCompra SET SubTotal = ". $subtotal .", Iva = ". $iva .", Total = ". $total ." WHERE IdOrdenCompra = ". $idOC_;

                if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                    $band_query_exito = -1;
                }

                if ($band_query_exito == 1) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'SE HAN ACTUALIZADO TODOS LOS PRECIOS';
                }
                else {
                    $conexion->rollback();
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "PRECIOS NO ACTUALIZADOS";
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "PRECIOS NO ACTUALIZADOS, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "PRECIOS NO ACTUALIZADOS";
        }
        return json_encode($conexion->result);
    }

    function actualizaPrecioDetalle($idDetalleOC_, $idOC_) {
        $conexion = new Conexion();
        $band_query_exito = 1;

        $conexion->abrirBD();
        try {
            
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $querySelect = "SELECT IdProveedor FROM OrdenCompra WHERE IdOrdenCompra = ". $idOC_ ." AND Eliminado IS NULL";
                //echo $querySelect."***";
                $result = mysqli_query($conexion->mysqli, $querySelect);
                $result = mysqli_fetch_assoc($result);
                $idProveedor = $result['IdProveedor'];

                if (!mysqli_query($conexion->mysqli, $querySelect)) {
                    $band_query_exito = 0;
                }

                $queryDetOC = "SELECT * FROM DetalleOrdenCompra WHERE IdDetalleOrdenCompra = ". $idDetalleOC_ ." AND Eliminado IS NULL";
                //echo $queryDetOC."***";
                $result = mysqli_query($conexion->mysqli, $queryDetOC);
                $result = mysqli_fetch_assoc($result);

                if (!mysqli_query($conexion->mysqli, $queryDetOC)) {
                    $band_query_exito = -1;
                }

                //while($row = mysqli_fetch_assoc($result)) {
                $queryPrecio = "SELECT Precio FROM ProveedorByMaterial WHERE IdMaterial = ". $result['IdMaterial'] ." AND IdProveedor = ". $idProveedor;
                    //echo $queryPrecio."***";
                $result = mysqli_query($conexion->mysqli, $queryPrecio);
                $resultPrecio = mysqli_fetch_assoc($result);
                $precio_actual = $resultPrecio['Precio'];

                if (!mysqli_query($conexion->mysqli, $queryPrecio)) {
                    $band_query_exito = -2;
                }

                $queryUpdate = "UPDATE DetalleOrdenCompra SET PrecioUnitario = ". $precio_actual .", SubTotal = (Cantidad * ". $precio_actual .") WHERE IdDetalleOrdenCompra = ". $idDetalleOC_;

                if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                    $band_query_exito = -3;
                }
                //}

                if ($band_query_exito == 1) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'SE HAN ACTUALIZADO TODOS LOS PRECIOS';
                }
                else {
                    $conexion->rollback();
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "PRECIOS NO ACTUALIZADOS";
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "PRECIOS NO ACTUALIZADOS, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "PRECIOS NO ACTUALIZADOS";
        }
        return $conexion->result;
    }

    function actualizaProveedor($idOC_, $idProvNvo_) {
        $conexion = new Conexion();
        
        try {
            if($conexion->abrirBD() != NULL) {
                $query = "UPDATE OrdenCompra SET IdProveedor = ". $idProvNvo_ ." WHERE IdOrdenCompra = ". $idOC_;
                
                if(mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'SE HA ACTUALIZADO EL PROVEEDOR';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = $conexion->mysqli->error;
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
        
        return json_encode($conexion->result);
    }

    function eliminarPartidaOC($idDetalleOC_, $idOC_) {
        $conexion = new Conexion();
        $band_query_exito = 1;

        $conexion->abrirBD();
        try {
            
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();

                $queryUpdate = "UPDATE DetalleOrdenCompra SET Eliminado = NOW() WHERE IdDetalleOrdenCompra = ". $idDetalleOC_;

                if (!mysqli_query($conexion->mysqli, $queryUpdate)) {
                    $band_query_exito = 0;
                }

                if ($band_query_exito == 1) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'SE HAN ACTUALIZADO LA OC';
                }
                else {
                    $conexion->rollback();
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "OC NO ACTUALIZADA";
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "OC NO ACTUALIZADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "OC NO ACTUALIZADA";
        }
        return $conexion->result;
    }
}