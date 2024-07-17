<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of nodoMenu
 *
 * @author rafael
 */
class NodoMenu {
    
    public $idModulo;
    public $nombre;
    public $idPadre;
    public $hijos;
    public $numHijos;
    
    public function __construct() 
    {
        $this->idModulo = 0;
        $this->nombre = "";
        $this->idPadre = 0;
        $this->hijos = array();
        $this->numHijos = 0;
    }
}
