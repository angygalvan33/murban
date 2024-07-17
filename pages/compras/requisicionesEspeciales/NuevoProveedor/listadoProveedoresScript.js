function getValorDolarMat() {
    var datos ={ "accion":'getDolar' };
    
    $.post("./pages/proveedores/listadoMateriales/datos.php", datos, function(result) {
        if (result["error"] == 0) {
            $(".dolarActual").val(result["result"]);
            $(".labelDolaresMat").text("Dólares ($"+ result["result"] +")");
        }
    }, "json");
}

function monedaRequeridaMat(val, tipo) {
    addRulesMat(val, tipo);
}

function addRulesMat(val, tipo) {
    if (tipo == 0) {
        switch (val) {
            case 'P': //agregar regla de requerido a pesos
                $("#nprecioMat").rules("add", { required: true });
                $("#nprecioMat").removeAttr('disabled', 'disabled');
                $("#dolaresMat").rules('remove', 'required');
                $("#dolaresMat").attr('disabled', 'disabled');
                $("#dolaresMat").removeClass("error");
                $("#dolaresMat").val("");
                $("#nprecioMat").val("");
                $("#nivaMat").attr('disabled', false);
            break;
            case 'D': //agregar regla de requerido a dolares
                $("#dolaresMat").rules("add", { required: true });
                $("#dolaresMat").removeAttr('disabled', 'disabled');
                $("#nprecioMat").rules('remove', 'required');
                $("#nprecioMat").attr('disabled', 'disabled');
                $("#nprecioMat").removeClass("error");
                $("#nprecioMat").val("");
                $("#dolaresMat").val("");
                $("#nivaMat").attr('disabled', true);
            break;
        }
    }
    else {
        switch (val) {
            case 'P': //agregar regla de requerido a pesos
                $("#precioMatM").rules("add", { required: true });
                $("#precioMatM").removeAttr('disabled', 'disabled');
                $("#dolaresMatM").rules('remove', 'required');
                $("#dolaresMatM").attr('disabled', 'disabled');
                $("#dolaresMatM").removeClass("error");
                $("#dolaresMatM").val("");
                $("#precioMatM").val("");
                $("#ivaMatM").attr('disabled', false);
            break;
            case 'D': //agregar regla de requerido a dolares
                $("#dolaresMatM").rules( "add", { required: true });
                $("#dolaresMatM").removeAttr('disabled', 'disabled');
                $("#precioMatM").rules('remove', 'required');
                $("#precioMatM").attr('disabled', 'disabled');
                $("#precioMatM").removeClass("error");
                $("#precioMatM").val("");
                $("#dolaresMatM").val("");
                $("#ivaMatM").attr('disabled', true);
            break;
        }
    }
}

function autocompleteCotizadores() {
    $('.ncotizador').select2( {
        tags: true,
        ajax: {
            url: './pages/proveedores/listadoMateriales/autocompleteCotizadores.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term, //search term
                    IdProveedor: $("#nproveedor").val()
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

function nuevoProveedorByMaterial(idMaterial) {
    var datos = {};
    datos["accion"] = "nuevoProveedorByMaterial";
    datos["idMaterial"] = idMaterial;
    datos["idProveedor"] = $("#nproveedor").val();
    var precio = parseFloat($("#nprecioMat").val().replace(/\,/g, ''));
    
    if ($("#nivaMat").prop("checked")) {
        datos["precio"] =  precio / 1.16;
        datos["iva"] = 1;
    }
    else {
        datos["precio"] = precio;
        datos["iva"] = 0;
    }
    
    datos["dolares"] = $("#dolaresMat").val().replace(/\,/g, '');
    datos["moneda"] = $('input[type=radio][name=monedaMat]:checked').val();
    var dataCot = $('#ncotizador').select2('data');
    
    if (dataCot.length > 0)
        datos["cotizador"] = dataCot[0].id;
    
    resetValuesNuevoProveedor();
    
    $.post("./pages/material/listadoProveedores/datos.php", datos, function(result) {
        $('#listadoProveedoresTable').DataTable().ajax.reload();
        
        if (result["error"] == 0) { //insertado
            $("#successModal .modal-body").text(result["result"]);
            $("#successModal").modal("show");
        }
        else if(result["error"] == 1) {
            $("#errorModal .modal-body").text(result["result"]);
            $("#errorModal").modal("show");
        }
        else if(result["error"] == 2) {
            $("#avisosModal .modal-body").text(result["result"]);
            $("#avisosModal").modal("show");
        }
    }, "json");
}

function resetValuesNuevoProveedor() {
    $("#nproveedor").empty();
    $("#nprecioMat").val("");
    $("#ncotizador").empty();
    $('#nivaMat').prop('checked', false);
    $("input[name$='precioMat']").val("");
    $("input[name$='dolaresMat']").val("");
    $('input[type=radio][name=monedaMat][value=P]').iCheck('uncheck');
    $('input[type=radio][name=monedaMat][value=D]').iCheck('uncheck');
    $("input[name$='precioMat']").rules('remove', 'required');
    $("input[name$='dolaresMat']").rules('remove', 'required');
    $("#precioMat").removeAttr('disabled', 'disabled');
    $("#dolaresMat").removeAttr('disabled', 'disabled');
    $(".radio").css("border","1px solid white");
}

function openPrecioProveedoresMatModal(data) {
    $("#precioMatM").val("");
    $("#dolaresMatM").val("");
    $('#cotizadorMat').empty();
    $('#ivaMat').prop('checked', false);
    $("input[name$='precioMatM']").val("");
    $("input[name$='dolaresMatM']").val("");
    $('input[type=radio][name=monedaMatM][value=P]').iCheck('uncheck');
    $('input[type=radio][name=monedaMatM][value=D]').iCheck('uncheck');
    $("input[name$='precioMatM']").rules('remove', 'required');
    $("input[name$='dolaresMatM']").rules('remove', 'required');
    $("#precioMatM").removeAttr('disabled', 'disabled');
    $("#dolaresMatM").removeAttr('disabled', 'disabled');
    $(".radio").css("border","1px solid white");
    $('#precioProveedorMaterialModal').modal('show');
}
//guarda el precio del material asociado al Proveedor
function guardarPrecioProveedorMat(idMaterial, idProveedor) {
    var datos = {};
    datos["accion"] = "editarPrecio";
    datos["idProveedor"] = idProveedor;
    datos["idMaterial"] = idMaterial;
    var precio_ = $("#precioMatM").val().replace(/\,/g, '');
    
    if($("#ivaMatM").prop("checked")) {
        datos["iva"] = 1;
        datos["precio"] = parseFloat(precio_ / 1.16).toFixed(2);
    }
    else {
        datos["iva"] = 0;
        datos["precio"] = precio_;
    }
    
    datos["dolares"] = $("#dolaresMatM").val().replace(/\,/g, '');
    datos["moneda"] = $('input[type=radio][name=monedaMatM]:checked').val();
    var dataCot = $('#cotizadorMat').select2('data');
    
    if (dataCot.length > 0)
        datos["cotizador"] = dataCot[0].id;
    
    $.post("./pages/material/listadoProveedores/datos.php", datos, function(result) {
        $('#listadoMaterialesTable').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                $("#errorModal .modal-body").text(result["result"]);
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