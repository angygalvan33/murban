<?php
include_once '../../../clases/material.php';

if (!isset($_POST['searchTerm'])) {
    $material = new Material();
    echo $material->getMaterialesFilter(null);
}
else {
    $search = $_POST['searchTerm'];
    $material = new Material();
    echo $material->getMaterialesFilter($search);
}