<script src="pages/almacen/ajuste/ajuste.js" type="text/javascript"></script>

<div class="well" style="display:block;">
    <form id="form_evento" role="form">
        <div class="row">
            <div class="col-md-3">
                <label>evento</label>
                <br>
                <input type="text" id="aevento" name="aevento" class="form-control aevento" required="" style="width:100% !important"></select>
            </div>
            <div class="col-md-6"></div>
            <div class="col-md-3">
                <br>
                <button type="button" class="btn btn-info btn-block" onclick="registraEvento($('#aevento').val())">Terminar</button>
            </div>
        </div>
    </form>
    <form id="form_ajuste" role="form">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Material</label>
                            <br>
                            <select id="amaterial" name="amaterial" class="form-control amaterial" required="" style="width:100% !important"></select>
                        </div>
                        <div class="col-md-2">
                            <label>Proyecto</label>
                            <br>
                            <select id="aproyecto" name="aproyecto" class="form-control aproyecto" required="" style="width:100% !important"></select>
                        </div>
                        <div class="col-md-2">
                            <label>Cantidad Sistema</label>
                            <br>
                            <input id="acantidad" name="acantidad" class="form-control acantidad" required="" type="text" disabled>
                        </div>
                        <div class="col-md-1">
                            <label>Conteo</label>
                            <br>
                            <input id="aconteo" name="aconteo" class="form-control aconteo" required="" type="text">
                        </div>
                        <div class="col-md-2">
                            <label>Nota</label>
                            <br>
                            <input id="anota" name="anota" class="form-control anota" required="" type="text">
                        </div>
                        <div class="col-md-2">
                            <br>
                            <button type="button" class="btn btn-bitbucket btn-block" onclick="agregarAjusteMaterial($('#amaterial').val(), $('#aproyecto').val(), $('#acantidad').val(), $('#aconteo').val(), $('#anota').val())">Agregar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="col-md-12 table-responsive">
    <table id="ajusteTabla" name="ajusteTabla" class="table table-hover">
        <thead>
            <tr>
                <th>Material</th>
                <th>Cantidad en sistema</th>
                <th>Conteo</th>
                <th>Ajuste</th>
                <th>Nota</th>
                <th>Usuario</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        inicializaTablaAjustesMaterial();
        autoCompleteMaterialesAjuste();

        $("#amaterial").change(function() {
            $("#aproyecto").val("");
            $("#acantidad").val("");
            $("#aconteo").val("");
            $("#anota").val("");
            autoCompleteProyectosMaterial($('#amaterial').val());
        });

        $("#aproyecto").change(function() {
            var dataP = $('#aproyecto').select2('data');
            var dataM = $('#amaterial').select2('data');
            if (dataP.length > 0)
                buscaCantidadMaterial(dataM[0].id, dataP[0].id);
        });

        $('#ajusteTabla tbody').on('click', 'button', function () {
            var data = $("#ajusteTabla").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "acancelar":
                    eliminarAjuste(data.IdHistoricoAjustes);
                    inicializaTablaAjustesMaterial();
                break;
            }
        });
    });
</script>