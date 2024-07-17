<link href="plugins/bootstrap-treeview/bootstrap-treeview.css" rel="stylesheet" type="text/css"/>
<script src="plugins/bootstrap-treeview/bootstrap-treeview.js" type="text/javascript"></script>

<div id="editarPermisosModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Permisos</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idRegistro">
                <input type="hidden" id="numPermisos">
                <form id="formPermisos" role="form">
                    <div class="form-group">
                        <div id="treeview-checkable" class=""></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="validarFormularioPermisos($('#idRegistro').val(), $('#numPermisos').val())">Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        $("#formPermisos").validate({ });
    });
    
    function loadCheckableTree() {
        $.when().then( function() {
            var datos = { "accion":'JSON' };
            
            $.post("./pages/usuarios/datosMenu.php", datos, function(result) {
                var json = result;
                var perms = $('#numPermisos').val();
                datos = { "accion":'nodes', "permisos":perms };
                
                $.post("./pages/usuarios/datosMenu.php", datos, function(result) {
                    var $checkableTree = $('#treeview-checkable').treeview( {
                        data: json,
                        showIcon: false,
                        showCheckbox: true,
                        onNodeChecked: function(event, node) {
                            $('#numPermisos').val(parseInt($('#numPermisos').val()) + parseInt(node.id));
                        },
                        onNodeUnchecked: function (event, node) {
                            $('#numPermisos').val(parseInt($('#numPermisos').val()) - parseInt(node.id));
                        }
                    });
                    
                    $checkableTree.treeview('uncheckAll', { silent: $('#chk-check-silent').is(':checked') });
                    $checkableTree.treeview('collapseAll', { silent: true });
                    var nodes = result;
                    var arrayLength = nodes.length;

                    for (var i = 0; i < arrayLength; i++) {
                        var checkableNodes = $checkableTree.treeview('search', [ nodes[i], { ignoreCase: false, exactMatch: true } ]);
                        $checkableTree.treeview('checkNode', [ checkableNodes, { silent: $('#chk-check-silent').is(':checked') }]);
                    }
                    
                    $checkableTree.treeview('collapseAll', { silent: true });
                    var checkableNodes = $checkableTree.treeview('search', [ "", { ignoreCase:false, exactMatch:false } ]);

                    $('#numPermisos').val(perms);
                }, "json");
            });
        }); 
    }
    
    function validarFormularioPermisos(idRegistro, numPermisos) {
        if ($("#formPermisos").valid()) {
            editarPermisos(idRegistro, numPermisos);
            $('#editarPermisosModal').modal('hide');
        }
    }
</script>