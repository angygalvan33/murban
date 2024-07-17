function loadDataTable(permisoAdministrar) {
    $('#catTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/categoria/categoriaData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".catTable-error").html("");
                $("#catTable").append('<tbody class="catTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#catTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Nombre", orderable: true, width: "25%" , className: 'details-control' },
            { orderable: false,  width: "20%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    if(permisoAdministrar) {
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
    $("#formCat").validate().resetForm();
    $("#formCat :input").removeClass('error');
}

function openModalCat() {
    resetValues();
    $("#accion").val(0);
    $("#idRegistro").val(0);
    $('#nuevaCatModal').modal('show');
}
//accion 0 => guardar, 1 => editar
//idRegistro en editar trae el Id del que se eliminará, en alta viene con 0
function guardarCategoria(accion, idRegistro) {
    var data = $("#formCat").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    
    $.each(data, function(key, value) {
        datos[value.name] = value.value;
    });
    //guardar en bd
    $.post("./pages/categoria/datos.php", datos, function(result) {
        $('#catTable').DataTable().ajax.reload();
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

        switch(result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA "+ msj1 +" LA CATEGORIA.");
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
    },"json");
}

function loadEditarCategoria(data) {
    $("#nombre").val(data.Nombre);
    $("#accion").val(1);
    $("#idRegistro").val(data.IdCategoria);
    $('#nuevaCatModal').modal('show');
    $("#formCat").validate().resetForm();
    $(".error").removeClass("error");
}

function eliminarCategoria(idCategoria) {
    //eliminacion en bd
    var datos = { "accion":'baja', "id":idCategoria };
    
    $.post("./pages/categoria/datos.php", datos, function(result) {
        $('#catTable').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA ELIMINADO LA CATEGORIA.");
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

function eliminarRegistro(id, tipo) {
    eliminarCategoria(id);
}