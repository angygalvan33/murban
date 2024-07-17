<script src="pages/compras/esperaFacturacion/esperaFacturacionScript.js" type="text/javascript"></script>
<link href="pages/compras/ocompraStyles.css" rel="stylesheet" type="text/css"/>

<div class="col-md-12 table-responsive">
    <table id="esperaFacturacionTable" class="table table-hover" style="width:100% !important;">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Total OC</th>
                <th>Descripción</th>
                <th>Genera</th>
                <th>Pagada</th>
                <th>PDF</th>
                <th>Facturación</th>
            </tr>
        </thead>
    </table>
</div>
<?php
    include 'modalFacturarEmitida.php';
?>
<script type="text/javascript">
    $( document ).ready( function() {
        var permisoFacturar = <?php echo json_encode($permisos->acceso("262144", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        loadDataTableEsperaFacturacion(permisoFacturar);
        $('#esperaFacturacionTable').on('click', 'button', function () {
            var data = $("#esperaFacturacionTable").DataTable().row($(this).parents('tr')).data();

            switch ($(this).attr("id")) {
                case "em_facturar":
                    $("#factura").attr("disabled", false);
                    $("#valor_factura").attr("disabled", false);
                    loadFacturacion(data, 1);
                break;
                case "em_pendienteFacturar":
                    $("#factura").attr("disabled", true);
                    $("#valor_factura").attr("disabled", true);
                    loadFacturacion(data, 2);
                break;
            }
        });

        $('#esperaFacturacionTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#esperaFacturacionTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#esperaFacturacionTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#esperaFacturacionTable').DataTable().row('.shown').node()).click();
                }
                row.child(formatEsperaFacturacion(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatEsperaFacturacion (rowData) {
        var divTipo = $('<div/>', {class:'tipo', id:"EsperaFacturacion"});
        var divDetalles = $('<div/>', {class:'row detalles', id:rowData.IdOrdenCompra});
        divTipo.append(divDetalles);
        divDetalles.load("pages/compras/detalleOC/detalleOC.php");
        return divTipo;
    }
</script>