<script src="pages/almacen/entradas/entradas.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-4">
        <label>Órden de compra</label>
        <div class="input-group">
            <input type="text" class="form-control iibuscarOC" placeholder="Buscar" id='iibuscarOC'/>
            <div class="input-group-btn">
                <button class="btn btn-primary" type="button" id='btn_iibuscarOC'>
                <span class="glyphicon glyphicon-search"></span>
              </button>
            </div>
      </div>
    </div>
</div>
<br>
<div class="col-md-12 table-responsive">
    <table id="entradasTabla" class="table table-hover">
        <thead>
            <tr>
                <th>Cantidad</th>
                <th>Material</th>
                <th>Medida</th>
                <th>Categoría</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        inicializaTablaEntradas();
        //buscar orden de compra
        $("#btn_iibuscarOC").on("click", function() {
            buscarOC_inventarioInicial($("#iibuscarOC").val());
        });
        
        $('#iibuscarOC').keypress(function(e) {
            var keycode = (e.keyCode ? e.keyCode : e.which);
            
            if (keycode == '13') {
                buscarOC_inventarioInicial($(this).val());
                e.preventDefault();
                return false;
            }
        });

        $('#entradasTabla').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#entradasTabla').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#entradasTabla').DataTable().row('.shown' ).length) {
                    $('.details-control', $('#entradasTabla').DataTable().row('.shown').node()).click();
                }
                row.child(MaterialPorObra(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    /* Formatting function forl row details - modify as you need */
    function MaterialPorObra (rowData) {
        var divDetalles = $('<div/>', {class: 'row detalles', id:rowData.IdMaterial, idOC:rowData.IdOrdenCompra, nombreMaterial:rowData.Material, precioUnitario:rowData.PrecioUnitario, idObra: rowData.IdObra});
        divDetalles.load("pages/almacen/entradas/materialesPorObra.php");
        return divDetalles;
    }
</script>