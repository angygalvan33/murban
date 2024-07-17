/*ADMINISTRACION USUARIOS*/
function loadDataTableUsuarios() {
    $('#usuariosTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/usuarios/usuariosData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".usuariosTable-error").html("");
                $("#usuariosTable").append('<tbody class="usuariosTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#usuariosTable_processing").css("display", "none");
            }
        },
        'columns': [
            { "data": "Nombre", sortable: false, width: "20%" },
            { "data": "Email", sortable: false, width: "15%" },
            { "data": "Usuario", sortable: false, width: "15%" },
            {
                mRender: function (data, type, row) {
                    if (row.Activo == 1)
                        return "<input class='edo icheckbox_flat-green' checked type='checkbox'>";
                    else
                        return "<input class='edo icheckbox_flat-green' type='checkbox'>";
                 }, sortable: false, width: "5%"
            },
            {
                mRender: function (data, type, row) {
                    var buttons = "<button type='button' id='verPermisos' style='margin-right:5px' class='btn btn-primary btn-sm'><i class='fa fa-list'></i>&nbsp;Permisos</button>";
                    buttons += "<button type='button' id='editar' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Editar</button>";
                    buttons += "<button type='button' id='restablecer' style='margin-right:5px' class='btn btn-warning btn-sm'><i class='fa fa-key'></i>&nbsp;Restablecer contraseña</button>";
                    buttons += "<button type='button' id='eliminar' style='margin-right:5px' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return buttons;
                }, sortable: false, width: "45%"
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function openModalUsuario() {
    resetValues(1);
    $("#accion").val(0);
    $("#idRegistro").val(0);
    $('#nuevoUsuarioModal').modal('show');
}

function loadEditarUsuario(data) {
    $("input[name$='nombre']").val(data.Nombre);
    $("input[name$='email']").val(data.Email);
    $("input[name$='usuario']").val(data.Usuario);
    $("#accion").val(1);
    $("#idRegistro").val(data.IdUsuario);
    $('#nuevoUsuarioModal').modal('show');
    $("#formUsuario").validate().resetForm();
    $(".error").removeClass("error");
}
//accion 0 => guardar, 1 => editar
//idRegistro en editar trae el Id del que se eliminará, en alta viene con 0
function guardarUsuario(accion, idRegistro) {
    var data = $("#formUsuario").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;

    $.each(data, function(key, value) {
        datos[value.name] = value.value;
    });
    
    var nuevoID = { id:0 };

    if (accion == 0)
        obtenerNuevoID(nuevoID);
    //guardar en bd
    $.post("./pages/usuarios/datosUsuarios.php", datos, function(result) {
        $('#usuariosTable').DataTable().ajax.reload();
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
                if (accion == 0)
                    loadEditarPermisos(nuevoID.id, 0);
                else {
                    $("#successModal .modal-body").text("SE HA "+ msj1 +" EL USUARIO.");
                    $("#successModal").modal("show");
                }
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

function obtenerNuevoID(nuevoID) {
    var datos = { "accion":'obtenerNuevoID' };
    
    $.post("./pages/usuarios/datosUsuarios.php", datos, function(result) {
        nuevoID.id = result["result"];
    }, "json");
}

function restablecerPassword(idUsuario) {
    var datos = { "accion":'restablecerPassword', "idUsuario":idUsuario };
    
    $.post("./pages/usuarios/datosUsuarios.php", datos, function(result) {
        if (result["error"] == 0) {
            $("#successModal .modal-body").text("SE HA RESTABLECIDO LA CONTRASEÑA. TE HA LLEGADO UN CORREO ELECTRÓNICO CON LA INFORMACIÓN DE ACCESO.");
            $("#successModal").modal("show");
        }
        else {
            $("#errorModal .modal-body").text("ERROR AL RESTABLECER CONTRASEÑA. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
    }, "json");
}
//cambio de estado, recibe el idUsuario y edo = true/false
function cambioEdoUsuario(idUsuario, edo) {
    if (edo == true)
        var datos = { "accion":'activarUsuario', "idUsuario":idUsuario };
    else
        var datos = { "accion":'desactivarUsuario', "idUsuario":idUsuario };
    
    $.post("./pages/usuarios/datosUsuarios.php", datos, function(result) {
        if (result["error"] == 1) {
            $("#errorModal .modal-body").text("ERROR AL CAMBIAR EL ESTADO DEL USUARIO. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
    }, "json");
}

function resetValues(tipo) {
    //usuario
    if (tipo == 1) {
        $("input[name$='nombre']" ).val("");
        $("input[name$='email']" ).val("");
        $("input[name$='usuario']" ).val("");
        $("#formUsuario" ).validate().resetForm();
        $("#formUsuario :input").removeClass('error');
    }
}

function loadEditarPermisos(idUsuario, permisos) {
    $("#idRegistro").val(idUsuario);
    $("#numPermisos").val(permisos);
    $("#treeview-checkable").prop("innerHTML", "Cargando...");
    loadCheckableTree();
    $('#editarPermisosModal').modal('show');
    $("#formPermisos").validate().resetForm();
    $(".error").removeClass("error");
}

function editarPermisos(idRegistro, numPermisos) {
    var datos = {};
    datos["accion"] = 'editarPermisos';
    datos["idUsuario"] = idRegistro;
    datos["numPermisos"] = numPermisos;
    
    $.post("./pages/usuarios/datosUsuarios.php", datos, function(result) {
        $('#usuariosTable').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HAN EDITADO LOS PERMISOS DEL USUARIO.");
                $("#successModal").modal("show");
            break;
            case 1:
                $msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR AL EDITAR LOS PERMISOS. ERROR DE BASE DE DATOS. "+ $msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}

function eliminarUsuario(idUsuario) {
    var datos = {};
    datos["accion"] = 'eliminarUsuario';
    datos["idUsuario"] = idUsuario;
    
    $.post("./pages/usuarios/datosUsuarios.php", datos, function(result) {
        $('#usuariosTable').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                $msjError = result["result"];
                $("#errorModal .modal-body").text($msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}

function eliminarRegistro(id, tipo) {
    eliminarUsuario(id);
}