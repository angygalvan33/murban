<?php

include_once '../../clases/conexion.php';
include_once '../../clases/reporteExcel.php';
require "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$mP = $_GET["metodosPago"];
$mC = $_GET["metodosCobro"];
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
    ->setCreator("Cxp")
    ->setLastModifiedBy('CxP') // última vez modificado por
    ->setTitle('Cuentas por Pagar');
 
$nombreDelDocumento = "CxP.xlsx";

$drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo');
$drawing->setPath('../../images/logo/logo_empresa_05-Dec-2018-15-10-52.png');
$drawing->setHeight(100);
$drawing->setCoordinates('B1');
$drawing->setWorksheet($documento->getActiveSheet());

$hoja = $documento->getActiveSheet();
$hoja->setTitle("Cuentas por Pagar");

$hoja->mergeCells('C1:H1');
$documento->getActiveSheet()->getStyle('C1:H1')->applyFromArray($headerStyle);
$hoja->mergeCells('C2:H2');
$documento->getActiveSheet()->getStyle('C2:H2')->applyFromArray($headerStyle);
$hoja->mergeCells('C3:H3');
$documento->getActiveSheet()->getStyle('C3:H3')->applyFromArray($headerStyle);
$hoja->mergeCells('C4:H4');
$documento->getActiveSheet()->getStyle('C4:H4')->applyFromArray($headerStyle);
$hoja->mergeCells('C5:H5');
$documento->getActiveSheet()->getStyle('C5:H5')->applyFromArray($headerStyle);

$hoja->setCellValueByColumnAndRow(3, 1, urldecode($result['Nombre']));
$hoja->setCellValueByColumnAndRow(3, 2, 'RFC: ' . utf8_decode($result['RFC']));
$hoja->setCellValueByColumnAndRow(3, 3, urldecode('Dirección').': ' . urldecode($result['Direccion']));
$hoja->setCellValueByColumnAndRow(3, 4, urldecode('Teléfono').': ' . utf8_decode($result['Telefono']).'   e-mail: ' . $result['Email']);
$hoja->setCellValueByColumnAndRow(3, 5, urldecode('Representante').': ' . urldecode($result['Representante']));

$hoja->mergeCells('A6:H6');

//----------------Datos de prueba
$reporte = new ReporteExcel();

if($mC!='' && $mP=='')
{
    $datos = $reporte->ReporteMetodoCobro($mC, $fechaIni, $fechaFin);
}
else if($mP!='' && $mC=='')
{
    $datos = $reporte->ReporteMetodoPago($mP, $fechaIni, $fechaFin);
}
else if($mP!='' && $mC!='')
{
    $datos = $reporte->ReporteMetodoPagoCobro($mP, $mC,$fechaIni, $fechaFin);
}
//var_dump($datos);
/*$datos[] = array( "Fecha" => "20/09/2019",
                "MetodoNombre" => "Transferencia HSBC",
                "NombreProveedorProyecto" => "Secretaría de Hacienda",
                "Concepto" => "Pendiente",
                "FolioFactura" => "1653168",
                "Ingreso" => 1350.36,
                "Egreso" => 0
        );

$datos[] = array( "Fecha" => "21/09/2019",
                "MetodoNombre" => "Caja Chica",
                "NombreProveedorProyecto" => "Juanito",
                "Concepto" => "Nada mas porque si",
                "FolioFactura" => "54618146",
                "Ingreso" => 0,
                "Egreso" => 8025698.25
        );
        
        var_dump($datos);*/
//-------------------------------------------------------

      
$hoja->setCellValueByColumnAndRow(1, 7, "FECHA");
$hoja->setCellValueByColumnAndRow(2, 7, "FORMA DE PAGO");
$hoja->setCellValueByColumnAndRow(3, 7, "CENTRO DE COSTOS");
$hoja->setCellValueByColumnAndRow(4, 7, "PROVEEDOR/BENEFICIARIO");
$hoja->setCellValueByColumnAndRow(5, 7, "CONCEPTO");
$hoja->setCellValueByColumnAndRow(6, 7, "FACTURA");
$hoja->setCellValueByColumnAndRow(7, 7, "INGRESO");
$hoja->setCellValueByColumnAndRow(8, 7, "EGRESO");
$documento->getActiveSheet()->getStyle('A7:H7')->applyFromArray($styleArray);

foreach(range('A','H') as $columnID) {
    $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}

$longitud = count($datos);
 
//var_dump($datos);
//Recorro todos los elementos
for($i=0; $i<$longitud; $i++)
{
    $hoja->setCellValueByColumnAndRow(1, $i+8, $datos[$i]["Fecha"]);
    $hoja->setCellValueByColumnAndRow(2, $i+8, $datos[$i]["MetodoNombre"]);
    $hoja->setCellValueByColumnAndRow(3, $i+8, $datos[$i]["CentroCostos"]);
    $hoja->setCellValueByColumnAndRow(4, $i+8, $datos[$i]["NombreProveedorProyecto"]);
    $hoja->setCellValueByColumnAndRow(5, $i+8, $datos[$i]["Concepto"]);
    $hoja->setCellValueByColumnAndRow(6, $i+8, $datos[$i]["FolioFactura"]);
    $hoja->setCellValueByColumnAndRow(7, $i+8, $datos[$i]["Ingreso"]);
    $hoja->setCellValueByColumnAndRow(8, $i+8, $datos[$i]["Egreso"]);
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




