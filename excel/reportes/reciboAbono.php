<?php
include_once '../../clases/conexion.php';
include_once '../../clases/reporteExcel.php';
include_once '../../clases/conversion.php';
require "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$idCajaChicaDetalle = $_GET['idCajaChicaDetalle'];
$conversion = new Conversion();

$conexion = new Conexion();
$conexion->abrirBD();
$query = "SELECT * FROM DatosEmpresa WHERE IdDatosEmpresa = 1";
$result = mysqli_query($conexion->mysqli, $query);
$result = mysqli_fetch_assoc($result);

$query_ = "SELECT * FROM VistaCajaChicaUsuario WHERE IdCajaChicaAbonos = ". $idCajaChicaDetalle;
$datos = mysqli_query($conexion->mysqli, $query_);
$datos = mysqli_fetch_assoc($datos);

$cantidad = $datos['Total'];
$folioFactura = $datos['FolioFactura'];
$fecha = $datos['Creado'];
$usuario = $datos['Usuario'];
$obra = $datos['Obra'];
$material = $datos['Material'];
$proveedor = $datos['Proveedor'];
$cantidad_letra = $conversion->convertirNumeroLetra($cantidad);

$conexion->cerrarBD();

$titleStyle = [
    'font' => [
        'bold' => true,
        'color' => [
            'rgb' => '000066'
        ],
        'size' => 20,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
];

$headerStyle = [
    'font' => [
        'bold' => true,
        'size' => 12,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ],
    /*'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startColor' => [
            'argb' => 'FFA0A0A0',
        ],
        'endColor' => [
            'argb' => 'FFFFFFFF',
        ],
    ],*/
];

$documento = new Spreadsheet();
$documento->getProperties()
    ->setCreator("CxC")
    ->setLastModifiedBy('CxC') // última vez modificado por
    ->setTitle('Recibo Caja Chica');

$nombreDelDocumento = "ReciboCajaChica.xlsx";
$files = glob('../../images/logo_reporte.png');

if ($files[0] != null) {
  $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Logo');
  $drawing->setDescription('Logo');
  $drawing->setPath($files[0]);
  $drawing->setHeight(100);
  $drawing->setCoordinates('A1');
  $drawing->setWorksheet($documento->getActiveSheet());
}

$hoja = $documento->getActiveSheet();
$hoja->setTitle("Recibo");

$hoja->setCellValueByColumnAndRow(5, 6, "RECIBO DE PAGO");
$hoja->setCellValueByColumnAndRow(9, 2, "FOLIO");
$hoja->setCellValueByColumnAndRow(9, 3, $folioFactura);
$hoja->setCellValueByColumnAndRow(9, 4, "SERIE");
$hoja->setCellValueByColumnAndRow(9, 6, "FECHA");
$hoja->setCellValueByColumnAndRow(9, 7, date("d-m-Y", strtotime($fecha)));

$hoja->setCellValueByColumnAndRow(2, 8, "Recibí de: ");
$hoja->setCellValueByColumnAndRow(4, 8, $usuario);

$hoja->setCellValueByColumnAndRow(2, 9, "Cantidad: ");
$hoja->setCellValueByColumnAndRow(4, 9, "$". number_format($datos["Total"], 2));

//$hoja->setCellValueByColumnAndRow(7, 9, "Cantidad: ");
$hoja->setCellValueByColumnAndRow(5, 9, $cantidad_letra );

$hoja->setCellValueByColumnAndRow(2, 10, "Proyecto: ");
$hoja->setCellValueByColumnAndRow(4, 10, $obra);

$hoja->setCellValueByColumnAndRow(2, 11, "Concepto");
$hoja->setCellValueByColumnAndRow(4, 11, $material);
$hoja->setCellValueByColumnAndRow(4, 12, $proveedor);

$hoja->setCellValueByColumnAndRow(4, 14, "RECIBIDO POR: ");
$hoja->setCellValueByColumnAndRow(4, 16, "_______________________________________");

if ($files[0] != null) {
  $drawing = new PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
  $drawing->setName('Logo');
  $drawing->setDescription('Logo');
  $drawing->setPath($files[0]);
  $drawing->setHeight(100);
  $drawing->setCoordinates('A19');
  $drawing->setWorksheet($documento->getActiveSheet());
}

$hoja->setCellValueByColumnAndRow(5, 24, "RECIBO DE PAGO");
$hoja->setCellValueByColumnAndRow(9, 20, "FOLIO");
$hoja->setCellValueByColumnAndRow(9, 21, $folioFactura);
$hoja->setCellValueByColumnAndRow(9, 22, "SERIE");
$hoja->setCellValueByColumnAndRow(9, 24, "FECHA");
$hoja->setCellValueByColumnAndRow(9, 25, date("d-m-Y", strtotime($fecha)));

$hoja->setCellValueByColumnAndRow(2, 26, "Recibí de: ");
$hoja->setCellValueByColumnAndRow(4, 26, $usuario);

$hoja->setCellValueByColumnAndRow(2, 27, "Cantidad: ");
$hoja->setCellValueByColumnAndRow(4, 27, "$". number_format($datos["Total"], 2));

//$hoja->setCellValueByColumnAndRow(7, 9, "Cantidad: ");
$hoja->setCellValueByColumnAndRow(5, 27, $cantidad_letra );

$hoja->setCellValueByColumnAndRow(2, 28, "Proyecto: ");
$hoja->setCellValueByColumnAndRow(4, 28, $obra);

$hoja->setCellValueByColumnAndRow(2, 29, "Concepto");
$hoja->setCellValueByColumnAndRow(4, 29, $material);
$hoja->setCellValueByColumnAndRow(4, 30, $proveedor);

$hoja->setCellValueByColumnAndRow(4, 32, "RECIBIDO POR: ");
$hoja->setCellValueByColumnAndRow(4, 34, "_______________________________________");

$hoja->mergeCells('E24:H25');
$documento->getActiveSheet()->getStyle('E24:H25')->applyFromArray($titleStyle);
$documento->getActiveSheet()->getStyle('I20:I20')->applyFromArray($headerStyle);
$documento->getActiveSheet()->getStyle('I22:I22')->applyFromArray($headerStyle);
$documento->getActiveSheet()->getStyle('I24:I24')->applyFromArray($headerStyle);
$documento->getActiveSheet()->getStyle('B26:B29')->applyFromArray($headerStyle);
$documento->getActiveSheet()->getStyle('D31:D32')->applyFromArray($headerStyle);

$hoja->mergeCells('E6:H7');
$documento->getActiveSheet()->getStyle('E6:H7')->applyFromArray($titleStyle);
$documento->getActiveSheet()->getStyle('I2:I2')->applyFromArray($headerStyle);
$documento->getActiveSheet()->getStyle('I4:I4')->applyFromArray($headerStyle);
$documento->getActiveSheet()->getStyle('I6:I6')->applyFromArray($headerStyle);
$documento->getActiveSheet()->getStyle('B8:B11')->applyFromArray($headerStyle);
$documento->getActiveSheet()->getStyle('D13:D14')->applyFromArray($headerStyle);

foreach (range('A', 'J') as $columnID) {
    if ($columnID == 'A')
        $documento->getActiveSheet()->getColumnDimension($columnID)->setWidth(5);
    else if ($columnID == 'B')
        $documento->getActiveSheet()->getColumnDimension($columnID)->setWidth(9.89);
    else if ($columnID == 'C')
        $documento->getActiveSheet()->getColumnDimension($columnID)->setWidth(5);
    else if ($columnID == 'D')
        $documento->getActiveSheet()->getColumnDimension($columnID)->setWidth(9.89);
    else if ($columnID == 'E')
        $documento->getActiveSheet()->getColumnDimension($columnID)->setWidth(9.89);
    else if ($columnID == 'F')
        $documento->getActiveSheet()->getColumnDimension($columnID)->setWidth(9.89);
    else if ($columnID == 'G')
        $documento->getActiveSheet()->getColumnDimension($columnID)->setWidth(9.89);
    else if ($columnID == 'H')
        $documento->getActiveSheet()->getColumnDimension($columnID)->setWidth(9.89);
    else if ($columnID == 'I')
       $documento->getActiveSheet()->getColumnDimension($columnID)->setWidth(9.89);
    else if ($columnID == 'J')
       $documento->getActiveSheet()->getColumnDimension($columnID)->setWidth(9.89);
    else
        $documento->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'. $nombreDelDocumento .'"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;