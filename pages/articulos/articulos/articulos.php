<script src="bower_components/jquery/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="bower_components/jquery/dist/localization/messages_es.min.js" type="text/javascript"></script>
<script src="bower_components/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<link href="bower_components/select2/dist/css/select2.min.css" rel="stylesheet" type="text/css"/>
<script src="pages/articulos/articulos/articulosScript.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-10"></div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <button type="button" class="btn bg-navy btn-flat btn-block" onclick="mostrarOcultarNuevoArticulo(1)"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
    </div>
    <div class="col-md-12">
        <?php 
            include_once 'nuevoArticulo.php';
        ?>
    </div>
    <div class="col-md-12 table-responsive">
        <table class="table table-hover" id="tablaArticulos">
            <thead>
                <tr>
                    <th>Clave</th>
                    <th>Nombre</th>
                    <th></th>
                    <th>Descripción</th>
                    <th>Línea</th>
                    <th>Materiales</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="editarArticuloModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">REGISTRO</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idArticuloM">
                <form id="formArticulo" role="form">
                    <div class="form-group">
                        <label>Clave</label>
                        <br>
                        <input id="claveArticuloM" name="claveArticuloM" class="form-control" required="" type="text">
                    </div>
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="nombreArticuloM" name="nombreArticuloM" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <input type="text" id="descripcionArticuloM" name="descripcionArticuloM" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Línea</label>
                        <br>
                        <select id="artLineaM" name="artLineaM" class="form-control artLineaM" required=""></select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="editarArticulo($('#idArticuloM').val(), $('#claveArticuloM').val(), $('#nombreArticuloM').val(), $('#descripcionArticuloM').val(), $('#artLineaM').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        mostrarOcultarNuevoArticulo(0);
        autoCompleteLineas("artLineaM");
        inicializaTablaArticulos();

        $('#tablaArticulos tbody').on('click', 'button', function () {
            var data = $("#tablaArticulos").DataTable().row($(this).parents('tr')).data();

            switch ($(this).attr("id")) {
                case "editarArticulo":
                    $("#idArticuloM").val(data.IdArticulo);
                    $("#claveArticuloM").val(data.Clave);
                    $("#nombreArticuloM").val(data.Nombre);
                    $("#descripcionArticuloM").val(data.Descripcion);
                    var option = new Option(data.Linea, data.IdLinea, true, true);
                    $("#artLineaM").append(option).trigger('change');
                    $("#editarArticuloModal").modal('show');
                break;
                case "eliminarArticulo":
                    $("#idRegistro").val(data.IdArticulo);
                    $("#tipo").val(1);
                    $("#warningModal").modal("show");
                break;
                case "verMateriales":
                    verMateriales($(this));
                break;
                case "verFotos":
                    verFotos($(this));
                break;
            }
        });
    });
    
    var openRows = new Array();
    
    function verMateriales(btn) {
        var tr = btn.closest('tr');
        var row = $('#tablaArticulos').DataTable().row(tr);

        if (row.child.isShown()) {
            //This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            btn.find(".openPrecio").remove();
        }
        else {
            closeOpenedRows($('#tablaArticulos').DataTable(), tr);
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
            openRows.push(tr);
            btn.append("<i class='fa fa-level-down openPrecio' aria-hidden='true'></i>");
        }
    }
    /*Formatting function forl row details - modify as you need*/
    function format (rowData) {
        var div = $('<div/>', {class: 'row detalles', id: rowData.IdArticulo});
        div.load("pages/articulos/articulos/verMateriales/materiales.php");
        return div;
    }
    
    function closeOpenedRows(table, selectedRow) {
        $(".verMateriales").html("<i class='fa fa-eye'></i>&nbsp;Ver materiales");
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
    /**Fotos**/
    /*var openRowsFotos = new Array();
    
    function verFotos(btn) {
        var tr = btn.closest('tr');
        var row = $('#tablaArticulos').DataTable().row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            btn.find(".openPrecio").remove();
        }
        else {
            closeOpenedRows($('#tablaArticulos').DataTable(), tr);
            // Open this row
            row.child(formatFotos(row.data())).show();
            tr.addClass('shown');
            openRowsFotos.push(tr);
            btn.append("<i class='fa fa-level-down openPrecio' aria-hidden='true'></i>");
        }
    }*/
    /* Formatting function forl row details - modify as you need */
    /*function formatFotos (rowData) {
        var div = $('<div/>', {class: 'row fotos', id: rowData.IdMaterial});
        div.load("pages/articulos/articulos/verfotosart/verfotosart.php");
        return div;
    }
    
    function closeOpenedRows(table, selectedRow) {
        $(".verFotos").html("<i class='fa fa-eye'></i>&nbsp;Ver Fotos");
        $.each(openRowsFotos, function (index, openRow) {
            // not the selected row!
            if ($.data(selectedRow) !== $.data(openRow)) {
                var rowToCollapse = table.row(openRow);
                rowToCollapse.child.hide();
                openRow.removeClass('shown');
                // remove from list
                var index = $.inArray(selectedRow, openRows);
                openRowsFotos.splice(index, 1);
            }
        });
    }*/
</script>