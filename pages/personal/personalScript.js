function loadDataTable(permisoAdministrar) {
    $('#personalTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/personal/personalData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".personalTable-error").html("");
                $("#personalTable").append('<tbody class="personalTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#personalTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Nombre", orderable: true, width: "30%", className: 'details-control'},
            {
                mRender: function (data, type, row) {
                    if (row.FechaNac !== null)
                        return ""+ row.FechaNac
                    else;
                        return "-";
                }, sortable: false, width: "10%"
            },
            { 'data': "NSS", orderable: true, width: "10%" },
            { 'data': "Telefono", orderable: true, width: "10%" },
            {
                mRender: function (data, type, row) {
                    if (row.FechaBaja !== null)
                        return ""+ row.FechaBaja;
                    else
                        return "-";
                }, sortable: false, width: "10%"
            },
            {
                mRender: function (data, type, row) {
                    if (row.Activo == 1)
                        return "<input id='edo' class='edo icheckbox_flat-green' checked type='checkbox'>";
                    else
                        return "<input id='edo' class='edo icheckbox_flat-green' type='checkbox'>";
                }, sortable: false, width: "10%"
            },
            { orderable: false, width: "20%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    if (permisoAdministrar) {
                        buttons += "<button type='button' id='editar' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Editar</button>";
                        buttons += "<button type='button' id='eliminar' style='margin-right:5px' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    }
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function resetValues() {
    $("#nombre").val("");
    $("#fechaing").val("");
    $("#departamento").val("");
    $("#puesto").val("");
    $("#periodo").attr('checked', false);
    $("#sueldo").val("");
    $("#fechanac").val("");
    $("#nss").val("");
    $("#telefono").val("");
    $("#fechabaja").val("");
    $("#formPersonal" ).validate().resetForm();
    $("#formPersonal :input").removeClass('error');
}

function openModalPersonal() {
    resetValues();
    $("#accion").val(0);
    $("#idRegistro").val(0);
    $('#nuevoPersonalModal').modal('show');
}
//accion 0 => guardar, 1 => editar
//idRegistro en editar trae el Id del que se eliminará, en alta viene con 0
function guardarPersonal(accion, idRegistro) {
    var data = $("#formPersonal").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    
    $.each(data, function(key, value) {
        datos[value.name] = value.value;
    });

    var periodo = $('input[name="periodo"]:checked').val();
    datos["periodo"] = periodo;
    //console.log(datos);
    //guardar en bd
    $.post("./pages/personal/datos.php", datos, function(result) {
        var msj1 = "";
        var msj2 = "";
        var msjError = "";
        
        if (accion == 0) { //alta
            msj1 = "DADO DE ALTA";
            msj2 = "DAR DE ALTA";
        }
        else {
            msj1 = "EDITADO";
            msj2 = "EDITAR";
        }
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA "+ msj1 +" EL PERSONAL.");
                $("#successModal").modal("show");
                $('#personalTable').DataTable().ajax.reload();
                resetValues();
            break;
            case 1:
                msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR AL "+ msj2 +". ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
            case 2:
                msjError = result["result"];
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text("ERROR AL "+ msj2 +": "+ msjError);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function loadEditarPersonal(data) {
    $("#nombre").val(data.Nombre);
    $("#fechaing").datepicker('setDate', data.FechaIngreso);
    $("#puesto").val(data.Puesto);
    $("#sueldo").val(data.Sueldo);
    $("#nss").val(data.NSS);
    $("#telefono").val(data.Telefono);
    
    if (data.IdDepto == null)
        $('#depto').append('<option selected value="0">Seleccionar opción</option>');
    else
        $('#depto').append('<option selected value="'+ data.IdDepto +'">'+ data.Depto +'</option>');

    if (data.IdPeriodo == 0)
        $("#semanal").prop('checked', true);
    else if (data.IdPeriodo == 1)
        $("#quincenal").prop('checked', true);
    else
        $("#periodo").attr('checked', false);
    
    if (data.FechaNac !== null)
        $("#fechanac").val(data.FechaNac);
    else
        $("#fechanac").val('');

    $("#accion").val(1);
    $("#idRegistro").val(data.IdPersonal);
    $('#nuevoPersonalModal').modal('show');
    $("#formPersonal").validate().resetForm();
    $(".error").removeClass("error");
}

function eliminarPersonal(idPersonal) {
    var datos = { "accion":'baja', "id":idPersonal };
    
    $.post("./pages/personal/datos.php", datos, function(result) {
        $('#personalTable').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA ELIMINADO EL PERSONAL.");
                $("#successModal").modal("show");
                $('#personalTable').DataTable().ajax.reload();
                resetValues();
            break;
            case 1:
                $("#errorModal .modal-body").text("ERROR AL ELIMINAR. ERROR DE BASE DE DATOS.");
                $("#errorModal").modal("show");
            break;
            case 2:
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text("ERROR AL ELIMINAR: "+ result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function eliminarRegistro(id, tipo) {
    eliminarPersonal(id);
}

function autoCompleteDepartamentos() {
    $('.depto').select2( {
        placeholder: "Seleccionar departamento",
        allowClear: true,
        ajax: {
            url: './pages/personal/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'departamentos',
                    searchTerm: params.term //search term
                };
            },
            processResults: function (response) {
                return {
                    results: response
                };
            }
        }
    });
}

function desactivaPersonal(idPersonal, estatus) {
    var datos = { "accion":"desactivar", "IdPersonal":idPersonal, "Estatus":estatus };
        
    $.post("./pages/personal/datos.php", datos, function(result) {
        if (result["error"] == 0) {
            $('#obTable').DataTable().ajax.reload();
            $("#successModal .modal-body").text("SE HA MODIFICADO EL REGISTRO");
            $("#successModal").modal("show");
        }
        else {
            $("#errorModal .modal-body").text("ERROR AL MODIFICAR EL REGISTRO. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
    }, "json");
}