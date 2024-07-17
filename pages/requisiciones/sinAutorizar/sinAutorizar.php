<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>

<script src="pages/requisiciones/sinAutorizar/sinAutorizarScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="requisicionesSinAutorizarTable" class="table table-hover reqs" style="width:100% !important;">
        <thead>
            <tr>
                <th>No.</th>
                <th>Fecha</th>
                <th>Observaciones</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<div id="cancelarReqSAModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cancelar</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idRequisicionSA">
                <label>Motivo de cancelación:</label>
                <br>
                <textarea id="motivoCancelacionReqSA" style="resize: none; width:100%; color:black;" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal" onclick="cancelarRequisicionSA($('#idRequisicionSA').val(), $('#motivoCancelacionReqSA').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<div id="autorizarReqSAModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cancelar</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idRequisicionSA">
                <label>¿Desea autorizar la requisición?</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal" onclick="autorizarRequisicionSA($('#idRequisicionSA').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        var permisoCancelarSA = <?php echo json_encode($permisos->acceso("32768", $usuario->obtenerPermisos($_SESSION['username']))); ?>;

        loadDataTableRequisicionesSinAutorizar(permisoCancelarSA);

        $('#requisicionesSinAutorizarTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#requisicionesSinAutorizarTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#requisicionesSinAutorizarTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#requisicionesSinAutorizarTable').DataTable().row('.shown').node()).click();
                }
                row.child(formatSinAutorizar(row.data())).show();
                tr.addClass('shown');
            }
        });
        
        $('#requisicionesSinAutorizarTable').on('click', 'button', function () {
            var data = $("#requisicionesSinAutorizarTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "req_cancelarSA":
                    $("#idRequisicionSA").val(data.IdRequisicion);
                    $("#cancelarReqSAModal").modal("show");
                break;
                case "req_autorizar":
                    $("#idRequisicionSA").val(data.IdRequisicion);
                    $("#autorizarReqSAModal").modal("show");
                    //mostrarOcultarNuevaRequisiscion(1, 3);
                break;
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatSinAutorizar (rowData) {
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdRequisicion });
        divDetalles.load("pages/requisiciones/detalleReqSA/detalleReqSA.php");
        return divDetalles;
    }
</script>