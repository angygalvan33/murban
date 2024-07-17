<?php

?>

<script src="../../bower_components/jquery/dist/jquery.min.js"></script>

<link href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<link href="../../plugins/iCheck/all.css" rel="stylesheet" type="text/css"/>
<script src="../../plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<!-- InputMask -->
<script src="../../plugins/input-mask/jquery.inputmask.js" type="text/javascript"></script>
<script src="../../plugins/input-mask/jquery.inputmask.extensions.js" type="text/javascript"></script>
<script src="../../plugins/input-mask/jquery.inputmask.numeric.extensions.js" type="text/javascript"></script>

<label>Logotipo</label>
<br>
<img src="logo_empresa.png" alt="logo_empresa.png" height="160px" width="200px">
<br>
<br>
<input type='file' accept="image/png" id="fileLogo"/>
<br>
<button class="btn btn-primary" id="subirLogo">Subir logo</button>

<script type="text/javascript">
    
    $( document ).ready(function() {
        
        $('#subirLogo').click(  function () {
            var archivos = $("#fileLogo")[0].files;
            if(archivos.length > 0)
            {
                var formData = new FormData();
                formData.append("accion", "cambiarImagen");
                console.log(archivos[0]);
                formData.append("archivo", archivos[0]);
                
                $.ajax({
                    type: 'POST',
                    url: "datos.php",
                    data: formData,
                    success: function(result) {
                        if(result == false)
                        {
                            alert("Error al actualizar logotipo.");
                        }
                        else
                        {
                            alert("Logotipo actualizado.");
                        }
                    },
                    error: function(response) {
                        alert("Error al actualizar logotipo.");
                    },
                    processData: false,
                    contentType: false
                });
            }
            else
            {
                alert("No se ha seleccionado un archivo válido.");
            }
        });
        
    });
    
    
</script>