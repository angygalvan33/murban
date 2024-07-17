<?php

include_once '../../clases/proveedor.php';
require "../vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
 

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
    ->setTitle('Proveedores');
 
$nombreDelDocumento = "Proveedores.xlsx";

$hoja = $documento->getActiveSheet();
$hoja->setTitle("Proveedores");


$proveedor = new Proveedor();
$proveedores = $proveedor->listadoProveedoresNoJSON();

$hoja->setCellValueByColumnAndRow(1, 1, "Nombre");
$hoja->setCellValueByColumnAndRow(2, 1, "Dirección");
$hoja->setCellValueByColumnAndRow(3, 1, "Teléfono");
$hoja->setCellValueByColumnAndRow(4, 1, "Representante");
$hoja->setCellValueByColumnAndRow(5, 1, "Email");
$hoja->setCellValueByColumnAndRow(6, 1, "RFC");
$documento->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);

foreach(range('A','F') as $columnID) {
    $documento->getActiveSheet()->getColumnDimension($columnID)
        ->setAutoSize(true);
}
//var_dump($proveedores);

$longitud = count($proveedores);
 
//Recorro todos los elementos
for($i=0; $i<$longitud; $i++)
{
    $hoja->setCellValueByColumnAndRow(1, $i+2, $proveedores[$i]["Nombre"]);
    $hoja->setCellValueByColumnAndRow(2, $i+2, $proveedores[$i]["Direccion"]);
    $hoja->setCellValueByColumnAndRow(3, $i+2, $proveedores[$i]["Telefono"]);
    $hoja->setCellValueByColumnAndRow(4, $i+2, $proveedores[$i]["Representante"]);
    $hoja->setCellValueByColumnAndRow(5, $i+2, $proveedores[$i]["Email"]);
    $hoja->setCellValueByColumnAndRow(6, $i+2, $proveedores[$i]["Rfc"]);
}
      
/**
 * Los siguientes encabezados son necesarios para que
 * el navegador entienda que no le estamos mandando
 * simple HTML
 * Por cierto: no hagas ningún echo ni cosas de esas; es decir, no imprimas nada
 */
 
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
header('Cache-Control: max-age=0');
 
$writer = IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;