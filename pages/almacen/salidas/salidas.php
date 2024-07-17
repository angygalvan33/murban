<script src="pages/almacen/salidas/salidas.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="salidasTabla" class="table table-hover">
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
        inicializaSalidasTable();
        
        $('#salidasTabla').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#salidasTabla').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#salidasTabla').DataTable().row('.shown').length) {
                    $('.details-control', $('#salidasTabla').DataTable().row('.shown').node()).click();
                }
                row.child(CantidadMatxObra(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    
    function CantidadMatxObra(rowData) {
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdMaterial, precioUnitario:rowData.PrecioUnitario, nombreMaterial:rowData.Nombre });
        divDetalles.load("pages/almacen/salidas/materialesPorObra.php");
        return divDetalles;
    }
</script>