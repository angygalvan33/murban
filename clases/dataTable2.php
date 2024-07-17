<?php 
include_once '../../clases/conexion.php';

class dataTableAnt {
    //put your code here
    public $draw;
    public $orderByColumnIndex;
    public $orderBy;
    public $orderType;
    public $start;
    public $length;
    public $nombreTabla;
    public $conexion;
    public $searchValue;
    public $columns;

    public function __construct($draw_,$orderByColumnIndex_,$orderBy_,$orderType_,$start_,$length_, $nombreTabla_, $searchValue_, $columns_) 
    {
        $this->draw = $draw_;
        $this->orderByColumnIndex  = $orderByColumnIndex_;
        $this->orderBy = $orderBy_;
        $this->orderType = $orderType_;
        $this->start  = $start_;
        $this->length = $length_;
        $this->nombreTabla = $nombreTabla_;
        $this->searchValue = $searchValue_;
        $this->columns =$columns_;
        
        $this->conexion = new Conexion();
    }
    
    public function getData($sql){
        $this->conexion->abrirBD();
        $data = array();
        
        if($this->conexion->mysqli!=NULL)
        {
            $query = mysqli_query($this->conexion->mysqli, $sql) OR DIE ("Can't get Data from DB , check your SQL Queryy ");
            $this->conexion->cerrarBD();
        
            foreach ($query as $row ) {
                $data[] = $row ;
            }
        }
        
        return $data;
    }
    
    //regresa el total de registros existentes en la tabla
    public function totalRegistros($nombreTabla){
        return count($this->getData("SELECT * FROM ".$nombreTabla." WHERE Eliminado IS NULL"));
    }
    
    public function registrosTabla()
    {
        $data = array();
        $registros = array();
        
        $whereEliminados = '(Eliminado IS NULL)';
        $recordsTotal = $this->totalRegistros($this->nombreTabla);

        /* SEARCH CASE : Filtered data */
        if(!empty($this->searchValue)){

            /* WHERE Clause for searching */
            for($i=0 ; $i<count($this->columns);$i++){
                $column = $this->columns[$i]['data'];//we get the name of each column using its index from POST request
                $where[]="$column like '%".$this->searchValue."%'";
            }
            $where = "WHERE ".implode(" OR " , $where);// id like '%searchValue%' or name like '%searchValue%' ....
            $where.= " AND ".$whereEliminados;
            /* End WHERE */

            $sql = sprintf("SELECT * FROM %s %s", $this->nombreTabla , $where);//Search query without limit clause (No pagination)

            $recordsFiltered = count($this->getData($sql));//Count of search result

            /* SQL Query for search with limit and orderBy clauses*/
            $sql = sprintf("SELECT * FROM %s %s ORDER BY %s %s limit %d , %d ",$this->nombreTabla,$where, $this->orderBy,$this->orderType,$this->start,$this->length);
            $data = $this->getData($sql);
        }
        /* END SEARCH */
        else {
            $sql = sprintf("SELECT * FROM %s WHERE %s ORDER BY %s %s limit %d , %d ", $this->nombreTabla,$whereEliminados,$this->orderBy,$this->orderType,$this->start,$this->length);
            $data = $this->getData($sql);

            $recordsFiltered = $recordsTotal;
        }

        /* Response to client before JSON encoding */
        $response = array(
            "draw" => intval($this->draw),
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data
        );

        return $response;
    }

}
