<script src="pages/almacen/stock/stockScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="stockTabla" class="table table-hover">
        <thead>
            <tr>
                <th>Material</th>
                <th>Cantidad disponible</th>
                <th>Medida</th>
                <th>Categoría</th>
                <th>Ubicación</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        inicializaStockTable();
        
        $('#stockTabla').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#stockTabla').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#stockTabla').DataTable().row('.shown').length) {
                    $('.details-control', $('#stockTabla').DataTable().row('.shown').node()).click();
                }
                row.child(formatStock(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    
    function formatStock(rowData) {
        var divDetalles = $('<div/>', {class:'row detalles', id:rowData.IdMaterial, precioUnitario:rowData.PrecioUnitario, nombreMaterial:rowData.Nombre});
        divDetalles.load("pages/almacen/stock/detalleStock.php");
        return divDetalles;
    }
</script>