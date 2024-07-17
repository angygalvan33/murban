function inicializaTablaUbicacionMaterial() {
    $('#ubicacionMaterialTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/ubicacionMaterial/ubicacionMaterialData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".ubicacionMaterialTabla-error").html("");
                $("#ubicacionMaterialTabla").append('<tbody class="ubicacionMaterialTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#ubicacionMaterialTabla_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Material", orderable: true, width: "25%" },
            { 'data': "Ubicacion", orderable: true, width: "20%" },
            { 'data': "Cantidad", orderable: true, width: "20%" },
            { orderable: false, width: "15%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    buttons += "<button type='button' id='editar' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Editar</button>";
                    buttons += "<button type='button' id='eliminar' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function resetValuesUbicacionMaterial() {
    $("#ubMat_material").empty("");
    $("#ubMat_ubicaciona").empty("");
    $("#ubMat_ubicacionn").empty("");
    $("#ubMat_cantidadnva").val("");
    $("#ubMat_cantidadact").val("");
    $("#ubMat_cantidadnva").prop('disabled', false);
    $("#formUbicacionMaterial").validate().resetForm();
    $("#formUbicacionMaterial :input").removeClass('error');
}

function resetValuesNuevaUbicacionMaterial() {
    $("#ubMat_material_edit").empty("");
    $("#ubMat_ubicaciona_edit").empty("");
    $("#ubMat_ubicacionn_edit").empty("");
    $("#ubMat_cantidadnva").val("");
    $("#ubMat_cantidadact_edit").val("");
    $("#ubMat_cantidadnva_edit").prop('disabled', false);
    $("#formNuevaUbicacionMaterial").validate().resetForm();
    $("#formNuevaUbicacionMaterial :input").removeClass('error');
}

function guardarUbicacionMaterial(accion, idRegistro) {
    var data = $("#formUbicacionMaterial").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    
    $.each(data, function(key, value) {
        datos[value.name] = value.value;
    });
    //console.log(datos);
    //guardar en bd
    $.post("./pages/almacen/ubicacionMaterial/datos.php", datos, function(result) {
        $('#ubicacionMaterialTabla').DataTable().ajax.reload();

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                $("#errorModal .modal-body").text("ERROR DE BASE DE DATOS. "+ result["result"]);
                $("#errorModal").modal("show");
            break;
            case 2:
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function guardarNuevaUbicacionMaterial(accion, idRegistro) {
    var data = $("#formNuevaUbicacionMaterial").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    
    $.each(data, function(key, value) {
        datos[value.name] = value.value;
    });
    console.log(datos);
    //guardar en bd
    $.post("./pages/almacen/ubicacionMaterial/datos.php", datos, function(result) {
        $('#ubicacionMaterialTabla').DataTable().ajax.reload();

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                $("#errorModal .modal-body").text("ERROR DE BASE DE DATOS. "+ result["result"]);
                $("#errorModal").modal("show");
            break;
            case 2:
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function loadEditarUbicacionMaterial(data) {
    //llenaUbicaciones_ub($('#idUbicacionn'));
    $("#ubMat_material_edit").append('<option selected value="'+ data.IdMaterial +'">'+ data.Material +'</option>');
    $("#ubMat_ubicaciona_edit").append('<option selected value="'+ data.IdUbicacion +'">'+ data.Ubicacion +'</option>');
    $("#ubMat_ubicacionn_edit").empty();
    $("#ubMat_material_edit").prop('disabled', true);
    $("#ubMat_cantidadact_edit").val(data.Cantidad);
    $("#ubMat_cantidadnva_edit").val('');
    $("#idMaterialUbicEdit").val(data.IdMaterial);
    $("#nombreMaterial").val(data.Material);
    //idMaterialUbicEdit
    $("#accion").val(1);
    llenaUbicaciones_ub($('#ubMat_ubicacionn_edit'));
    $('#idRegistro').val(data.IdInventario);
    $('#editarUbicacionMaterialModal').modal('show');
    $("#formUbicacionMaterial").validate().resetForm();
    $(".error").removeClass("error");
}

function eliminarRegistro(id, tipo) {
    if (tipo === "1")
        eliminarUbicacionMaterial(id);
    else
        eliminarUbicacion(id);
}

function eliminarUbicacionMaterial(IdUbicacionMaterial) {
    //eliminacion en bd
    var datos ={ "accion":'baja', "id":IdUbicacionMaterial };
    
    $.post("./pages/almacen/ubicacionMaterial/datos.php", datos, function(result) {
        $('#ubicacionMaterialTabla').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
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

function llenaMateriales_ub(ubicMaterial) {
    ubicMaterial.select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/almacen/ubicacionMaterial/datos.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    accion: 'autocompleteMateriales',
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

function llenaUbicaciones_ub(ubicacion) {
    ubicacion.select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/almacen/ubicacionMaterial/datos.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    accion: 'autocompleteUbicaciones',
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