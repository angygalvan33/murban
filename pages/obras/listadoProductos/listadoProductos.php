<?php
    set_include_path(get_include_path() . PATH_SEPARATOR . '../../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../../config.php";
    include_once '../../../clases/permisos.php';
    include_once '../../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>

<script src="pages/obras/listadoProductos/listadoProductosScript.js" type="text/javascript"></script>
<style type="text/css">
    .ancho {
        width:100% !important;
    }
</style>

<div style="margin:10px 10px">
    <form id="form_productosxobra" role="form">
        <div class="row">
            <div class="col-md-6">
                <label>Producto</label>
                <br>
                <select id="productoO" name="productoO" class="form-control productoO" required="" style="width:100% !important"></select>
                <br>
            </div>
            <div class="col-md-3">
                <label>Cantidad</label>
                <br>
                <input id="cantidadO" name="cantidadO" class="form-control cantidadO" required="" type="text">
            </div>
            <div class="col-md-3"><br>
                <button type="button" class="btn btn-bitbucket btn-block" onclick="agregarProductoaProyecto($('.detalles').attr('id'))">Agregar a proyecto</button>
            </div>
        </div>
    </form>
    <h4>Productos de Proyecto</h4>
    <table id="productosObraTable" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Producto</th>
                <th>Foto</th>
                <th>Cantidad</th>
                <th>Costo</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<div id="warningModalP" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Eliminación</h4>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas eliminar el producto?</p>
                <input type="hidden" id="idProductoP">
                <input type="hidden" id="idProyectoP">
                <input type="hidden" id="idRequisicionP">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
                <button id="eliminarProducto" type="button" class="btn btn-outline" data-dismiss="modal" onclick="eliminarProducto($('#idRequisicionP').val())">Eliminar</button>
              </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        loadProductosObraDataTable();
        autoCompleteProductosProyecto();

        $('#productosObraTable tbody').on('click', 'button', function () {
            var data = $("#productosObraTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "eliminar_p":
                    $("#idProductoP").val(data.IdProducto);
                    $("#idProyectoP").val(data.IdProyecto);
                    $("#idRequisicionP").val(data.IdRequisicion);
                    $("#warningModalP").modal("show");
                break;
            }
        });
    });

    function agregarProductoaProyecto(idObra) {
        if ($("#form_productosxobra").valid()) {
            agregarAProyecto(idObra);
        }
    }
</script>