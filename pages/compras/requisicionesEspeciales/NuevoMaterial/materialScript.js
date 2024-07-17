function resetValuesMat() {
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#tipoMedida").val("");
    $("#tiposDinamicos").empty();
    $('.idCategoriaNM').empty();
    $("#formMat").validate().resetForm();
    $("#formMat :input").removeClass('error');
}

function openModalMat() {
    resetValuesMat();
    $("#accion").val(0);
    $("#idRegistro").val(0);
    $("#formMat #nombre").val($(".materialAP").val());
    $('#nuevoMatModal').modal('show');
}
//accion 0 => guardar, 1 => editar
//idRegistro en editar trae el Id del que se eliminará, en alta viene con 0
function guardarMaterial(accion, idRegistro) {
    var data = $("#formMat").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    var inputs = $(".inputDinamico");
    var bandComa = true;
    var medida = "[";

    $.each(inputs, function(key, value) {
        medida += "{'nombre':" + "'"+ value.name +"',";
        medida += "'valor':" + "'"+ value.value +"',";
        medida += "'unidad':" + "'"+ value.id +"'}";
        
        if ($("#tipoMedida").val() == 4 && bandComa == true) {
            medida += ",";
            bandComa = false;
        }
    });

    medida += "]";
    medida = medida.replace(/'/g, '"');
    
    $.each(data, function(key, value) {
        datos[value.name] = value.value;
    });

    datos["medida"] = medida;
    //guardar en bd
    $.post("./pages/material/datos.php", datos, function(result) {
        $('#matTable').DataTable().ajax.reload();
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
                $("#material_AP").val(datos["nombre"]);
                $("#idmaterial_AP").val(result["Id"]);
                $("#msjAltaMat").text(result["result"]);
                
                if (($("#nproveedor").val() != "-1" && $("#nproveedor").val() != null) && $("#idmaterial_AP").val() != "-1")
                    $("#btnAsignar").prop("disabled", false);
                else
                    $("#btnAsignar").prop("disabled", true);
            break;
            case 1:
                $("#msjAltaMat").text(result["result"]);
            break;
            case 2:
                $("#msjAltaMat").text(result["result"]);
            break;
        }
    }, "json");
}

function llenaMedidas() {
    var datos = { "accion":'getMedidas' };

    $("select[name='tipoMedida']").append($("<option>", {
        value: "",
        text: ""
    }));
    
    $.post("./pages/material/datos.php", datos, function(result) {
       $.each(result, function(i, val) {
            $("select[name='tipoMedida']").append($("<option>", {
                value: val.IdMedida,
                text: val.Nombre
            }));
        });
    }, "json");
}
//obtiene el json de acuerdo al id de la medida
function getJsonMedidas(idMedida) {
    var datos = { "accion":'getJsonMedidas', "idMedida":idMedida };
    $("#tiposDinamicos").empty();
    
    $.post("./pages/material/datos.php", datos, function(result) {
        var datos = $.parseJSON(result.Metadato);

        $.each(datos, function(i, val) {
            var inputD = "<div class='col-md-6'><label>"+ val.nombre +"("+ val.unidad +")</label><input id='"+ val.unidad +"' name='"+ val.nombre +"' type='text' value='' class='form-control inputDinamico' required></input></div>"
            $("#tiposDinamicos").append(inputD);
        });
    }, "json");
}

function llenaCategorias() {
    $('.idCategoriaNM').select2( {
        placeholder: "Selecciona una opción",
        ajax: {
            url: './pages/material/datos.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    accion: 'autocompleteCategorias',
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