<?php

include_once 'conexion.php';
include_once 'nodoMenu.php';
/**
 * Description of menu
 *
 * @author rafael
 */

class Menu {
    
    public $raiz;
    
    public function __construct() 
    {
        $this->raiz = array();
        $this->construirRaiz();
    }
    
    function construirRaiz()
    {
        $i = 0;
        
        $conn = new Conexion();
        $conn->abrirBD();

        $sql = "SELECT IdModulo, Nombre, Padre FROM Modulo WHERE Padre IS NULL AND Visible = 1";
        $result = $conn->mysqli->query($sql);
        
        $conn->cerrarBD();

        while($row = $result->fetch_assoc()) {
            $nodoMenu = new NodoMenu();
            $nodoMenu->idModulo = $row["IdModulo"];
            $nodoMenu->nombre = $row["Nombre"];
            $nodoMenu->idPadre = $row["Padre"];
            $nodoMenu->numHijos = $this->obtenerCuentaHijos($nodoMenu->idModulo);
            if($nodoMenu->numHijos > 0){
                $this->obtenerHijos($nodoMenu);
            }
            $this->raiz[$i] = $nodoMenu;
            $i++;
        }
    }
    
    function obtenerHijos($nodoMenu)
    {
        $i = 0;
        
        $conn = new Conexion();
        $conn->abrirBD();

        $sql = "SELECT IdModulo, Nombre, Padre FROM Modulo WHERE Padre = " . $nodoMenu->idModulo . " AND Visible = 1";
        $result = $conn->mysqli->query($sql);
        
        $conn->cerrarBD();

        while($row = $result->fetch_assoc()) {
            $hijo = new NodoMenu();
            $hijo->idModulo = $row["IdModulo"];
            $hijo->nombre = $row["Nombre"];
            $hijo->idPadre = $row["Padre"];
            $hijo->numHijos = $this->obtenerCuentaHijos($hijo->idModulo);
            if($hijo->numHijos > 0){
                $this->obtenerHijos($hijo);
            }
            $nodoMenu->hijos[$i] = $hijo;
            $i++;
        }
    }
    
    function obtenerCuentaHijos($idPadre)
    {
        $hijos = 0;
        
        $conexion = new Conexion();
        $conexion->abrirBD();
        
        $query = "SELECT COUNT(*) AS numeroRegistros FROM Modulo WHERE Padre = " . $idPadre . " AND Visible = 1";
        $result = mysqli_query($conexion->mysqli, $query);
        $result = mysqli_fetch_assoc($result);
        $numRegistros = $result['numeroRegistros'];
        
        $conexion->cerrarBD();
        $hijos = $numRegistros;
        
        return $hijos;
    }
    
    public function construirJSON()
    {
        $json = '[';
        
        foreach ($this->raiz as $nodoRaiz) {
            $json .= $this->getSubJSON($nodoRaiz);
            $json .= ',';
        }
        
        $json = substr($json, 0, -1);
        $json .= ']';
        
        echo $json;
    }
    
    function getSubJSON($nodoRaiz)
    {
        $json = '{ "text": "' . $nodoRaiz->nombre . '", "id": "' . $nodoRaiz->idModulo . '"';
        
        if($nodoRaiz->numHijos > 0)
        {
            $json .= ', "nodes": [';
            foreach ($nodoRaiz->hijos as $nodoHijo) {
                $json .= $this->getSubJSON($nodoHijo);
                $json .= ',';
            }
            $json = substr($json, 0, -1);
            $json .= ']';
        }
        
        $json .= '}';
        return $json;
    }
}