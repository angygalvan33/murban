function loadDataTable() {
    $('#linTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/articulos/lineas/lineasData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".linTable-error").html("");
                $("#linTable").append('<tbody class="linTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#linTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Nombre", orderable: true, width: "25%" },
            { orderable: false,  width: "20%",
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

function resetValues() {
    $("#nombre").val("");
    $("#formLin" ).validate().resetForm();
    $("#formLin :input").removeClass('error');
}

function openModalLin() {
    resetValues();
    $("#accion").val(0);
    $("#idRegistro").val(0);
    $('#nuevaLinModal').modal('show');
}
//accion 0=>guardar, 1=>editar
//idRegistro en editar trae el Id del que se eliminará, en alta viene con 0
function guardarLinea(accion, idRegistro) {
    var data = $("#formLin").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    
    $.each(data, function(key, value) {
        datos[value.name] = value.value;
    });
    //guardar en bd
    $.post("./pages/articulos/lineas/datos.php", datos, function(result) {
        $('#linTable').DataTable().ajax.reload();

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
                $("#successModal .modal-body").text("SE HA "+ msj1 +" LA LÍNEA.");
                $("#successModal").modal("show");
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

function loadEditarLinea(data) {
    $("#nombre").val(data.Nombre);
    $("#accion").val(1);
    $("#idRegistro").val(data.IdLinea);
    $('#nuevaLinModal').modal('show');
    $("#formLin").validate().resetForm();
    $(".error").removeClass("error");
}

function eliminarLinea(idLinea) {
    //eliminacion en bd
    var datos = { "accion":'baja', "id":idLinea };
    
    $.post("./pages/articulos/lineas/datos.php", datos, function(result) {
        $('#linTable').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA ELIMINADO LA LÍNEA.");
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