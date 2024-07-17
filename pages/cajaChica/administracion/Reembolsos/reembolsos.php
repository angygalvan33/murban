<script src="pages/cajaChica/administracion/Reembolsos/reembolsosScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="reembolsosFacturadosTable" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Reembolsos Facturados</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    var openRows1 = new Array();
    var openRows2 = new Array();
    
    $( document ).ready(function() {
        loadDataTableReembolsosFacturados();

        $('#reembolsosFacturadosTable tbody').on('click', 'td.details-control2', function () {
            var tr = $(this).closest('tr');
            var row = $('#reembolsosFacturadosTable').DataTable().row(tr);
            if (row.child.isShown()) {
                //This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                closeOpenedRows($('#reembolsosFacturadosTable').DataTable(), tr, openRows1);
                row.child( formatDetalleReembolsos(row.data(), 1)).show();
                tr.addClass('shown');
                //store current selection
                openRows1.push(tr);
            }
        });
        
        $('#reembolsosNoFacturadosTable tbody').on('click', 'td.details-control2', function () {
            var tr = $(this).closest('tr');
            var row = $('#reembolsosNoFacturadosTable').DataTable().row(tr);
            if (row.child.isShown()) {
                //This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                closeOpenedRows($('#reembolsosNoFacturadosTable').DataTable(), tr, openRows2);
                row.child( formatDetalleReembolsos(row.data(),2)).show();
                tr.addClass('shown');
                //store current selection
                openRows2.push(tr);
            }
        });
    });
    
    function formatDetalleReembolsos (rowData, tipo) {
        var div = $('<div/>', {class:'row detallesReembolso', id:rowData.FechaRegistroCorte});

        if (tipo == 1)
            div.load("pages/cajaChica/administracion/Reembolsos/DetalleReembolsos/detalleReembolsosFacturados.php");
        else
            div.load("pages/cajaChica/administracion/Reembolsos/DetalleReembolsos/detalleReembolsosNoFacturados.php");
        return div;
    }
    
    function closeOpenedRows(table, selectedRow, openRows) {
        $.each(openRows, function (index, openRow) {
            //not the selected row!
            if ($.data(selectedRow) !== $.data(openRow)) {
                var rowToCollapse = table.row(openRow);
                rowToCollapse.child.hide();
                openRow.removeClass('shown');
                //remove from list
                var index = $.inArray(selectedRow, openRows);
                openRows.splice(index, 1);
            }
        });
    }
</script>