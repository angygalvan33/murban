<script src="pages/requisiciones/atendidas/requisicionesScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="requisicionesAtendidasTable" class="table table-hover reqs" style="width:100% !important;">
        <thead>
            <tr>
                <th>No.</th>
                <th>Fecha</th>
                <th>Observaciones</th>
                <th>Tipo de requisición</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        loadDataTableRequisicionesAtendidas();
       
        $('#requisicionesAtendidasTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#requisicionesAtendidasTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#requisicionesAtendidasTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#requisicionesAtendidasTable').DataTable().row('.shown').node()).click();
                }
                row.child(formatAtendida(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatAtendida (rowData) {
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdRequisicion });
        divDetalles.load("pages/requisiciones/detalleReqAtendidas/detalleReq.php");
        return divDetalles;
    }
</script>