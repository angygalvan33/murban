<?php
include_once '../../clases/material.php';
include_once '../../clases/medida.php';
include_once '../../clases/categoria.php';
include_once '../../clases/historicoPrecioMateriales.php';

$accion = $_POST['accion'];

function getUnidadRef($sunidad) {
	if ($sunidad == 'long-cm-100')
		return 2;
	else if ($sunidad == 'long-m-1')
		return 1;
	else if ($sunidad == 'long-pulg-39.37')
		return 3;
	else if ($sunidad == 'long-pies-3.281')
		return 4;
	else if ($sunidad == 'peso-gr-100')
		return 5;
	else if ($sunidad == 'peso-kg-1')
		return 6;
	else
		return 0;
}
//alta
if ($accion == 'alta') {
    $_POST['nombre'] = empty($_POST['nombre']) == TRUE ? '' : $_POST['nombre'];
    $_POST['clave'] = empty($_POST['clave']) == TRUE ? 0 : $_POST['clave'];
    $_POST['descripcion'] = empty($_POST['descripcion']) == TRUE ? '' : $_POST['descripcion'];
    $_POST['tipoMedida'] = empty($_POST['tipoMedida']) == TRUE ? '' : $_POST['tipoMedida'];
    $_POST['medida'] = empty($_POST['medida']) == TRUE ? '' : $_POST['medida'];
    $_POST['idCategoria'] = empty($_POST['idCategoria']) == TRUE ? '' : $_POST['idCategoria'];	
	$_POST['Largo'] = empty($_POST['Largo']) == TRUE ? 0 : $_POST['Largo'];
	$_POST['Ancho'] = empty($_POST['Ancho']) == TRUE ? 0 : $_POST['Ancho'];
	$_POST['alto'] = empty($_POST['alto']) == TRUE ? 0 : $_POST['alto'];
	$_POST['Peso'] = empty($_POST['Peso']) == TRUE ? 0 : $_POST['Peso'];
    $_POST['comboLongitud'] = empty($_POST['comboLongitud']) == TRUE ? 0 : $_POST['comboLongitud'];
	$_POST['comboPeso'] = empty($_POST['comboPeso']) == TRUE ? 0 : $_POST['comboPeso'];
	$_POST['pesoespecifico'] = empty($_POST['pesoespecifico']) == TRUE ? 0 : $_POST['pesoespecifico'];
	$_POST['pesopieza'] = empty($_POST['pesopieza']) == TRUE ? 0 : $_POST['pesopieza'];
    $aunit = 0;

	if ($_POST['tipoMedida'] == 4) {
		$aunit = getUnidadRef($_POST['comboLongitud']);
	}
	else if ($_POST['tipoMedida'] == 6) {
		$aunit = getUnidadRef($_POST['comboLongitud']);
	}
	else if ($_POST['tipoMedida'] == 3) {
		$aunit = getUnidadRef($_POST['comboPeso']);
	}
	else if ($_POST['tipoMedida'] == 1) {
		$aunit = 7;
	}
	else if ($_POST['tipoMedida'] == 2) {
		$aunit = 8;
	}

    if ($_POST['pesopieza'] > 0)
		$_POST['Peso'] = $_POST['pesopieza'];
	
    $mat = new Material();
    $mat->llenaDatos(-1, $_POST['nombre'], $_POST['clave'], $_POST['descripcion'], $_POST['tipoMedida'], $_POST['medida'], $_POST['idCategoria'], $_POST['Largo'], $_POST['Ancho'], $_POST['alto'], $_POST['Peso'], $aunit,$_POST['pesoespecifico']);
    $mat->inserta();
}
//baja
else if ($accion == 'baja') {
    $mat = new Material();
    return $mat->baja($_POST['id']);
}
//editar
else if($accion == 'editar') {
    $_POST['nombre'] = empty($_POST['nombre']) == TRUE ? '' : $_POST['nombre'];
    $_POST['clave'] = empty($_POST['clave']) == TRUE ? 0 : $_POST['clave'];
    $_POST['descripcion'] = empty($_POST['descripcion']) == TRUE ? '' : $_POST['descripcion'];
    $_POST['tipoMedida'] = empty($_POST['tipoMedida']) == TRUE ? '' : $_POST['tipoMedida'];
    $_POST['medida'] = empty($_POST['medida']) == TRUE ? '' : $_POST['medida'];
    $_POST['idCategoria'] = empty($_POST['idCategoria']) == TRUE ? '' : $_POST['idCategoria'];
	$_POST['Largo'] = empty($_POST['Largo']) == TRUE ? 0 : $_POST['Largo'];
	$_POST['Ancho'] = empty($_POST['Ancho']) == TRUE ? 0 : $_POST['Ancho'];
	$_POST['alto'] = empty($_POST['alto']) == TRUE ? 0 : $_POST['alto'];
	$_POST['Peso'] = empty($_POST['peso']) == TRUE ? 0 : $_POST['Peso'];
    $_POST['comboLongitud'] = empty($_POST['comboLongitud']) == TRUE ? 0 : $_POST['comboLongitud'];
	$_POST['comboPeso'] = empty($_POST['comboPeso']) == TRUE ? 0 : $_POST['comboPeso'];
	$_POST['pesoespecifico'] = empty($_POST['pesoespecifico']) == TRUE ? 0 : $_POST['pesoespecifico'];
	$_POST['pesopieza'] = empty($_POST['pesopieza']) == TRUE ? 0 : $_POST['pesopieza'];
    $aunit = 0;

	if ($_POST['tipoMedida'] == 4) {
		$aunit = getUnidadRef($_POST['comboLongitud']);
	}
	else if ($_POST['tipoMedida'] == 6) {
		$aunit = getUnidadRef($_POST['comboLongitud']);
	}
	else if ($_POST['tipoMedida'] == 3) {
		$aunit = getUnidadRef($_POST['comboPeso']);
	}
	else if ($_POST['tipoMedida'] == 1) {
		$aunit = 7;
	}
	else if ($_POST['tipoMedida'] == 2) {
		$aunit = 8;
	}

	if ($_POST['pesopieza'] > 0)
		$_POST['Peso'] = $_POST['pesopieza'];

    $mat = new Material();
    $mat->llenaDatos($_POST['id'], $_POST['nombre'], $_POST['clave'], $_POST['descripcion'], $_POST['tipoMedida'], $_POST['medida'], $_POST['idCategoria'], $_POST['Largo'], $_POST['Ancho'], $_POST['alto'], $_POST['Peso'], $aunit, $_POST['pesoespecifico']);
    $mat->editar();
}
else if ($accion == 'getMedidas') {
    $medidaTMP = new Medida();
    $medidaTMP->getMedidas();
}
else if ($accion == 'getCategorias') {
    $categ = new Categoria();
    $categ -> getCategorias();
}
else if ($accion == 'getJsonMedidas') {
    $id = $_POST['idMedida'];
    $medidaTMP = new Medida();
    $medidaTMP->getJsonMedidas($id);
}
else if ($accion == 'guardarPrecio') {
    $historico = new HistoricoPrecioMateriales();
    $historico -> llenaDatos(-1, $_POST['idMaterial'], $_POST['idProveedor'], $_POST['precio'], $_POST['cotizador']);
    $historico -> guardarPrecio();
}
else if ($accion == 'autocompleteCategorias') {
    if (!isset($_POST['searchTerm']))
        $search = null;
    else
        $search = $_POST['searchTerm'];
    
    $categoria = new Categoria();
    $categoria->getCategoriaFilter($search);
}