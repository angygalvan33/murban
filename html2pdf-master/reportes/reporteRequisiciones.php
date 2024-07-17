<?php
/**
 * Html2Pdf Library - example
 *
 * HTML => PDF converter
 * distributed under the OSL-3.0 License
 *
 * @package   Html2pdf
 * @author    Laurent MINGUET <webmaster@html2pdf.fr>
 * @copyright 2017 Laurent MINGUET
 */

date_default_timezone_set('America/Mexico_City');
require_once dirname(__FILE__).'/../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

try {
    ob_start();
    if($_GET['id']!="-2")
    {
    include dirname(__FILE__).'/res/requisiciones.php';
    }
    
    else
    {
        include dirname(__FILE__).'/res/requisiciones2.php';
    }
    $content = ob_get_clean();

    $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', 3);
    $html2pdf->pdf->SetDisplayMode('fullpage');
    $html2pdf->writeHTML($content);
    
    if($_GET['id']!="-2")
    {
    $html2pdf->output('Requisiciones_'.$_GET['id'] . '.pdf');
    }
    
    else
    {
        $html2pdf->output('Requisiciones'. '.pdf');
    }
} catch (Html2PdfException $e) {
    $html2pdf->clean();

    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}
