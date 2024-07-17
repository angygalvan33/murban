<script src="pages/compras/requispreoc/requispreocScript.js" type="text/javascript"></script>
<style type="text/css">
    .detalles{
        padding: 5px !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <button id='descargaPreOC' type='button' class='btn btn-info btn-sm' onclick='descargaPreOC(0)'>Descargar Presupuesto</button>
    </div>
</div>
<div class="row">
    <div class="col-md-12 table-responsive">
        <table id="requispreoc_Table" class="table table-hover">
            <thead>
                <tr>
                    <th>Creación</th>
                    <th>Proveedor</th>
                    <th>Total OC</th>
                    <th>Genera</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        loadDataTablePreOC();

        $('#requispreoc_Table tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#requispreoc_Table').DataTable().row(tr);
            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#requispreoc_Table').DataTable().row('.shown').length) {
                    $('.details-control', $('#requispreoc_Table').DataTable().row('.shown').node()).click();
                }
                // Open this row
                row.child( formatDetallePreOC(row.data()) ).show();
                tr.addClass('shown');
            }
        });

        $('#requispreoc_Table').on('click', 'button', function () {
            var data = $("#requispreoc_Table").DataTable().row($(this).parents('tr')).data();

            switch ($(this).attr("id")) {
                case "req_comprar":
                    $('#ocCompleteTablaOCReq').DataTable().clear().draw();
                    //$('#matsOCReq').DataTable().clear().draw();
                    $("#valIdProvOCReq").val(data.IdProveedor);
                    $("#proveedorOCReq").append(new Option(data.Proveedor, data.IdProveedor));
                    solicitarNuevaOC_Req(1);
                break;
            }
        });
    });
    /* Formatting function forl row details - modify as you need */
    function formatDetallePreOC (d) {
        var div = $('<div/>', { class:'row detalleMaterialesPreOCTable', id:d.IdProveedor });
        div.load("pages/compras/requispreoc/detallePreOc/listadoMateriales.php");
        return div;
    }

    function descargaPreOC(IdProveedor) {
        if ($('#requispreoc_Table').DataTable().data().count() > 0)
            window.open('html2pdf-master/reportes/reportePreOC.php?idProveedor='+IdProveedor, '_blank');
    }
</script>