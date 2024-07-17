<script src="bower_components/jquery/dist/jquery.validate.min.js" type="text/javascript"></script>
<script src="bower_components/jquery/dist/localization/messages_es.min.js" type="text/javascript"></script>

<div id="nuevoArticulo">
    <div class="well" style="display: block;">
        <div class="row">
            <form id="formNuevoArticulo" role="form">
                <div class="col-md-2">
                    <label>Clave</label>
                    <br>
                    <input id="claveArticulo" name="claveArticulo" class="form-control" required="" type="text">
                </div>
                <div class="col-md-3">
                    <label>Nombre</label>
                    <br>
                    <input id="nombreArticulo" name="nombreArticulo" class="form-control" required="" type="text">
                </div>
                <div class="col-md-5">
                    <label>Descripción</label>
                    <br>
                    <input id="descripcionArticulo" name="descripcionArticulo" class="form-control" required="" type="text">
                </div>
                <div class="col-md-2">
                    <label>Línea</label>
                    <br>
                    <select id="artLinea" name="artLinea" class="form-control artLinea" required=""></select>
                </div>
            </form>
            <div class="col-md-12" style="margin-top:15px;">
                <div class="form-group" style="padding: 0px 0px !important; margin: 0px !important">
                    <fieldset class="scheduler-border" style="margin-bottom: 0px !important; padding: 0px 20px !important;">
                        <legend class="scheduler-border">Fotos</legend>
                        <?php include_once 'fotosart/articulofotos.php'; ?>
                    </fieldset>
                </div>
            </div>
            <div class="col-md-12" style="margin-top:15px;">
                <div class="form-group" style="padding: 0px 0px !important; margin: 0px !important">
                    <fieldset class="scheduler-border" style="margin-bottom: 0px !important; padding: 0px 20px !important;">
                        <legend class="scheduler-border">Materiales</legend>
                            <?php include_once 'materiales/materiales.php'; ?>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="row">
            <br>
            <div class="col-md-8"></div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-block" onclick="cancelarNuevoArticulo()">Cancelar</button>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-bitbucket btn-block" onclick="guardarArticulo()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        autoCompleteLineas("artLinea");
        $("#formNuevoArticulo").validate({ });
    });

    function cancelarNuevoArticulo() {
        resetValuesArticulos();
        mostrarOcultarNuevoArticulo(0);
    }
    
    function guardarArticulo() {
        if ($("#formNuevoArticulo").valid()) {
            var dataLineas = $('#artLinea').select2('data');
            guardarNuevoArticulo($("#nombreArticulo").val(), $("#descripcionArticulo").val(), dataLineas[0].id, $('#mainfoto').val(), $("#claveArticulo").val());
        }
    }
</script>