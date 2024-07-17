function resetValuesMaterialesPrestamo() {
    $("#idMaterial").empty();
    $("#cantidad").val("");
    $("#diasPrestamo").val("");
    $("#idPersonal").empty();
    $("#descripcion").val("");
    var hoy = moment().format("DD/MM/YYYY");
    $('#fecha').val(hoy);
    $('#fechaH').val(hoy);
    $('input[type=radio][name=tipoPrestamo][value=P]').iCheck('uncheck');
    $('input[type=radio][name=tipoPrestamo][value=R]').iCheck('uncheck');
    $("#idMaterial").prop("disabled", false);
    $("#mostrarDias").css("display", "none");
    $("#formPrestamo" ).validate().resetForm();
    $("#formPrestamo :input").removeClass('error');
}

function llenaMaterialesEPrestamo() {
    $('#idMaterial').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/materialesEnPrestamo/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'material',
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

function llenaEPersonal() {
    $('#idPersonal').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/materialesEnPrestamo/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'personal',
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
//nuevo-editar material
function guardarMaterialPR(accion) {
    var data = $("#formPrestamo").serializeArray();
    var datos = {};
    datos["accion"] = accion === "0" ? "guardarMaterial" : "editarMaterial";
    
    $.each(data, function(key, value) {
        datos[value.name] = value.value;
    });
    
    datos["cantidad"] = $("#cantidad").val().replace(/\,/g, '');
    datos["idMaterial"] = $("#idMaterial").val();
    datos["idPersonal"] = $("#idPersonal").val();
    datos["fecha"] = $("#fechaH").val();
    //datos["nombreMaterial"] = $("#nombreMaterial").val();
    console.log(datos);
    $.post("./pages/materialesEnPrestamo/datos.php", datos, function(result) {
        $('#MaterialPrestamoPTabla').DataTable().ajax.reload();
        $('#PersonalResguardoTabla').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                var msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}
//recepcion de material (tipo 1-> prestamo, 2->resguardo)
function recibirMaterial(idDetalle,tipo, idPersonal, idMaterial, cantidad, fecha) {
    var datos = { "accion":'recibir', "idDetalle":idDetalle, "idMaterial":idMaterial, "idPersonal":idPersonal, "cantidad":cantidad, "fecha":fecha };
    
    if (tipo === "1")
        datos["tipo"] = "P";
    else
        datos["tipo"] = "R";
    
    $.post("./pages/materialesEnPrestamo/datos.php", datos, function(result) {
        $('#MaterialPrestamoPTabla').DataTable().ajax.reload();
        $('#PersonalResguardoTabla').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                var msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}

function getCantidadMaterialActual(idMaterial, nombreMaterial) {
    var datos = { "accion":'getCantidadMaterial', "idMaterial":idMaterial, "NombreMaterial":nombreMaterial };
    
    $.post("./pages/materialesEnPrestamo/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#cantidadMaterialPrestamoInformativo").text("Cantidad "+ result["result"]);
            break;
            case 1:
            break;
        }
    }, "json");
}