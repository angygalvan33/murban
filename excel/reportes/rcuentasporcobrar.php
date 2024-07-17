<?php

include_once '../../clases/conexion.php';
include_once '../../clases/reporteExcel.php';
require "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


$tipo = $_GET["tipo"];

/*
$fechaIni = $_GET["fIni"];
$fechaFin = $_GET["fFin"];
*/
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
    ->setTitle('Cuentas por Cobrar');
 
$nombreDelDocumento = "CxC.xlsx";


$files = glob('../../images/logo/*.*');
if($files[0] != null)
{
  $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Logo');
  $drawing->setDescription('Logo');
  //$drawing->setPath('../../images/logo/logo_empresa_05-Dec-2018-15-10-52.png');
  $drawing->setPath($files[0]);
  $drawing->setHeight(100);
  $drawing->setCoordinates('A1');
  $drawing->setWorksheet($documento->getActiveSheet());
}

$hoja = $documento->getActiveSheet();
$hoja->setTitle("Cuentas por Cobrar");

$hoja->mergeCells('C1:H1');
$documento->getActiveSheet()->getStyle('B1:H1')->applyFromArray($headerStyle);
$hoja->mergeCells('C2:H2');
$documento->getActiveSheet()->getStyle('B2:H2')->applyFromArray($headerStyle);
$hoja->mergeCells('C3:H3');
$documento->getActiveSheet()->getStyle('B3:H3')->applyFromArray($headerStyle);
$hoja->mergeCells('C4:H4');
$documento->getActiveSheet()->getStyle('B4:H4')->applyFromArray($headerStyle);
$hoja->mergeCells('C5:H5');
$documento->getActiveSheet()->getStyle('B5:H5')->applyFromArray($headerStyle);

$hoja->setCellValueByColumnAndRow(2, 1, urldecode($result['Nombre']));
$hoja->setCellValueByColumnAndRow(2, 2, 'RFC: ' . utf8_decode($result['RFC']));
$hoja->setCellValueByColumnAndRow(2, 3, urldecode('Dirección').': ' . urldecode($result['Direccion']));
$hoja->setCellValueByColumnAndRow(2, 4, urldecode('Teléfono').': ' . utf8_decode($result['Telefono']).'   e-mail: ' . $result['Email']);
$hoja->setCellValueByColumnAndRow(2, 5, urldecode('Representante').': ' . urldecode($result['Representante']));

$hoja->mergeCells('A6:H6');


//----------------Datos de prueba
$reporte = new ReporteExcel();

if($tipo == 1)
{
    $datos = $reporte->getCuentasxCobrarGeneral();
}

      
$hoja->setCellValueByColumnAndRow(1, 7, "PROYECTO");
$hoja->setCellValueByColumnAndRow(2, 7, "CLIENTE");
$hoja->setCellValueByColumnAndRow(3, 7, "FOLIO OC");
$hoja->setCellValueByColumnAndRow(4, 7, "MONTO OC");
$hoja->setCellValueByColumnAndRow(5, 7, "DIAS DE CRED.");
$hoja->setCellValueByColumnAndRow(6, 7, "DIAS RESTANTES");
$hoja->setCellValueByColumnAndRow(7, 7, "FACTURA");
$hoja->setCellValueByColumnAndRow(8, 7, "MONTO FACTURA");
$hoja->setCellValueByColumnAndRow(9, 7, "FECHA FACTURA");
$hoja->setCellValueByColumnAndRow(10, 7, "RESTA");
$documento->getActiveSheet()->getStyle('A7:J7')->applyFromArray($styleArray);

foreach(range('A','J') as $columnID) {
    if($columnID == 'A')
	   $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(50);
	else if($columnID == 'B')
	   $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(50);
	else if($columnID == 'E')
	   $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setWidth(13);
	else if($columnID == 'F')
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
    $hoja->setCellValueByColumnAndRow(1, $i+8, $datos[$i]["Proyecto"]);
    $hoja->setCellValueByColumnAndRow(2, $i+8, $datos[$i]["Cliente"]);
    $hoja->setCellValueByColumnAndRow(3, $i+8, $datos[$i]["OCFolio"]);
    $hoja->setCellValueByColumnAndRow(4, $i+8, $datos[$i]["OCMonto"]);
    $hoja->setCellValueByColumnAndRow(5, $i+8, $datos[$i]["DiasCredito"]);
	$hoja->setCellValueByColumnAndRow(6, $i+8, $datos[$i]["DiasCreditoRestantes"]);	
    $hoja->setCellValueByColumnAndRow(7, $i+8, $datos[$i]["FacturaNumero"]);
    $hoja->setCellValueByColumnAndRow(8, $i+8, $datos[$i]["FacturaValor"]);
    $hoja->setCellValueByColumnAndRow(9, $i+8, $datos[$i]["FacturaFecha"]);
	$hoja->setCellValueByColumnAndRow(10,$i+8, $datos[$i]["CobroRestante"]);
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




