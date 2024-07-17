<?php
    set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>

<script src="pages/requisiciones/pendientes/requisicionesScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="requisicionesPendientesTable" class="table table-hover reqs" style="width:100% !important;">
        <thead>
            <tr>
                <th>No.</th>
                <th>Fecha</th>
                <th>Observaciones</th>
                <th>Tipo de requisición</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<div id="cancelarReqModal" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cancelar</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idRequisicion">
                <label>Motivo de cancelación:</label>
                <br>
                <textarea id="motivoCancelacionReq" style="resize: none; width:100%; color:black;" rows="3"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-outline" data-dismiss="modal" onclick="cancelarRequisicion($('#idRequisicion').val(), $('#motivoCancelacionReq').val())">Aceptar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        var permisoCancelar = <?php echo json_encode($permisos->acceso("8192", $usuario->obtenerPermisos($_SESSION['username']))); ?>;

        loadDataTableRequisicionesPendientes(permisoCancelar);

        $('#requisicionesPendientesTable').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#requisicionesPendientesTable').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#requisicionesPendientesTable').DataTable().row('.shown').length) {
                    $('.details-control', $('#requisicionesPendientesTable').DataTable().row('.shown').node()).click();
                }
                row.child(formatPendiente(row.data())).show();
                tr.addClass('shown');
            }
        });
        
        $('#requisicionesPendientesTable').on('click', 'button', function () {
            var data = $("#requisicionesPendientesTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "req_cancelar":
                    $("#idRequisicion").val(data.IdRequisicion);
                    $("#cancelarReqModal").modal("show");
                break;
                case "req_editar":
                    $("#idRequisicion").val(data.IdRequisicion);
                    mostrarOcultarNuevaRequisiscion(1, 3);
                break;
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatPendiente (rowData) {
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdRequisicion });
        divDetalles.load("pages/requisiciones/detalleReq/detalleReq.php");
        return divDetalles;
    }
</script>