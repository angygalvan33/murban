function resetValuesAltaP() {
    $("#nombre").val("");
    $("#direccion").val("");
    $("#representante").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#rfc").val("");
    $("#diasCredito").val("");
    $("#limiteCredito").val("");
    $("#formProv" ).validate().resetForm();
    $("#formProv :input").removeClass('error');
}

function openModalMP(prov) {
    resetValuesAltaP();
    var dataP = prov.select2('data');
    if (dataP.length > 0)
        $("#nombre").val(dataP[0].text);

    $("#accion").val(0);
    $("#idRegistro").val(0);
    $('#nuevoProvModal').modal('show');
}
//accion 0 => guardar, 1 => editar
//idRegistro en editar trae el Id del que se eliminará, en alta viene con 0
function guardarProveedor(accion,idRegistro) {
    var data = $("#formProv").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    
    $.each(data, function(key, value) {
        if (value.name == "limiteCredito")
            datos[value.name] = value.value.replace(/\,/g, '');
        else
            datos[value.name] = value.value;
    });
    //guardar en bd
    $.post("./pages/proveedores/datos.php", datos, function(result) {
        $('#provTable').DataTable().ajax.reload();
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
                var newOption = new Option(datos["nombre"], result["Id"], true, true);
                $(".proveedor_AP").append(newOption).trigger('change');
                autocompleteCotizadores();
                $("#msjAltaProv").text(result["result"]);
                $("#msjAltaProvUO").text(result["result"]);
            break;
            case 1:
                $("#msjAltaProv").text(result["result"]);
                $("#msjAltaProvUO").text(result["result"]);
            break;
            case 2:
                $("#msjAltaProv").text(result["result"]);
                $("#msjAltaProvUO").text(result["result"]);
            break;
        }
    }, "json");
}