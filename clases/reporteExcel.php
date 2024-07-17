<?php
include_once 'conexion.php';

class ReporteExcel {
    //put your code here
    public function ReporteMetodoPago($idsMetodoPago, $fechaIni, $fechaFin)
    {
        $conexion = new Conexion();
        $lista = array();
        
        if( $conexion->abrirBD() != NULL)
        {
            $query = "select date(OrdenCompraDetallePagos.Creado) as Fecha
            ,OrdenCompraDetallePagos.IdMetodoPago as IdMetodo
            ,MetodoPago.Nombre as MetodoNombre
            ,'-' as CentroCostos
            ,Proveedor.IdProveedor as IdProveedorProyecto
            ,Proveedor.Nombre as NombreProveedorProyecto
            -- ,'Pendiente' as Concepto
            ,OrdenCompraDetallePagos.Concepto
            ,convert(OrdenCompra.NumeroFactura,char) as FolioFactura
            ,'-' as Ingreso
            ,OrdenCompra.ValorFactura as Egreso
            From OrdenCompraDetallePagos
            inner join MetodoPago on OrdenCompraDetallePagos.IdMetodoPago = MetodoPago.IdMetodoPago
            inner join OrdenCompra on OrdenCompra.IdOrdenCompra = OrdenCompraDetallePagos.IdOrdenCompra
            inner join Proveedor on Proveedor.IdProveedor = OrdenCompra.IdProveedor
            Where OrdenCompraDetallePagos.IdMetodoPago in ( $idsMetodoPago ) and
            (date(OrdenCompraDetallePagos.Creado) Between '$fechaIni' and '$fechaFin' )
            Order by Fecha;";

            $result = mysqli_query($conexion->mysqli, $query);
            //$fila = $result->fetch_assoc();
            while($fila = $result->fetch_assoc())
            {
                $lista[] = $fila;
            }
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            
            $conexion->cerrarBD();
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result['result'];
    }
    
    public function ReporteMetodoCobro($idsMetodoCobro, $fechaIni, $fechaFin)
    {
        $conexion = new Conexion();
        $lista = array();
        
        if( $conexion->abrirBD() != NULL)
        {
            $query = "select date(ObraDetalleCobros.Creado) as Fecha
            ,ObraDetalleCobros.IdMetodoCobro as IdMetodo
            ,MetodoCobro.Nombre as MetodoNombre
            ,'-' as CentroCostos
            ,ObraDetalleCobros.IdObra as IdProveedorProyecto
            ,Obra.Nombre as NombreProveedorProyecto
            -- ,'Pendiente' as Concepto
            ,ObraDetalleCobros.Concepto
            ,convert(Obra.FacturaNumero,char) as FolioFactura
            ,ObraDetalleCobros.Monto as Ingreso
            ,'-' as Egreso
            From ObraDetalleCobros
            inner join MetodoCobro on ObraDetalleCobros.IdMetodoCobro = MetodoCobro.IdMetodoCobro
            inner join Obra on Obra.IdObra = ObraDetalleCobros.IdObra
            Where ObraDetalleCobros.IdMetodoCobro in ( $idsMetodoCobro ) and
            (date(ObraDetalleCobros.Creado) Between '$fechaIni' and '$fechaFin' )
            Order by Fecha;";

            $result = mysqli_query($conexion->mysqli, $query);
            
            while($fila = $result->fetch_assoc())
            {
                $lista[] = $fila;
            }
            
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            
            $conexion->cerrarBD();
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result['result'];
    }
    
    //Gnera el reporte combinado de metodo de pago y cobro
    public function ReporteMetodoPagoCobro($idsMetodoPago, $idsMetodoCobro, $fechaIni, $fechaFin)
    {
        $conexion = new Conexion();
        $lista = array();
        
        if( $conexion->abrirBD() != NULL)
        {
            $query = "select Fecha
            ,T.IdMetodo
            ,T.MetodoNombre
            ,T.CentroCostos
            ,T.IdProveedorProyecto
            ,T.NombreProveedorProyecto
            ,T.Concepto
            ,T.FolioFactura
            ,T.Ingreso
            ,T.Egreso
            From
            (
            (
            select date(OrdenCompraDetallePagos.Creado) as Fecha ,
            OrdenCompraDetallePagos.IdMetodoPago as IdMetodo ,
            MetodoPago.Nombre as MetodoNombre ,
            '-' as CentroCostos ,Proveedor.IdProveedor as IdProveedorProyecto ,
            Proveedor.Nombre as NombreProveedorProyecto 
            -- 'Pendiente' as Concepto 
            ,OrdenCompraDetallePagos.Concepto 
            ,convert(OrdenCompra.NumeroFactura,char) as FolioFactura 
            ,'-' as Ingreso 
            ,OrdenCompra.ValorFactura as Egreso 
            From OrdenCompraDetallePagos 
            inner join MetodoPago on OrdenCompraDetallePagos.IdMetodoPago = MetodoPago.IdMetodoPago 
            inner join OrdenCompra on OrdenCompra.IdOrdenCompra = OrdenCompraDetallePagos.IdOrdenCompra 
            inner join Proveedor on Proveedor.IdProveedor = OrdenCompra.IdProveedor 
            Where OrdenCompraDetallePagos.IdMetodoPago in ( $idsMetodoPago ) 
            and (date(OrdenCompraDetallePagos.Creado) Between '$fechaIni' and '$fechaFin' ) 
            )
            union all
            (
            select date(ObraDetalleCobros.Creado) as Fecha
            ,ObraDetalleCobros.IdMetodoCobro as IdMetodo
            ,MetodoCobro.Nombre as MetodoNombre
            ,'-' as CentroCostos
            ,ObraDetalleCobros.IdObra as IdProveedorProyecto
            ,Obra.Nombre as NombreProveedorProyecto
            -- ,'Pendiente' as Concepto
            ,ObraDetalleCobros.Concepto 
            ,convert(Obra.FacturaNumero,char) as FolioFactura
            ,ObraDetalleCobros.Monto as Ingreso
            ,'-' as Egreso
            From ObraDetalleCobros
            inner join MetodoCobro on ObraDetalleCobros.IdMetodoCobro = MetodoCobro.IdMetodoCobro
            inner join Obra on Obra.IdObra = ObraDetalleCobros.IdObra
            Where ObraDetalleCobros.IdMetodoCobro in ( $idsMetodoCobro ) and 
            (date(ObraDetalleCobros.Creado) Between '$fechaIni' and '$fechaFin' ) 
            )
            ) as T
            Order by T.Fecha;";

            $result = mysqli_query($conexion->mysqli, $query);
            
            while($fila = $result->fetch_assoc())
            {
                $lista[] = $fila;
            }
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            
            $conexion->cerrarBD();
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result['result'];
    }
    
	public function getCuentasxCobrarGeneral()
	{
		$conexion = new Conexion();
        $lista = array();
		if( $conexion->abrirBD()!=NULL)
        {
			$query = "Select * from VistaCuentasCobrarGeneral";
			$result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc())
            {
                $lista[] = $fila;
            }
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            
            $conexion->cerrarBD();
		}
		else
		{
			$conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
		}
		return $conexion->result['result'];
	}
    
	public function getDetalledeCobros($fechaIni, $fechaFin)
	{
		$conexion = new Conexion();
        $lista = array();

		if( $conexion->abrirBD()!=NULL)
        {
			if($fechaIni == -1)
				$query = "Select * from VistaCuentasCobrarDetallePagosGeneral";
			else
				$query = "Select * from VistaCuentasCobrarDetallePagosGeneral where "
			    . "FechaCobro >= '" . $fechaIni . "' and FechaCobro <= '" . $fechaFin . "'";
			
			$result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc())
            {
                $lista[] = $fila;
            }

            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            
            $conexion->cerrarBD();
		}
		else
		{
			$conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
		}

		return $conexion->result['result'];
	}

    public function getGastosxCategoria($categoria, $fechaIni, $fechaFin)
    {
        $conexion = new Conexion();
        $lista = array();

        if( $conexion->abrirBD()!=NULL)
        {
            if($fechaIni == -1)
                $query = "SELECT * FROM VistaGastosxCategoria WHERE VistaGastosxCategoria.IdCategoria = ". $categoria;
            else
                $query = "SELECT * FROM VistaGastosxCategoria WHERE VistaGastosxCategoria.IdCategoria = ". $categoria ." AND ( CAST(Fecha as Date) >= '". $fechaIni ."' AND CAST(Fecha as Date) <= '". $fechaFin ."')";

            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc())
            {
                $lista[] = $fila;
            }

            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            
            $conexion->cerrarBD();
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result['result'];
    }

    public function getBitacoraMateriales($fechaIni, $fechaFin, $idProveedor)
    {
        $conexion = new Conexion();
        $lista = array();

        if( $conexion->abrirBD()!=NULL)
        {
            if ($idProveedor == 0){
                if($fechaIni == -1)
                    $query = "SELECT * FROM VistaBitacoraMateriales";
                else
                    $query = "SELECT * FROM VistaBitacoraMateriales WHERE ( CAST(Fecha as Date) >= '". $fechaIni ."' AND CAST(Fecha as Date) <= '". $fechaFin ."')";
            }
            else {
                if($fechaIni == -1)
                    $query = "SELECT * FROM VistaBitacoraMateriales WHERE IdProveedor = ". $idProveedor;
                else
                    $query = "SELECT * FROM VistaBitacoraMateriales WHERE IdProveedor = ". $idProveedor ." AND ( CAST(Fecha as Date) >= '". $fechaIni ."' AND CAST(Fecha as Date) <= '". $fechaFin ."')";
            }
            //echo "*****". $query ."*****";
            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc())
            {
                $lista[] = $fila;
            }

            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            
            $conexion->cerrarBD();
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
        return $conexion->result['result'];
    }

    public function getConciliacionInventario($IdUbicacion){
        $conexion = new Conexion();
        $lista = array();

        if( $conexion->abrirBD()!=NULL)
        {
            $query = "SELECT * FROM VistaInventarioUbicacionMaterial WHERE VistaInventarioUbicacionMaterial.IdUbicacion = ". $IdUbicacion;

            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc())
            {
                $lista[] = $fila;
            }

            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            
            $conexion->cerrarBD();
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result['result'];
    }

    public function getRequisicionesxFecha($fechaIni, $fechaFin){
        $conexion = new Conexion();
        $lista = array();

        if( $conexion->abrirBD()!=NULL)
        {
            if($fechaIni == -1)
                $query = "SELECT * FROM VistaRequisicionesxFecha";
            else
                $query = "SELECT * FROM VistaRequisicionesxFecha WHERE ( CAST(Fecha as Date) >= '". $fechaIni ."' AND CAST(Fecha as Date) <= '". $fechaFin ."')";

            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc())
            {
                $lista[] = $fila;
            }

            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            
            $conexion->cerrarBD();
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
        return $conexion->result['result'];
    }

    public function getReporteDePagos($fechaIni, $fechaFin)
    {
        $conexion = new Conexion();
        $lista = array();
        if( $conexion->abrirBD()!=NULL)
        {
            if($fechaIni == -1)
                $query = "SELECT * FROM VistaDetallePagos ORDER BY Creado";
            else
                $query = "SELECT * FROM VistaDetallePagos WHERE Creado BETWEEN '" . $fechaIni . "' AND '".$fechaFin."' ORDER BY Creado";
            //echo "***********".$query."******************";
            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc())
            {
                $lista[] = $fila;
            }
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            
            $conexion->cerrarBD();
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }
        return $conexion->result['result'];
    }

    public function getReporteComprasxProveedor($fechaIni, $fechaFin, $idProveedor, $idEstado)
    {
        $conexion = new Conexion();
        $lista = array();
        
        if( $conexion->abrirBD() != NULL)
        {
            $query = "SELECT * FROM VistaHistoricoPagosOc WHERE 1=1";
            if($fechaIni != -1)
                $query = $query." AND Creado BETWEEN '". $fechaIni ."' AND '". $fechaFin ."'";
            if($idProveedor != 0)
                $query = $query ." AND IdProveedor = ". $idProveedor;
            if($idEstado == 1)
                $query = $query ." AND IdEstadoOC != 5";
            else if($idEstado == 5)
                $query = $query ." AND IdEstadoOC = ". $idEstado;
            $query = $query. " ORDER BY NumeroFactura";

            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc())
            {
                $lista[] = $fila;
            }

            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            
            $conexion->cerrarBD();
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result['result'];
    }

    public function getReporteCuentasxPagarProv(){
        $conexion = new Conexion();
        $lista = array();
        
        if( $conexion->abrirBD() != NULL)
        {
            $query = "SELECT * FROM VistaCuentasPorPagarxProvReport WHERE Eliminado IS NULL AND Deuda > 0 ORDER BY Proveedor";

            $result = mysqli_query($conexion->mysqli, $query);
            while($fila = $result->fetch_assoc())
            {
                $lista[] = $fila;
            }

            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            
            $conexion->cerrarBD();
        }
        else
        {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result['result'];
    }

    public function getReporteCuentasxPagar() {
        $conexion = new Conexion();
        $lista = array();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM VistaCuentasPorPagar WHERE Proponer = 1 AND Eliminado IS NULL";
            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $lista[] = $fila;
            }

            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            $conexion->cerrarBD();
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result['result'];
    }

    public function getReporteCuentasxPagarxProv() {
        $conexion = new Conexion();
        $lista = array();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM VistaCuentasPorPagarProveedor";
            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $lista[] = $fila;
            }

            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            $conexion->cerrarBD();
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result['result'];
    }

    public function getReporteCatalogoMateriales() {
        $conexion = new Conexion();
        $lista = array();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM VistaMaterialMedidaCategoria WHERE Eliminado IS NULL ORDER BY IdCategoria, IdMaterial";
            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $lista[] = $fila;
            }

            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            $conexion->cerrarBD();
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result['result'];
    }

    public function getReporteCatalogoProveedores() {
        $conexion = new Conexion();
        $lista = array();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM Proveedor ORDER BY IdProveedor";
            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $lista[] = $fila;
            }

            $conexion->result['error'] = 0;
            $conexion->result['result'] = $lista;
            $conexion->cerrarBD();
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = $conexion->mysqli->error;
        }

        return $conexion->result['result'];
    }
}