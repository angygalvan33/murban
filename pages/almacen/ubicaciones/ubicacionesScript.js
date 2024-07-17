function inicializaTablaUbicaciones() {
    $('#ubicacionesTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/ubicaciones/ubicacionesData.php", //json datasource
            type: "post", //method, by default get
            error: function(){ //error handling
                $(".ubicacionesTabla-error").html("");
                $("#ubicacionesTabla").append('<tbody class="ubicacionesTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#ubicacionesTabla_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Nombre", orderable: true, width: "25%" },
            { 'data': "Descripcion", orderable: true, width: "20%" },
            { orderable: false, width: "15%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    buttons += "<button type='button' id='ubeditar' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Transferencia</button>";
                    buttons += "<button type='button' id='ubeliminar' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function resetValuesUbicacion() {
    $("#nombreUbicacion").val("");
    $("#descripcionUbicacion").val("");
    $("#formUbicacion" ).validate().resetForm();
    $("#formUbicacion :input").removeClass('error');
}

function guardarUbicacion(accion, idRegistro) {
    var data = $("#formUbicacion").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    
    $.each(data, function(key, value) {
        datos[value.name] = value.value;
    });
    //guardar en bd
    $.post("./pages/almacen/ubicaciones/datos.php", datos, function(result) {
        $('#ubicacionesTabla').DataTable().ajax.reload();

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

function loadEditarUbicacion(data) {
    $("#nombreUbicacion").val(data.Nombre);
    $("#descripcionUbicacion").val(data.Descripcion);
    $("#accion").val(1);
    $('#idRegistro').val(data.IdUbicacion);
    $('#nuevaUbicacionModal').modal('show');
    $("#formUbicacion").validate().resetForm();
    $(".error").removeClass("error");
}

function eliminarUbicacion(idUbicacion) {
    //eliminacion en bd
    var datos ={ "accion":'baja', "id":idUbicacion };
    
    $.post("./pages/almacen/ubicaciones/datos.php", datos, function(result) {
        $('#ubicacionesTabla').DataTable().ajax.reload();
        
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