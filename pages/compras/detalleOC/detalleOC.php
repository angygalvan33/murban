<script src="pages/compras/detalleOC/detalleOCScript.js" type="text/javascript"></script>
<link href="pages/compras/detalleOC/detalleOCStyles.css" rel="stylesheet" type="text/css"/>

<div class="col-md-12">
    <table id="detalleOCTable" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Cantidad</th>
                <th>Material</th>
                <th>Precio unitario (MXN)</th>
                <th>Costo total (MXN)</th>
                <th>Proyecto</th>
                <th>Solicita</th>
                <th>Archivo</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaDetalleOCTable();
    });
</script>