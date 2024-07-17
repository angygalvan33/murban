<?php

include_once '../../clases/conexion.php';
include_once '../../clases/reporteExcel.php';
require "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$fechaIni = $_GET["fIni"];
$fechaFin = $_GET["fFin"];

$conexion = new Conexion();
$conexion->abrirBD();
$query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
$result = mysqli_query($conexion->mysqli, $query);
$result = mysqli_fetch_assoc($result);
$conexion->cerrarBD();


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
    ->setCreator("CxC")
    ->setLastModifiedBy('CxC') // última vez modificado por
    ->setTitle('RequisicionesxFecha');
 
$nombreDelDocumento = "RequisxFecha.xlsx";
$files = glob('../../images/logo/*.*');
if($files[0] != null)
{
  $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Logo');
  $drawing->setDescription('Logo');
  $drawing->setPath($files[0]);
  $drawing->setHeight(100);
  $drawing->setCoordinates('A1');
  $drawing->setWorksheet($documento->getActiveSheet());
}

$hoja = $documento->getActiveSheet();
$hoja->setTitle("RequisicionesxFecha");

$hoja->mergeCells('D1:F1');
$documento->getActiveSheet()->getStyle('C1:F1')->applyFromArray($headerStyle);
$hoja->mergeCells('D2:F2');
$documento->getActiveSheet()->getStyle('C2:F2')->applyFromArray($headerStyle);
$hoja->mergeCells('D3:F3');
$documento->getActiveSheet()->getStyle('C3:F3')->applyFromArray($headerStyle);
$hoja->mergeCells('D4:F4');
$documento->getActiveSheet()->getStyle('C4:F4')->applyFromArray($headerStyle);
$hoja->mergeCells('D5:F5');
$documento->getActiveSheet()->getStyle('C5:F5')->applyFromArray($headerStyle);

//$hoja->setCellValueByColumnAndRow(3, 2, 'RFC: ' . utf8_decode($result['RFC']));
//$hoja->setCellValueByColumnAndRow(3, 3, urldecode('Dirección').': ' . urldecode($result['Direccion']));
//$hoja->setCellValueByColumnAndRow(3, 4, urldecode('Teléfono').': ' . utf8_decode($result['Telefono']).'   e-mail: ' . $result['Email']);
//$hoja->setCellValueByColumnAndRow(3, 5, urldecode('Representante').': ' . urldecode($result['Representante']));
if($fechaIni != -1)
    $hoja->setCellValueByColumnAndRow(3, 2, urldecode('Fecha Inicio:').': ' .  $fechaIni . ' Fecha Fin: ' . $fechaFin);
$hoja->mergeCells('A6:H6');


//----------------Datos de prueba
$reporte = new ReporteExcel();

$datos = $reporte->getRequisicionesxFecha($fechaIni, $fechaFin);

      
$hoja->setCellValueByColumnAndRow(1, 7, "FOLIO");
$hoja->setCellValueByColumnAndRow(2, 7, "FECHA DE CREACIÓN");
$hoja->setCellValueByColumnAndRow(3, 7, "FECHA REQUERIDA");
$hoja->setCellValueByColumnAndRow(4, 7, "USUARIO");
$hoja->setCellValueByColumnAndRow(5, 7, "PROYECTO");
$hoja->setCellValueByColumnAndRow(6, 7, "MATERIAL");
$hoja->setCellValueByColumnAndRow(7, 7, "ATENDIDO");
$hoja->setCellValueByColumnAndRow(8, 7, "REQUERIDO");

$documento->getActiveSheet()->getStyle('A7:H7')->applyFromArray($styleArray);

foreach(range('A','H') as $columnID) {
    if($columnID == 'A')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(10);
    else if($columnID == 'B')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else if($columnID == 'C')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(20);
    else if($columnID == 'D')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(25);
    else if($columnID == 'E')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(30);
    else if($columnID == 'F')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(50);
    else if($columnID == 'G')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(15);
    else if($columnID == 'H')
       $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(15);
    else        
        $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}

$longitud = count($datos);
 
//var_dump($datos);
//Recorro todos los elementos
for($i=0; $i<$longitud; $i++)
{
    $hoja->setCellValueByColumnAndRow(1, $i+8, $datos[$i]["IdRequisicion"]);
    $hoja->setCellValueByColumnAndRow(2, $i+8, $datos[$i]["Fecha"]);
    $hoja->setCellValueByColumnAndRow(3, $i+8, $datos[$i]["FechaReq"]);
    $hoja->setCellValueByColumnAndRow(4, $i+8, $datos[$i]["Usuario"]);
    $hoja->setCellValueByColumnAndRow(5, $i+8, $datos[$i]["Proyecto"]);
    $hoja->setCellValueByColumnAndRow(6, $i+8, $datos[$i]["Material"]);
    $hoja->setCellValueByColumnAndRow(7, $i+8, $datos[$i]["CantidadA"]);
    $hoja->setCellValueByColumnAndRow(8, $i+8, $datos[$i]["CantidadR"]); 

}
   


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');


$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');

/*
$writer = IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
*/

exit;




