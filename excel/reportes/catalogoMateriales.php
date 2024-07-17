<?php
include_once '../../clases/conexion.php';
include_once '../../clases/reporteExcel.php';
require "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

//$total = $_GET['total'];
$hoyf = date('_Y_m_d');

$conexion = new Conexion();
$conexion->abrirBD();

$headerStyle = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ]
];

$styleArray = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
    'borders' => [
        'top' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
        ],
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startColor' => [
            'argb' => 'FFA0A0A0',
        ],
        'endColor' => [
            'argb' => 'FFFFFFFF',
        ],
    ],
];

$documento = new Spreadsheet();
$documento
    ->getProperties()
    ->setCreator("CM")
    ->setLastModifiedBy('CM') // última vez modificado por
    ->setTitle('Catalogo de materiales');

$nombreDelDocumento = "CatalogoMateriales".$hoyf.".xlsx";

$hoja = $documento->getActiveSheet();
$hoja->setTitle("Catalogo de materiales");
//----------------Datos de prueba
$reporte = new ReporteExcel();

$datos = $reporte->getReporteCatalogoMateriales();
//VistaMaterialMedidaCategoria

$hoja->setCellValueByColumnAndRow(1, 1, "ID");
$hoja->setCellValueByColumnAndRow(2, 1, "NOMBRE");
$hoja->setCellValueByColumnAndRow(3, 1, "DESCRIPCION");
$hoja->setCellValueByColumnAndRow(4, 1, "IDMEDIDA");
$hoja->setCellValueByColumnAndRow(5, 1, "MEDIDA");
$hoja->setCellValueByColumnAndRow(6, 1, "IDCATEGORIA");
$hoja->setCellValueByColumnAndRow(7, 1, "CATEGORIA");
$hoja->setCellValueByColumnAndRow(8, 1, "LARGO");
$hoja->setCellValueByColumnAndRow(9, 1, "ANCHO");
$hoja->setCellValueByColumnAndRow(10, 1, "ALTO");
$hoja->setCellValueByColumnAndRow(11, 1, "PESO");
$hoja->setCellValueByColumnAndRow(12, 1, "UNIDAD");
$hoja->setCellValueByColumnAndRow(13, 1, "PESOESPECIFICO");
$documento->getActiveSheet()->getStyle('A1:M1')->applyFromArray($styleArray);

foreach(range('A','M') as $columnID) {
    if($columnID == 'A')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'B')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(45);
    else if($columnID == 'C')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(30);
    else if($columnID == 'D')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'E')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(50);
    else if($columnID == 'F')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'G')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(25);
    else if($columnID == 'H')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'I')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'J')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'K')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'L')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(7);
    else if($columnID == 'M')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(15);
    else
        $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}

$longitud = count($datos);
$procant = null;
$facant = null;
//var_dump($datos);
//Recorro todos los elementos
for($i = 0; $i < $longitud; $i++) {
    $hoja->setCellValueByColumnAndRow(1, $i + 2, $datos[$i]["IdMaterial"]);
    $hoja->setCellValueByColumnAndRow(2, $i + 2, $datos[$i]["Nombre"]);
    $hoja->setCellValueByColumnAndRow(3, $i + 2, $datos[$i]["Descripcion"]);
    $hoja->setCellValueByColumnAndRow(4, $i + 2, $datos[$i]["IdMedida"]);
    $hoja->setCellValueByColumnAndRow(6, $i + 2, $datos[$i]["IdCategoria"]);
    $hoja->setCellValueByColumnAndRow(7, $i + 2, $datos[$i]["CategoriaNombre"]);
    $hoja->setCellValueByColumnAndRow(8, $i + 2, $datos[$i]["Largo"]);
    $hoja->setCellValueByColumnAndRow(9, $i + 2, $datos[$i]["Ancho"]);
    $hoja->setCellValueByColumnAndRow(10, $i + 2, $datos[$i]["Alto"]);
    $hoja->setCellValueByColumnAndRow(11, $i + 2, $datos[$i]["Peso"]);
    $hoja->setCellValueByColumnAndRow(12, $i + 2, $datos[$i]["Unidad"]);
    $hoja->setCellValueByColumnAndRow(13, $i + 2, $datos[$i]["PesoEspecifico"]);
    $html='';
    //$html = "Tipo: ". $datos[$i]["MedidaNombre"] .". ";
    $unidad = 'pz';
    if ($datos[$i]["Unidad"] == 1)
        $unidad = 'm';
    else if ($datos[$i]["Unidad"] == 2)
        $unidad = 'cm';
    else if ($datos[$i]["Unidad"] == 3)
        $unidad = 'in';
    else if ($datos[$i]["Unidad"] == 4)
        $unidad = 'ft';
    else if ($datos[$i]["Unidad"] == 5)
        $unidad = 'gm';
    else if ($datos[$i]["Unidad"] == 6)
        $unidad = 'kg';
    else if ($datos[$i]["Unidad"] == 7)
        $unidad = 'm3';
    else if ($datos[$i]["Unidad"] == 8)
        $unidad = 'lt';

    if ($datos[$i]["Medida"] != "") {
        $dato = json_decode($datos[$i]["Medida"]);
        $html .= '[';
        //var_dump($dato);
        foreach ($dato as $key => $value) {
            if($html !== '[')
                $html .= ',';
            //var_dump($value->nombre);
            $snombrelado = $value->nombre;
            if ($snombrelado == 'Alto') {
                $snombrelado = 'Calibre';
                $unidad = 'mm';
            }

            $html .= '{"nombre":"'. $snombrelado .'","valor":"'. $value->valor . '","unidad":"' . $unidad .'"}';
        }
    }
    $html .= "]";
    //var_dump($key);
    $hoja->setCellValueByColumnAndRow(5, $i + 2, $html);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;