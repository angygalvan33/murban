<script src="pages/almacen/OCEsperaRecepcion/OCEsperaRecepcionScript.js" type="text/javascript"></script>
<link href="pages/almacen/OCEsperaRecepcion/OCEsperaRecepcionStyles.css" rel="stylesheet" type="text/css"/>

<div class="col-md-12 table-responsive">
    <table id="ocEsperaTable" class="table table-hover" style="width:100% !important;">
        <thead>
            <tr>
                <th>Folio</th>
                <th>Fecha</th>
                <th>Proveedor</th>
                <th>Total OC</th>
                <th>Descripción</th>
                <th>Genera</th>
                <th>Autoriza</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        loadDataTableOCEsperaRecepcion();

        $('#ocEsperaTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#ocEsperaTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#ocEsperaTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#ocEsperaTable').DataTable().row('.shown').node()).click();
                }
                row.child(formatOCEsperaRecepcion(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatOCEsperaRecepcion (rowData) {
        var divDetalles = $('<div/>', {class:'row detalles', id:rowData.IdOrdenCompra});
        divDetalles.load("pages/almacen/OCEsperaRecepcion/detalleMateriales/detalleMateriales.php");
        return divDetalles;
    }
</script>