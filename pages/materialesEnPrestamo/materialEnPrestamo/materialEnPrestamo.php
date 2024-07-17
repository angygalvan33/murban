<script src="pages/materialesEnPrestamo/materialEnPrestamo/materialEnPrestamoScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="MaterialPrestamoPTabla" class="table table-hover">
        <thead>
            <tr>
                <th>Material</th>
                <th>Cantidad en préstamo</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaEntradasMaterialPrestamoTable();
        
        $('#MaterialPrestamoPTabla').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#MaterialPrestamoPTabla').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#MaterialPrestamoPTabla').DataTable().row('.shown').length) {
                    $('.details-control', $('#MaterialPrestamoPTabla').DataTable().row('.shown').node()).click();
                }
                row.child(formatMatPrestamo(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    
    function formatMatPrestamo (rowData) {
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdMaterial });
        divDetalles.load("pages/materialesEnPrestamo/materialEnPrestamo/detalleMaterialPrestamo/detalleMaterialPrestamo.php");
        return divDetalles;
    }
</script>