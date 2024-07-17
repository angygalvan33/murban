<div id="historicoMaterialesModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Histórico de materiales</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idMaterial">
                <input type="hidden" id="idProveedor">
                <div class="row" style="text-align: center">
                    <div class="col-md-12" id="nombreMaterial"></div>
                    <div class="col-md-12" id="nombreProveedor"></div>
                </div>
                <table id="historicoTable" class="table table-hover">
                    <thead class="encabezadoTabla">
                        <tr>
                            <th>Fecha</th>
                            <th>Precio</th>
                            <th>¿Capturado con IVA?</th>
                            <th>Atendió</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        $('#historicoTable').DataTable( {
            'processing': true,
            'serverSide': true,
            'ajax': {
                url: "pages/proveedores/listadoMateriales/historicoData.php", //json datasource
                type: "post", //method, by default get
                data: function(d) {
                    d.IdProveedor = $("#historicoMaterialesModal #idProveedor").val(),
                    d.IdMaterial = $("#historicoMaterialesModal #idMaterial").val()
                },
                error: function() { //error handling
                    $(".historicoTable-error").html("");
                    $("#historicoTable").append('<tbody class="historicoTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                    $("#historicoTable_processing").css("display", "none");
                }
            },
            'columns': [
                { 'data': "Creado", orderable: true, width: "30%" },
                { 'data': "Precio", orderable: true, width: "20%", className: "alinearDerecha",
                    mRender: function (data, type, row) {
                        return "$" + formatNumber(row.Precio);
                    }
                },
                { 'data': "Iva", orderable: true, width: "20%",
                    mRender: function (data, type, row) {
                        if (row.Iva == 1)
                            return "<input class='iva icheckbox_flat-green' checked type='checkbox' disabled>";
                        else
                            return "<input class='iva icheckbox_flat-green' type='checkbox' disabled>";
                    }
                },
                { 'data': "Cotizador", orderable: true, width: "30%" }
            ],
            'language': {
                "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
});
</script>