<?php
include_once 'conexion.php';
include_once 'comodin.php';

class Material {
    private $id;
    private $nombre;
    private $clave;
    private $descripcion;
    private $idMedida;
    private $medida;
    private $idCategoria;
    private $largo;
    private $ancho;
    private $alto;
    private $peso;
    private $unidad;
    private $pesoespecifico;
    
    public function __construct() {
        $this->id = NULL;
        $this->nombre = NULL;
        $this->clave = NULL;
        $this->descripcion = NULL;
        $this->idMedida = NULL;
        $this->medida = NULL;
        $this->idCategoria = NULL;
        $this->largo = NULL;
        $this->ancho = NULL;
        $this->alto = NULL;
        $this->peso = NULL;
        $this->unidad = NULL;
        $this->pesoespecifico = NULL;
    }
    
    public function llenaDatos( 
                            $id_,
                            $nombre_,
                            $clave_,
                            $descripcion_,
                            $idMedida_,
                            $medida_,
                            $idCategoria_,
                            $largo_,
                            $ancho_,
                            $alto_,
                            $peso_,
                            $unidad_,
                            $pesoespecifico_) {
        $this->id = $id_;
        $this->nombre = $nombre_;
        $this->nombre = $nombre_;
        $this->clave = $clave_;
        $this->idMedida = $idMedida_;
        $this->medida = $medida_;
        $this->idCategoria = $idCategoria_;
        $this->largo = $largo_;
        $this->ancho = $ancho_;
        $this->alto = $alto_;
        $this->peso = $peso_;
        $this->unidad = $unidad_;
        $this->pesoespecifico = $pesoespecifico_;
    }

    public function inserta() {
        $conexion = new Conexion();
        $comodin = new Comodin();
        
        $conexion->existeMaterial($this->nombre, "'". $this->medida ."'", $this->id);
        $repetido = $conexion->result['result'];
        
        if ($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE Y MEDIDA DE MATERIAL REPETIDOS.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            $conexion->obtenerNuevoIdTabla('Material')['result'];
            $nueviId = $conexion->result['result'];

            if ($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO Material
                (IdMaterial,
                Nombre,
                Clave,
                Descripcion,
                IdMedida,
                Medida,
                IdCategoria,
                Largo,
                Ancho,
                Alto,
                Peso,
                Unidad,
                PesoEspecifico,
                IdUsuario)
                VALUES
                (". $nueviId .",
                '$this->nombre',
                '$this->clave',
                '$this->descripcion',
                '$this->idMedida',
                '$this->medida',
                '$this->idCategoria',
                ". $this->largo .",
                ". $this->ancho .",
                ". $this->alto .",
                ". $this->peso .",
                ". $this->unidad .",
                ". $this->pesoespecifico .",
                ". $comodin->idUsuarioSession() .");";
                //echo '******'.$query.'******';
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO INSERTADO';
                    $conexion->result['Id'] = $nueviId;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                    $conexion->result['Id'] = -1;
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
    
    public function baja($idMat) {
        $conexion = new Conexion();
        
        $conexion->existe('HistoricoPrecioMaterial', 'IdMaterial', $idMat, -1);
        $repetido = $conexion->result['result'];
        
        if ($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "MATERIAL COTIZADO POR UN PROVEEDOR.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Material SET Eliminado = now() WHERE IdMaterial = ". $idMat;
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO ELIMINADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO ELIMINADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO ELIMINADO";
        }
        echo json_encode($conexion->result);
    }
    
    public function editar() {
        $conexion = new Conexion();
        $comodin = new Comodin();
        
        $conexion->existeMaterial($this->nombre, "'". $this->medida ."'", $this->id);
        $repetido = $conexion->result['result'];
        
        if ($repetido > 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE Y MEDIDA DE MATERIAL REPETIDOS.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Material
                SET Nombre = '$this->nombre',
                Clave = '$this->clave',
                Descripcion = '$this->descripcion',
                IdMedida = ". $this->idMedida .",
                Medida = '$this->medida',
                IdCategoria = '$this->idCategoria',
                Largo = ". $this->largo .",
                Ancho = ". $this->ancho .",
                Alto = ". $this->alto .",
                Peso = ". $this->peso .",
                Unidad = ". $this->unidad .",
                PesoEspecifico = ". $this->pesoespecifico .",
                IdUsuario = ". $comodin->idUsuarioSession() ."
                WHERE IdMaterial = ". $this->id;
                
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO MODIFICADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $query ."-". $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "REGISTRO NO MODIFICADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "REGISTRO NO MODIFICADO";
        }
        echo json_encode($conexion->result);
    }
    
    public function getMateriales() {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM Material WHERE Eliminado IS NULL;";
            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $listaMat[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaMat;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($conexion->result['result']);
    }
    
    public function getMaterialById($idMaterial) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM ProveedorByMaterial WHERE Eliminado IS NULL AND IdMaterial = ". $idMaterial .";";
            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $listaMat[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaMat;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        
        echo json_encode($conexion->result['result']);
    }
    
    public function getMaterial_ById($idMaterial) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM ProveedorByMaterial WHERE Eliminado IS NULL AND IdMaterial = ". $idMaterial .";";
            $result = mysqli_query($conexion->mysqli, $query);
            
            while ($fila = $result->fetch_assoc()) {
                $listaMat[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaMat;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        
        $txt = $conexion->utf8_converter($conexion->result['result']);
        echo json_encode(array("error" => $conexion->result['error'], "result"=>$txt));
    }
    
    public function getNombreMaterialById($idMaterial) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM Material WHERE Eliminado IS NULL AND IdMaterial = ". $idMaterial .";";
            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $listaMat[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaMat;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        return $conexion->result['result'][0]['Nombre'];
    }
    //se utiliza en la ordenes de compra para indicar el precio del material según el precio seleccionado 
    public function getMaterialByIdProveedor($idMaterial, $idProveedor) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            $query = "SELECT * FROM ProveedorByMaterial WHERE Eliminado IS NULL AND IdProveedor = ". $idProveedor ." AND IdMaterial = ". $idMaterial .";";
            $result = mysqli_query($conexion->mysqli, $query);

            while ($fila = $result->fetch_assoc()) {
                $listaMat[] = $fila;
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = $listaMat;
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        $txt = $conexion->utf8_converter($conexion->result['result']);
        echo json_encode(array("error" => $conexion->result['error'], "result"=>$txt));
    }
    
    public function getMaterialesFilter($busqueda) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            if ($busqueda != null) {
                $query = "SELECT * FROM Material WHERE Eliminado IS NULL AND Nombre like '%". $busqueda ."%' ORDER BY Material.Nombre LIMIT 10;";
            }
            else {
                $query = "SELECT * FROM Material WHERE Eliminado IS NULL ORDER BY Material.Nombre LIMIT 10;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            $listaMateriales = array();

            while ($row = mysqli_fetch_array($result)) {
                $mat = array();
                $mat['IdMaterial'] = $row['IdMaterial'];
                $mat['Nombre'] = $row['Nombre'];
                $mat['Medida'] = json_decode($row['Medida'], true);
                $listaMateriales[] = $mat;
                $imp = "";
                
                foreach ($mat['Medida'] as $value) {
                    $imp .= $value['nombre'] .' '. $value['valor'] .' '. $value['unidad'] .' ';
                }

                $data[] = array("id" => $mat['IdMaterial'], "text" => $mat['Nombre'] .' ('. $imp .')');
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($data);
    }
    
    public function getMaterialesStockFilter($busqueda) {
        $conexion = new Conexion();
        //VOLVER A SUBIR CUANDO ESTÉ RESUELTO LO DE LOS MATERIALES DE REQUISICIÓN ESPECIAL 
        if ($conexion->abrirBD() != NULL) {
            if ($busqueda != null) {
                $query = "(SELECT DISTINCT(Material.IdMaterial), Material.Nombre, Material.Medida
                    FROM Material
                    INNER JOIN Inventario ON Material.IdMaterial = Inventario.IdMaterial
                    WHERE (IdObra = -1 OR IdObra = 35 OR IdObra = 50 OR IdObra = 11 OR IdObra = 503 OR IdObra = 459 OR IdObra = 499)
                    AND Material.Eliminado IS NULL and Inventario.Eliminado IS NULL AND Material.Nombre like '%". $busqueda ."%' ORDER BY Material.Nombre LIMIT 5)
union all
(SELECT DISTINCT (Inventario.IdMaterial), Inventario.Nombre AS Nombre, '' AS Medida
                    FROM Inventario
                    WHERE (IdObra = -1 OR IdObra = 35 OR IdObra = 50 OR IdObra = 11 OR IdObra = 503 OR IdObra = 459 OR IdObra = 499) and Inventario.IdMaterial = -1
                    AND Inventario.Eliminado IS NULL AND Inventario.Nombre like '%". $busqueda ."%'
ORDER BY Inventario.Nombre LIMIT 5);";
            }
            else {
                $query = "(SELECT DISTINCT(Material.IdMaterial), Material.Nombre, Material.Medida
                    FROM Material
                    INNER JOIN Inventario ON Material.IdMaterial = Inventario.IdMaterial
                    WHERE (IdObra = -1 OR IdObra = 35 OR IdObra = 50 OR IdObra = 11 OR IdObra = 503 OR IdObra = 459 OR IdObra = 499)
                    AND Material.Eliminado IS NULL and Inventario.Eliminado IS NULL ORDER BY Material.Nombre LIMIT 5)
union all
(SELECT DISTINCT (Inventario.IdMaterial), Inventario.Nombre AS Nombre, '' AS Medida
                    FROM Inventario
                    WHERE (IdObra = -1 OR IdObra = 35 OR IdObra = 50 OR IdObra = 11 OR IdObra = 503 OR IdObra = 459 OR IdObra = 499) and Inventario.IdMaterial = -1
                    AND Inventario.Eliminado IS NULL ORDER BY Inventario.Nombre LIMIT 5);";
            }
            //echo "****".$query."****";
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            $listaMateriales = array();

            while ($row = mysqli_fetch_array($result)) {
                $mat = array();
                $mat['IdMaterial'] = $row['IdMaterial'];
                $mat['Nombre'] = $row['Nombre'];
                $listaMateriales[] = $mat;
                $imp = "";
                
                if ($mat['IdMaterial'] != -1) {
                    $mat['Medida'] = json_decode($row['Medida'], true);
                    foreach ($mat['Medida'] as $value) {
                        $imp .= $value['nombre'] .' '. $value['valor'] .' '. $value['unidad'] .' ';
                    }
                }

                if ($mat['IdMaterial'] != -1)
                    $data[] = array("id" => $mat['IdMaterial'], "text" => $mat['Nombre'] .' ('. $imp .')');
                else
                    $data[] = array("id" => $mat['IdMaterial'], "text" => $mat['Nombre']);
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        
        echo json_encode($data);
    }
    
    public function getMaterialProveedorFilter($busqueda, $idProveedor) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            if ($busqueda != null) {
                $base = "SELECT * FROM ProveedorByMaterial WHERE Eliminado IS NULL AND Material LIKE '%". $busqueda ."%' ";
                $query = $base ."AND IdProveedor = ". $idProveedor ." LIMIT 5";
            }
            else {
                $query = "SELECT * FROM ProveedorByMaterial WHERE Eliminado IS NULL AND IdProveedor = ". $idProveedor ." LIMIT 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            $listaMateriales = array();

            while ($row = mysqli_fetch_array($result)) {
                $mat = array();
                $mat['Medida'] = json_decode($row['Medida'], true);
                $listaMateriales[] = $mat;
                $imp = "";
                
                foreach ($mat['Medida'] as $value) {
                    $imp .= $value['nombre'] .' '. $value['valor'] .' '. $value['unidad'] .' ';
                }
                
                $data[] = array("id" => $row['IdMaterial'], "text" => $row['Material'] .' ('. $imp .')');
            }
            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        $data = $conexion->utf8_converter($data);
        echo json_encode($data);
    }

    public function getCotizadoresFilter($busqueda, $idProveedor) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            if ($busqueda != null) {
                $query = "SELECT DISTINCT Cotizador FROM HistoricoPrecioMaterial WHERE Eliminado IS NULL AND IdProveedor = ". $idProveedor ." AND Cotizador LIKE '%". $busqueda ."%' LIMIT 5;";
            }
            else {
                $query = "SELECT DISTINCT Cotizador FROM HistoricoPrecioMaterial WHERE Eliminado IS NULL AND IdProveedor = ". $idProveedor ." LIMIT 5;";
            }
            
            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            
            while ($row = mysqli_fetch_array($result)) {
                $mat = array();
                $mat['Cotizador'] = $row['Cotizador'];
                $data[] = array("id" => $mat['Cotizador'], "text" => $mat['Cotizador']);
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
        
        echo json_encode($data);
    }

    public function getMaterialesProyectosFilter($busqueda) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            if ($busqueda != null) {
                $query = "SELECT DISTINCT(Material.IdMaterial), Material.Nombre
                    FROM Material
                    INNER JOIN Inventario ON Material.IdMaterial = Inventario.IdMaterial
                    WHERE Material.Eliminado IS NULL
                    AND Material.Nombre LIKE '%". $busqueda ."%'
                    ORDER BY Material.Nombre LIMIT 5;";
            }
            else {
                $query = "SELECT DISTINCT(Material.IdMaterial), Material.Nombre
                    FROM Material
                    INNER JOIN Inventario ON Material.IdMaterial = Inventario.IdMaterial
                    WHERE Material.Eliminado IS NULL
                    ORDER BY Material.Nombre LIMIT 5;";
            }

            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();
            $listaMateriales = array();
            while ($row = mysqli_fetch_array($result)) {
                $mat = array();
                $mat['IdMaterial'] = $row['IdMaterial'];
                $mat['Nombre'] = $row['Nombre'];
                $listaMateriales[] = $mat;
                $data[] = array("id" => $mat['IdMaterial'], "text" => $mat['Nombre']);
            }

            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }

        echo json_encode($data);
    }
}