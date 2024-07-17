function autoCompleteProveedoresAP() {
    $('.proveedor_AP').select2( {
        placeholder: "Selecciona una opción",
        tags: true,
        allowClear: true,
        createTag: function (params) {
            $(".proveedor_AP").text("");
            return {
                id: "-1",
                text: params.term
            };
        },
        ajax: {
            url: './pages/compras/autocompleteOC.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'proveedor',
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

function guardarProveedorPrecioUO(idReqDetalle, idMaterial, material, precio, idProveedor, proveedor) {
    var datos = {};
    datos["accion"] = "asigarProveedorUnicaOcasion";
    datos["idMaterial"] = idMaterial;
    datos["material"] = material;
    datos["precio"] = precio;
    datos["idProveedor"] = idProveedor;
    datos["proveedor"] = proveedor;
    datos["idReqDetalle"] = idReqDetalle;
    
    $.post("./pages/compras/requisesp/datos.php", datos, function(result) {
        $('#requisicionesEspecialesTable').DataTable().ajax.reload();

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                msjError = result["result"];
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
            case 2:
                msjError = result["result"];
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function guardarProveedorPrecio(idReqDetalle, idMaterial, material, idProveedor, proveedor) {
    var datos = {};
    datos["accion"] = "asigarProveedor";
    datos["idMaterial"] = idMaterial;
    datos["material"] = material;
    datos["idProveedor"] = idProveedor;
    datos["proveedor"] = proveedor;
    datos["idReqDetalle"] = idReqDetalle;
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
    
    $.post("./pages/compras/requisesp/datos.php", datos, function(result) {
        $('#requisicionesEspecialesTable').DataTable().ajax.reload();

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                msjError = result["result"];
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
            case 2:
                msjError = result["result"];
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}