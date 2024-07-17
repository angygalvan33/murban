function guardarDatosEmpresa() {
    if ($("#datosEmpresaForm").valid()) {
        var data = $("#datosEmpresaForm").serializeArray();
        var datos = {};
        datos["accion"] = "guardarDatos";

        $.each(data, function(key, value) {
            if (value.name == "maximoSinAutorizacionEmpresa")
                datos["maximoSinAutorizacionEmpresa"] = $("#maximoSinAutorizacionEmpresa").val().replace(/\,/g, '');
            else
                datos[value.name] = value.value;
        });

        $.post("./pages/panelAdmin/datos.php", datos, function(result) {
            switch (result["error"]) {
                case 0:
                    $("#successModal .modal-body").text(result["result"]);
                    $("#successModal").modal("show");
                    $("#datosEmpresaForm input").prop("disabled", true);
                break;
                case 1:
                    $("#errorModal .modal-body").text(result["result"]);
                    $("#errorModal").modal("show");
                break;
            }
        }, "json");
    }
}

function editarDatosEmpresa() {
    $("#datosEmpresaForm input").prop("disabled", false);
}

function leerDatosEmpresa() {
    var datos = {};
    datos["accion"] = "leerDatos";

    $.post("./pages/panelAdmin/datos.php", datos, function(result) {
        $("#nombreEmpresa").val(result["nombreEmpresa"]);
        $("#rfcEmpresa").val(result["rfcEmpresa"]);
        $("#emailEmpresa").val(result["emailEmpresa"]);
        $("#direccionEmpresa").val(result["direccionEmpresa"]);
        $("#municipioEmpresa").val(result["municipioEmpresa"]);
        $("#edoEmpresa").val(result["edoEmpresa"]);
        $("#representanteEmpresa").val(result["representanteEmpresa"]);
        $("#telefonoEmpresa").val(result["telefonoEmpresa"]);
        $("#politicasCompras").val(result["politicasCompras"]);
        $("#maximoSinAutorizacionEmpresa").val(result["maximoSinAutorizacionEmpresa"]);
    }, "json");
}

function cambiarLogo() {
    var archivos = $("#fileLogo")[0].files;
    var formData = new FormData();
    
    if (archivos.length > 0) {
        formData.append("accion", "cambiaLogo");
        formData.append("Logo", archivos[0]);
        
        $.ajax( {
            type: 'POST',
            url: './pages/panelAdmin/datos.php',
            data: formData,
            success: function(result) {
                if (result == false) {
                    $("#errorModal .modal-body").text("Error al actualizar logotipo. Asegúrese de seleccionar un archivo PNG / JPG");
                    $("#errorModal").modal("show");
                }
                else {
                    $("#logo").attr("src","./images/logo/"+ result);
                    $("#successModal .modal-body").text("Logotipo actualizado.");
                    $("#successModal").modal("show");
                }
            },
            error: function(response) {
                $("#errorModal .modal-body").text("Error al actualizar logotipo.");
                $("#errorModal").modal("show");
            },

            processData: false,
            contentType: false
        });
    }
    else {
        alert("No se ha seleccionado un archivo válido.");
    }
}

function cargarLogo() {
    var formData = new FormData();
    formData.append("accion", "obtenerLogo");
    
    $.ajax( {
        type: 'POST',
        url: './pages/panelAdmin/datos.php',
        data: formData,
        success: function(result) {
            if (result != false) {
                $("#logo").attr("src", "./cxp"+ result);
            }
        },
        error: function(response) { },
        processData: false,
        contentType: false
    });
}

function guardarPoliticasCompras() {
    var datos = {};
    datos["accion"] = "guardarPoliticasCompras";
    datos["politicas"] = $("#politicasCompras").val();
    
    $.post("./pages/panelAdmin/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $("#politicasCompras").prop("disabled", true);
            break;
            case 1:
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
        }
    });
}

function editarPoliticasCompras() {
    $("#politicasCompras").prop("disabled", false);
}