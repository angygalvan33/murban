<?php
$to = "167.rafa@gmail.com";
$subject = "Bienvenido al sistema";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

$message = "
<html>
    <head>
        <title>Bienvenido</title>
    </head>
    <body>
        <div>
            <div style='float: left;'><img src='../images/logo_empresa.png' alt='' width='85%'></div>
            <div>
                <table>
                    <tr><td colspan='2'>Bienvenido al Sistema</td></tr>
                    <tr><td colspan='2'>Tus credenciales son:</td></tr>
                    <tr>
                        <td>Usuario:</td>
                        <td>Usuario_123</td>
                    </tr>
                    <tr>
                        <td>Pass:</td>
                        <td>".substr( md5(microtime()), 1, 8)."</td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>";

echo mail($to, $subject, $message, $headers);
