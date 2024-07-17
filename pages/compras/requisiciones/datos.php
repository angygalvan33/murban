<?php
include_once '../../../clases/requisicion.php';

if (isset($_POST['accion'])) {
    if ($_POST['accion'] == 'resetRequisiciones') {
        $req = new Requisicion();
        $req->resetRequsicionesCheck($_POST['tipoRequisicion']);
    }
}