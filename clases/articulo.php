<?php
include_once 'conexion.php';

class Articulo {
    public $id;
    private $IdLinea;
    private $Nombre;
    private $Descripcion;
    public  $DetalleArticulo;
    public  $FotosArticulo;
    public  $Foto;
    
    public function __construct() {
        $this->id = NULL;
        $this->IdLinea = NULL;
        $this->Clave = NULL;
        $this->Nombre = NULL;
        $this->Descripcion = NULL;
        $this->DetalleArticulo = NULL;
        $this->FotosArticulo = NULL;
        $this->Foto = NULL;
    }
    
    public function llenaDatos($id_,
                            $IdLinea_,
                            $Clave_,
                            $Nombre_,
                            $Descripcion_,
                            $DetalleArticulo_,
                            $FotosArticulo_,
                            $Foto_) {
        $this->id = $id_;
        $this->IdLinea = $IdLinea_;
        $this->Clave = $Clave_;
        $this->Nombre = $Nombre_;
        $this->Descripcion = $Descripcion_;
        $this->DetalleArticulo = $DetalleArticulo_;
        $this->FotosArticulo = $FotosArticulo_;
        $this->Foto = $Foto_;
    }
    
    public function insertaArticulo() {
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        $conexion->existe('Articulo', 'Nombre', "'". $this->Nombre ."'", $this->id);
        $repetido = $conexion->result['result'];
        $mierror = '';

        if ($repetido != 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE ARTÍCULO REPETIDO.";
            return $conexion->result;
        }
        
        try {
            $conexion->obtenerNuevoIdTabla('Articulo');
            $nueviId = $conexion->result['result'];
            $conexion->result['IdArticulo'] = -1;

            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                
                $query = "INSERT INTO Articulo
                (IdArticulo,
                IdLinea,
                Clave,
                Nombre,
                Descripcion,
                Foto)
                VALUES
                (". $nueviId .",
                ". $this->IdLinea .",
                '". $this->Clave ."',
                '". $this->Nombre ."',
                '". $this->Descripcion ."',
                '". $this->Foto ."');";
                //echo 'band_query_exito: '. $band_query_exito .'***********'. $query .'*************';
                
                if (!mysqli_query($conexion->mysqli, $query)) {
                    $band_query_exito = 0;
                }
                //var_dump($this->DetalleArticulo);
                if ($band_query_exito == 1 && !empty($this->DetalleArticulo)) {
                    foreach ($this->DetalleArticulo as $value) {
                        $query = "INSERT INTO ArticuloDetalle (IdArticulo, IdMaterial, Cantidad) VALUES (". $nueviId .", ". $value['IdMaterial'] .", ". $value['Cantidad'] .");";
                        //echo 'band_query_exito: '. $band_query_exito .'***********'. $query .'*************';
                        if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -1;
                        }
                    }
                }
                
                if ($band_query_exito == 1) {
                    foreach ($this->FotosArticulo as $value) {
                        $query = "INSERT INTO ArticuloFoto (IdArticulo, Foto) VALUES (". $nueviId .", '". $value['Foto'] ."');";
                        //echo 'band_query_exito: '. $band_query_exito .'***********'. $query .'*************';
                        if (!mysqli_query($conexion->mysqli, $query)) {
                            $band_query_exito = -2;
                        }
                    }
                }
                //echo $band_query_exito ."*********";
                if ($band_query_exito == 1) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'ARTÍCULO INSERTADO';
                    $conexion->result['IdArticulo'] = $nueviId;
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "ARTÍCULO NO INSERTADO";
                }
                
                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "ARTÍCULO NO INSERTADO, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ARTÍCULO NO INSERTADO";
        }
        return $conexion->result;
    }
    
    public function editar() {
        $conexion = new Conexion();
        $conexion->existe('Articulo', 'Nombre', "'". $this->Nombre ."'", $this->id);
        $repetido = $conexion->result['result'];
        
        if ($repetido != 0) {
            $conexion->result['error'] = 2;
            $conexion->result['result'] = "NOMBRE DE ARTÍCULO REPETIDO.";
            echo json_encode($conexion->result);
            return;
        }
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Articulo SET Clave = '$this->Clave', IdLinea = ". $this->IdLinea .", Nombre = '$this->Nombre', Descripcion = '$this->Descripcion' WHERE IdArticulo = ". $this->id;
                //echo "****".$query."****";
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO MODIFICADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
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
    
    public function baja($idArt) {
        $conexion = new Conexion();

        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Articulo SET Eliminado = now() WHERE IdArticulo = ". $idArt;

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
    
    public function agregarMaterialArticulo($idArticulo_, $IdMaterial_, $cantidad_) {
        $conexion = new Conexion();
        //echo "*****".$IdMaterial_."****";
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "INSERT INTO ArticuloDetalle (IdArticulo, IdMaterial, Cantidad) VALUES (". $idArticulo_ .", ". $IdMaterial_ .", ".$cantidad_.");";
                
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO INSERTADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
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
    
    public function editarMaterialArticulo($idArticuloDetalle_, $cantidad_) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE ArticuloDetalle SET Cantidad = ". $cantidad_ ." WHERE IdArticuloDetalle = ". $idArticuloDetalle_;
                
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'REGISTRO MODIFICADO';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
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
    
    public function eliminarMaterialArticulo($idArticuloDetalle_) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE ArticuloDetalle SET Eliminado = now() WHERE IdArticuloDetalle = ". $idArticuloDetalle_;
                
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
    
    public function eliminarFotoArticulo($idArticuloFoto_) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE ArticuloFoto SET Eliminado = now() WHERE IdArticuloFoto = ". $idArticuloFoto_;
                
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
    
    public function fotoToPrincipal($Foto_, $idArticulo_) {
        $conexion = new Conexion();
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $query = "UPDATE Articulo SET Foto = '". $Foto_ ."' WHERE IdArticulo = ". $idArticulo_;
                
                if (mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'LA FOTO '. $Foto_ .' AHORA ES LA PRINCIPAL';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "IMPOSIBLE CAMBIAR LA FOTO A PRINCIPAL, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "A OCURRIDO UN ERROR IMPOSIBLE CAMBIAR LA FOTO A PRINCIPAL";
        }

        echo json_encode($conexion->result);
    }

    public function ArticulosEntrada($aIdArticulo, $aCantidad, $isEntrada) {
        $conexion = new Conexion();
        $conexion->existeArticuloenAlmacen($aIdArticulo);
        $repetido = $conexion->result['result'];
        $msg = "ENTRADA";
        $entrada = 1;

        if ($isEntrada == 0) {
            $msg = "SALIDA";
            $entrada = 0;
        }

        $band_query_exito = 1;

        if ($repetido != 0) {
            $query = "UPDATE ArticuloAlmacen SET Cantidad = (Cantidad + ". $aCantidad .") WHERE IdArticulo = ". $aIdArticulo;

            if ($entrada == 0)
                $query ="UPDATE ArticuloAlmacen SET Apartado = (Apartado + ". $aCantidad .") WHERE IdArticulo = ". $aIdArticulo;

            try {
                 if ($conexion->abrirBD() != NULL) {
                    $conexion->iniciaTransaccion();

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }

                    if ($band_query_exito == 1) {
                        $query = "SELECT IdParte, Cantidad FROM ArticuloDetalle WHERE IdArticulo = ". $aIdArticulo;
                        $resultado = mysqli_query($conexion->mysqli, $query);

                        while ($registro = mysqli_fetch_assoc($resultado)) {
                            $aIdParte = $registro['IdParte'];
                            $aCantidadParte = $registro['Cantidad'];
                            $aCantTotal = $aCantidadParte * $aCantidad;
                            $query = "UPDATE ParteAlmacen SET Cantidad = (Cantidad + ". $aCantTotal .") WHERE IdParte = ". $aIdParte;
                            $queryMov = "INSERT INTO MovimientoAlmacen(IdArticulo, IdParte, Cantidad, Tipo) VALUES (". $aIdArticulo .", ". $aIdParte .", ". $aCantidad .", 1)";

                            if ($entrada == 0) {
                                $query = "UPDATE ParteAlmacen SET Apartado = (Apartado + ". $aCantTotal .") WHERE IdParte = ". $aIdParte;
                                $queryMov = "INSERT INTO MovimientoAlmacen(IdArticulo, IdParte, Cantidad, Tipo) VALUES (". $aIdArticulo .", ". $aIdParte .", ". $aCantidad .", 0)";
                            }
                            
                            if (!mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = 0;
                            }
                            
                            if ($band_query_exito == 1) {
                                if (!mysqli_query($conexion->mysqli, $queryMov)) {
                                    $band_query_exito = 0;
                               }
                            }
                        }
                    }
                    
                    if ($band_query_exito == 1) {
                        $conexion->commit();
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = $msg .' ARTÍCULO REGISTRADA';
                        $conexion->result['IdArticulo'] = $nueviId;
                   }
                   else {
                       $conexion->result['error'] = 1;
                       $conexion->result['result'] = $msg ." DE ARTÍCULO NO REGISTRADA";
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
               $conexion->result['result'] = $msg . ' DE ARTÍCULO NO REGISTRADA';
            }
        }
        else {
            $query ="INSERT INTO ArticuloAlmacen(IdArticulo,Cantidad) VALUES (". $aIdArticulo .", ". $aCantidad .")";

            try {
                if ($conexion->abrirBD() != NULL) {
                    $conexion->iniciaTransaccion();

                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }

                    if ($band_query_exito == 1) {
                        $query = "SELECT IdParte, Cantidad FROM ArticuloDetalle WHERE IdArticulo = ". $aIdArticulo;
                        $resultado = mysqli_query($conexion->mysqli, $query);

                        while ($registro = mysqli_fetch_assoc($resultado)) {
                            $aIdParte = $registro['IdParte'];
                            $aCantidadParte = $registro['Cantidad'];
                            $aCantTotal = $aCantidadParte * $aCantidad;
                            $query = "INSERT INTO ParteAlmacen(IdParte, Cantidad) VALUES (". $aIdParte .", ". $aCantTotal .")";
                            $queryMov = "INSERT INTO MovimientoAlmacen(IdArticulo, IdParte, Cantidad, Tipo) VALUES (". $aIdArticulo .", ". $aIdParte .", ". $aCantidad .", 1)";
                            
                            if (!mysqli_query($conexion->mysqli, $query)) {
                                $band_query_exito = 0;
                            }

                            if ($band_query_exito == 1) {
                                if (!mysqli_query($conexion->mysqli, $queryMov)) {
                                    $band_query_exito = 0;
                                }
                            }
                        }
                    }
                    
                    if ($band_query_exito == 1) {
                        $conexion->commit();
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = $msg .' ARTÍCULO REGISTRADA';
                    }
                    else {
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = $msg ." DE ARTÍCULO NO REGISTRADA";
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
                $conexion->result['result'] = $msg .' DE ARTÍCULO NO REGISTRADA';
            }
        }
        return $conexion->result;
    }
    
    public function ParteEntrada($aIdParte, $aCantidad, $isEntrada) {
        $conexion = new Conexion();
        $conexion->existeParteenAlmacen($aIdParte);
        $repetido = $conexion->result['result'];
        $msg = "ENTRADA";
        $entrada = 1;

        if ($isEntrada == 0) {
            $msg = "SALIDA";
            $entrada = 0;
        }

        $band_query_exito = 1;
        
        if ($repetido != 0) {
            $query = "UPDATE ParteAlmacen SET Cantidad = (Cantidad + ". $aCantidad .") WHERE IdParte = ". $aIdParte;

            if ($entrada == 0)
                $query = "UPDATE ParteAlmacen SET Apartado = (Apartado + ". $aCantidad .") WHERE IdParte = ". $aIdParte;
            
            try {
                if ($conexion->abrirBD() != NULL) {
                    $conexion->iniciaTransaccion();
                    
                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }

                    if($band_query_exito == 1) {
                        $conexion->commit();
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = $msg .' PARTE REGISTRADA';
                        $conexion->result['IdArticulo'] = $nueviId;
                    }
                    else {
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = $msg ." DE PARTE NO REGISTRADA";
                    }

                    $conexion->cerrarBD();  
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = "REGISTRO NO INSERTADO, ERROR CONEXION BD";
                }
            }
            catch (Exception $ex) 
            {
               $conexion->result['error'] = 1;
               $conexion->result['result'] = $msg . ' DE PARTE NO REGISTRADA';
            }
        }
        else {
            $query = "INSERT INTO ParteAlmacen(IdParte, Cantidad) VALUES (". $aIdParte .", ". $aCantidad .")";

            try {
                if ($conexion->abrirBD() != NULL) {
                    $conexion->iniciaTransaccion();
                    if (!mysqli_query($conexion->mysqli, $query)) {
                        $band_query_exito = 0;
                    }

                    if ($band_query_exito == 1) {
                        $conexion->commit();
                        $conexion->result['error'] = 0;
                        $conexion->result['result'] = $msg .' PARTE REGISTRADA';
                    }
                    else {
                        $conexion->result['error'] = 1;
                        $conexion->result['result'] = $msg . " DE PARTE NO REGISTRADA";
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
                $conexion->result['result'] = $msg . ' DE PARTE NO REGISTRADA';
            }
        }
        return $conexion->result;
    }
    
    public function nuevafoto($foto, $Id, $Principal) {
        $conexion = new Conexion();
        $band_query_exito = 1;
        
        try {
            if ($conexion->abrirBD() != NULL) {
                $conexion->iniciaTransaccion();
                $query = "INSERT INTO ArticuloFoto(IdArticulo, Foto) VALUES (". $Id .", '". $foto ."')";
                
                if (!mysqli_query($conexion->mysqli, $query) == TRUE) {
                    $band_query_exito = 0;
                }
                if ($band_query_exito == 1 && $Principal == 1) {
                    $query = "UPDATE Articulo SET Foto = '". $foto ."' WHERE IdArticulo = ". $Id;

                    if (!mysqli_query($conexion->mysqli, $query) == TRUE) {
                       $band_query_exito = 0;
                    }
                }
                
                if ($band_query_exito == 1) {
                    $conexion->commit();
                    $conexion->result['error'] = 0;
                    $conexion->result['result'] = 'NUEVA FOTO REGISTRADA';
                }
                else {
                    $conexion->result['error'] = 1;
                    $conexion->result['result'] = $conexion->mysqli->error;
                }

                $conexion->cerrarBD();
            }
            else {
                $conexion->result['error'] = 1;
                $conexion->result['result'] = "FOTO NO REGISTRADA, ERROR CONEXION BD";
            }
        }
        catch (Exception $ex) {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "FOTO NO REGISTRADA";
        }

        return $conexion->result;
    }

    public function getArticulosFilter($busqueda) {
        $conexion = new Conexion();
        
        if ($conexion->abrirBD() != NULL) {
            if ($busqueda != NULL) {
                $query = "SELECT * FROM Articulo WHERE Eliminado IS NULL AND (Nombre LIKE '%". $busqueda ."%' OR Clave LIKE '%". $busqueda ."%') order by Nombre LIMIT 5;";
            }
            else {
                $query = "SELECT * FROM Articulo WHERE Eliminado IS NULL order by Nombre LIMIT 5;";
            }

            $result = mysqli_query($conexion->mysqli, $query);
            $data = array();

            while ($row = mysqli_fetch_array($result)) {
                $data[] = array("id" => $row['IdArticulo'], "text" => $row['Clave'] ." ". $row['Nombre']);
            }

            $data = $conexion->utf8_converter($data);
            echo json_encode($data);
            $conexion->cerrarBD();
            $conexion->result['error'] = 0;
            $conexion->result['result'] = "";
        }
        else {
            $conexion->result['error'] = 1;
            $conexion->result['result'] = "ERROR CONEXION BD";
        }
    }
}