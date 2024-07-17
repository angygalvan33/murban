<?php
include_once '../../clases/panelAdmin.php';

$accion = $_POST['accion'];
//guardar
if ($accion == 'guardarDatos') {
    $pa = new PanelAdmin();
    $pa->actualizaDatosEmpresa($_POST['nombreEmpresa'], $_POST['direccionEmpresa'], $_POST['municipioEmpresa'], $_POST['edoEmpresa'], $_POST['telefonoEmpresa'], $_POST['representanteEmpresa'], $_POST['rfcEmpresa'], $_POST['emailEmpresa'], $_POST['maximoSinAutorizacionEmpresa']);
}
//lee los datos de la empresa para mpstrar al inicio
else if ($accion == 'leerDatos') {
    $pa = new PanelAdmin();
    echo $pa->leerDatosEmpresa();
}
//cambia Imagen de Logo
else if ($accion == 'cambiaLogo') {
    if (!empty ($_FILES)) {
        $archivo = $_FILES['Logo'];
        $ext = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        
        if ($ext != "png" && $ext != "jpg" && $ext != "jpeg") {
            return false;
        }
        
        $files = glob('../../images/logo/*'); //get all file names
        foreach ($files as $file) { //iterate files
            if (is_file($file))
                unlink($file); //delete file
       }
       
       $name = "logo_empresa_". date("d-M-Y-H-i-s") .".". $ext;
       file_put_contents("../../images/logo/". $name, file_get_contents($archivo['tmp_name']));
       echo $name;
   }
   return false;
}
else if ($accion == 'obtenerLogo') {
    $result = false;
    $files = glob('../../images/logo/*');
    $result = $files[0];
    echo $result;
}
else if($accion == 'guardarPoliticasCompras') {
    $pa = new PanelAdmin();
    $pa->actualizaPoliticasCompra($_POST['politicas']);
}
//lee los datos de la empresa para mpstrar al inicio
else if ($accion == 'leerPoliticasCompras') {
    $pa = new PanelAdmin();
    echo $pa->leerPoliticasCompra();
}