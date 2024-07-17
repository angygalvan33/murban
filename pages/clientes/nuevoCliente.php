<div id="nuevoClienteModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cliente</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="accion">
                <input type="hidden" id="idRegistro">
                <form id="formCliente" role="form">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="radio-inline">
                                <input type="radio" id="pfisica" name="tipoPersona" value="0"> Persona física
                            </label>
                            <label class="radio-inline">
                                <input type="radio" id="pmoral" name="tipoPersona" value="1"> Persona moral
                            </label>
                        </div>
                        <div class="form-group col-md-9">
                            <label>Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required maxlength="250">
                        </div>
                        <div class="form-group col-md-3">
                            <label>RFC</label>
                            <input type="text" id="rfc" name="rfc" class="form-control" required maxlength="50">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Dirección</label>
                            <input type="text" id="direccion" name="direccion" required class="form-control" maxlength="500">
                        </div>
                    </div>
                    <div id="datosPMoral" class="form-group" style="padding: 0px 0px !important; margin: 0px !important;">
                        <fieldset class="scheduler-border" style="margin-bottom: 0px !important;">
                            <legend class="scheduler-border">Datos de contacto</legend>
                            <button type='button' class="btn btn-success btn-sm" style="margin-bottom: 10px" onclick="muestraFormContacto(1)"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
                            <div id="formContacto">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label>Nombre</label>
                                        <input type="text" id="contacto" name="contacto" class="form-control" maxlength="100" required="required">
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label>E-mail</label>
                                        <input type="text" id="emailc" name="emailc" class="form-control" maxlength="250" required="required">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Teléfono</label>
                                        <input type="text" id="telefonoc" name="telefonoc" class="form-control" data-inputmask='"mask": "(999) 999-9999"' data-mask required="required">
                                    </div>
                                    <div class="col-md-1">
                                        <br>
                                        <button type='button' class="btn btn-success" id="addContact" onclick="nuevoContacto($('#contacto').val(), $('#emailc').val(), $('#telefonoc').val())"><i class="fa fa-plus"></i></button>
                                    </div>
                                    <div class="col-md-1">
                                        <br>
                                        <button type='button' class="btn btn-danger" id="cancelContact" onclick="muestraFormContacto(0)"><i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <table id="contactoTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>E-mail</th>
                                            <th>Teléfono</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </fieldset>  
                    </div>
                    <div class="form-group" style="padding: 0px 0px !important; margin: 0px !important">
                        <fieldset class="scheduler-border" style="margin-bottom: 0px !important;">
                            <legend class="scheduler-border">Crédito</legend>
                            <div class="form-group">
                                <div class="col-md-12 monedas" style="padding:0px 0px !important">
                                    <div class="col-md-6" style="padding-left:0px !important;">
                                        <label>Días de crédito</label>
                                        <input type="text" id="diasCredito" name="diasCredito" required class="form-control">
                                    </div>
                                    <div class="col-md-6" style="padding-left:0px !important;">
                                        <label>Límite de crédito (MXN)</label>
                                        <input type="text" id="limiteCredito" name="limiteCredito" required class="form-control">
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" id="btnAceptar" class="btn btn-primary" onclick="validarFormularioCliente($('#accion').val(), $('#idRegistro').val())" disabled>Aceptar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script type="text/javascript">
    contactos = [];
    $( document ).ready(function() {
        inicializaContactoTabla();

        $("#formCliente").validate( {
            rules: {
                email: { email: true },
                diasCredito: { number: true },
                limiteCredito: { number: true }
            }
	    });
        
        $('[data-mask]').inputmask();

        $("#limiteCredito").inputmask(
            "decimal", {
                allowMinus: false,
                allowPlus: false,
                radixPoint: ".",
                groupSeparator: ",",
                digits: 2,
                autoGroup: true
            }
        );

        $("#diasCredito").inputmask(
            "integer", {
                allowMinus: false,
                allowPlus: false,
            }
        );

        $('input:radio[name="tipoPersona"]').change(function() {
            $("#btnAceptar").prop("disabled", false);
        });
        
        $('#contactoTable tbody').on('click', 'button', function () {
            switch ($(this).attr("id")) {
                case "eliminarContacto":
                    var actualRow = $("#contactoTable").DataTable().row($(this).parents('tr'));
                    actualRow.remove().draw();
                break;
            }
        });
    });
    
    function muestraFormContacto(valor) {
        if (valor === 0) {
            $("#contacto").val("");
            $("#email").val("");
            $("#telefono").val("");
            $("#formContacto").css("display", "none");
        }
        else
            $("#formContacto").css("display","block");
    }
    
    function validarFormularioCliente(accion, idRegistro) {
        if ($("#formCliente").valid()) {
            guardarCliente(accion, idRegistro);
        }
    }
</script>