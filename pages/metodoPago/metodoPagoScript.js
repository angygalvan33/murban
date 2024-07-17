function loadDataTable(permisoAdministrar) {
    $('#mPTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/metodoPago/metodoPagoData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".mPTable-error").html("");
                $("#mPTable").append('<tbody class="mPTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#mPTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Nombre", orderable: true, width: "25%" },
            { 'data': "Referencia", orderable: true, width: "10%" },
            { orderable: false, width: "20%",
                mRender: function (data, type, row) {
                    var buttons = "";

                    if (permisoAdministrar) {
                        buttons += "<button type='button' id='editar' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Editar</button>";
                        buttons += "<button type='button' id='eliminar' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
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
    $("#referencia").val("");
    $("#formMP" ).validate().resetForm();
    $("#formMP :input").removeClass('error');
}

function openModalMP() {
    resetValues();
    $("#accion").val(0);
    $("#idRegistro").val(0);
    $('#nuevoMPModal').modal('show');
}
//accion 0 => guardar, 1 => editar
//idRegistro en editar trae el Id del que se eliminará, en alta viene con 0
function guardarMetodoPago(accion, idRegistro) {
    var data = $("#formMP").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    
    $.each(data, function(key, value) {
        datos[value.name] = value.value;
    });
    //guardar en bd
    $.post("./pages/metodoPago/datos.php", datos, function(result) {
        $('#mPTable').DataTable().ajax.reload();
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
                $("#successModal .modal-body").text("SE HA "+ msj1 +" EL METODO DE PAGO.");
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

function loadEditarMetodoPago(data) {
    $("#nombre").val(data.Nombre);
    $("#referencia").val(data.Referencia);
    $("#accion").val(1);
    $("#idRegistro").val(data.IdMetodoPago);
    $('#nuevoMPModal').modal('show');
    $("#formMP").validate().resetForm();
    $(".error").removeClass("error");
}

function eliminarMetodoPago(idMetodoPago) {
    //eliminacion en bd
    var datos = { "accion":'baja', "id":idMetodoPago };
    
    $.post("./pages/metodoPago/datos.php", datos, function(result) {
        $('#mPTable').DataTable().ajax.reload();
        
        if (result["error"] == 0) { //insertado
            $("#successModal .modal-body").text("SE HA ELIMINADO EL MÉTODO DE PAGO");
            $("#successModal").modal("show");
        }
        else {
            $("#errorModal .modal-body").text("ERROR AL ELIMINAR. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
    }, "json");
}

function eliminarRegistro(id, tipo) {
    eliminarMetodoPago(id);
}