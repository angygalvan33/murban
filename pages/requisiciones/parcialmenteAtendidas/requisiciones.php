<script src="pages/requisiciones/parcialmenteAtendidas/requisicionesScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="requisicionesParcialmenteAtendidasTable" class="table table-hover reqs" style="width:100% !important;">
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
       loadDataTableRequisicionesParcialmenteAtendidas();
       
       $('#requisicionesParcialmenteAtendidasTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#requisicionesParcialmenteAtendidasTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#requisicionesParcialmenteAtendidasTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#requisicionesParcialmenteAtendidasTable').DataTable().row('.shown').node()).click();
                }
                row.child(formatParcialmenteAtendida(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatParcialmenteAtendida (rowData) {
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdRequisicion });
        divDetalles.load("pages/requisiciones/detalleReq/detalleReq.php");
        return divDetalles;
    }
</script>