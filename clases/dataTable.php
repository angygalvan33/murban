<?php 
include_once 'conexion.php';

class dataTable {
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
    public $consulta;

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
        $this->consulta = "";
        $this->conexion = new Conexion();
    }
    
    public function construyeConsulta($query_)
    {
        $this->consulta = $query_;
   //     echo $this->consulta;
    }

    public function getData($sql){
        $this->conexion->abrirBD();
        $data = array();
        
        if($this->conexion->mysqli!=NULL)
        {
            $query = mysqli_query($this->conexion->mysqli, $sql) OR DIE ("Can't get Data from DB , check your SQL Queryy ".$sql);
            $this->conexion->cerrarBD();
        
            foreach ($query as $row ) {
                $data[] = $row ;
            }
        }
        //var_dump($data);
        return $data;
    }
    
    //regresa el total de registros existentes en la tabla
    public function totalRegistros($sql){
        return count($this->getData($sql));
    }
    
    public function registrosTabla()
    {
        $data = array();
        $registros = array();
        
        $sql = $this->consulta;
        $recordsTotal = $this->totalRegistros($sql);
        
        /* SEARCH CASE : Filtered data */
        if(!empty($this->searchValue)){
            /* WHERE Clause for searching */
            for($i=0 ; $i<count($this->columns);$i++){
                $column = $this->columns[$i]['data'];//we get the name of each column using its index from POST request
                
                if($column!='Accion')
                    $where[]="$column like '%".$this->searchValue."%'";
            }
            $where = "AND (".implode(" OR " , $where);// id like '%searchValue%' or name like '%searchValue%' ....
            $where.=")";
            /* End WHERE */

            $sql = sprintf($this->consulta." %s", $where);//Search query without limit clause (No pagination)
            
            $recordsFiltered = count($this->getData($sql));//Count of search result

            /* SQL Query for search with limit and orderBy clauses*/
            $sql = sprintf($this->consulta." %s"." ORDER BY %s %s limit %d , %d ",$where, $this->orderBy,$this->orderType,$this->start,$this->length);
            $data = $this->getData($sql);
            
        }
        
        else
        {
            $sql = sprintf($this->consulta." ORDER BY %s %s limit %d , %d ",$this->orderBy,$this->orderType,$this->start,$this->length);
            $data = $this->getData($sql);
            $recordsFiltered = $recordsTotal;
        }
        
        $conexion = new Conexion();
        $data = $conexion->utf8_converter($data);
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