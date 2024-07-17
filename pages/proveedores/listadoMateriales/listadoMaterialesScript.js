function loadListadoMaterialesDataTable() {
    $('#listadoMaterialesTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/proveedores/listadoMateriales/listadoMaterialesData.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdProveedor": $(".detalles").attr("id")
            },
            error: function() { //error handling
                $(".listadoMaterialesTable-error").html("");
                $("#listadoMaterialesTable").append('<tbody class="listadoMaterialesTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#listadoMaterialesTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Material", orderable: true, width: "20%" },
            { 'data': "Medida", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    var html = "<p>";
                    
                    if (row.Medida != "") {
						try {
                            var datos = $.parseJSON(row.Medida);
                            $.each(datos, function(i, val) {
                                html += "<i>"+ val.nombre +"</i>: "+ val.valor + val.unidad +" ";
                            });
						}
						catch {
						   html += "El material se guardo incorrectamente para agregar el precio es, necesario modificarlo en el catalogo de materiales.";
						}
                    }

                    html += "";
                    return html;
                 }
            },
            { 'data': "Precio", orderable: true, width: "15%", className:"alinearDerecha",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.Precio);
                }
            },
            { 'data': "Creado", orderable: true, width: "15%" },
            { 'data': "Cotizador", orderable: true, width: "20%" },
            { orderable: false, width: "20%",
                mRender: function (data, type, row) {
                    var buttons = "<button type='button' id='editarPrecio' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Editar precio</button>";
                    buttons+= "<button type='button' id='historico' class='btn btn-warning btn-sm'><i class='fa fa-history'></i>&nbsp;Histórico</button>";
                    buttons+= "<button type='button' id='eliminarPrecios' style='margin-right:5px' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function openPrecioMaterialModal(data) {
    $("#precioM").val("");
    $("#dolaresM").val("");
    $('#cotizador').empty();
    $('#iva').prop('checked', false);
    $("input[name$='precioM']").val("");
    $("input[name$='dolaresM']").val("");
    $('input[type=radio][name=monedaM][value=P]').iCheck('uncheck');
    $('input[type=radio][name=monedaM][value=D]').iCheck('uncheck');
    $("input[name$='precioM']").rules('remove', 'required');
    $("input[name$='dolaresM']").rules('remove', 'required');
    $("#precioM").removeAttr('disabled', 'disabled');
    $("#dolaresM").removeAttr('disabled', 'disabled');
    $(".radio").css("border","1px solid white");
    $('#precioMaterialModal').modal('show');
}
//guarda el precio del material asociado al Proveedor
function guardarPrecioMaterial(idMaterial, idProveedor) {
    var datos = {};
    datos["accion"] = "historico";
    datos["idProveedor"] = idProveedor;
    datos["idMaterial"] = idMaterial;
    var precio_ = $("#precioM").val().replace(/\,/g, '');
    
    if ($("#iva").prop("checked")) {
        datos["iva"] = 1;
        datos["precio"] = parseFloat(precio_ / 1.16).toFixed(2);
    }
    else {
        datos["iva"] = 0;
        datos["precio"] = precio_;
    }
    
    datos["dolares"] = $("#dolaresM").val().replace(/\,/g, '');
    datos["moneda"] = $('input[type=radio][name=monedaM]:checked').val();
    
    var dataCot = $('#cotizador').select2('data');
    
    if (dataCot.length > 0)
        datos["cotizador"] = dataCot[0].id;
    
    $.post("./pages/proveedores/listadoMateriales/datos.php", datos, function(result) {
        switch (result["error"]) {
            case "0":
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
				$('#listadoMaterialesTable').DataTable().ajax.reload();
            break;
            case "1":
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
            case "2":
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function eliminarMaterial(idProveedor, idMaterial) {
    var datos = { "accion":'baja', "idProveedor":idProveedor, "idMaterial":idMaterial };
    
    $.post("./pages/proveedores/listadoMateriales/datos.php", datos, function(result) {
        $('#listadoMaterialesTable').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA ELIMINADO EL MATERIAL.");
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

function resetValuesNuevoMaterial() {
    $("#nmaterial").empty();
    $("#nprecio").val("");
    $("#ncotizador").empty();
    $('#niva').prop('checked', false);
    $("input[name$='precio']").val("");
    $("input[name$='dolares']").val("");
    $('input[type=radio][name=moneda][value=P]').iCheck('uncheck');
    $('input[type=radio][name=moneda][value=D]').iCheck('uncheck');
    $("input[name$='precio']").rules('remove', 'required');
    $("input[name$='dolares']").rules('remove', 'required');
    $("#precio").removeAttr('disabled', 'disabled');
    $("#dolares").removeAttr('disabled', 'disabled');
    $(".radio").css("border","1px solid white");
}
//agrega un nuevo material al idProveedor especificado
function nuevoMaterialByProveedor(idProveedor) {
    var datos = {};
    datos["accion"] = "primeraVezHistorico";
    datos["idProveedor"] = idProveedor;
    datos["idMaterial"] = $("#nmaterial").val();
    var precio = parseFloat($("#nprecio").val().replace(/\,/g, ''));
    
    if ($("#niva").prop("checked")) {
        datos["precio"] =  precio / 1.16;
        datos["iva"] = 1;
    }
    else {
        datos["precio"] = precio;
        datos["iva"] = 0;
    }
    
    datos["dolares"] = $("#dolares").val().replace(/\,/g, '');
    datos["moneda"] = $('input[type=radio][name=moneda]:checked').val();
    
    var dataCot = $('#ncotizador').select2('data');

    if (dataCot.length > 0)
        datos["cotizador"] = dataCot[0].id;
    
    resetValuesNuevoMaterial();

    $.post("./pages/proveedores/listadoMateriales/datos.php", datos, function(result) {
        $('#listadoMaterialesTable').DataTable().ajax.reload();
        
        if (result["error"] == 0) { //insertado
            $("#successModal .modal-body").text(result["result"]);
            $("#successModal").modal("show");
        }
        else if (result["error"] == 1) {
            $("#errorModal .modal-body").text(result["result"]);
            $("#errorModal").modal("show");
        }
        else if (result["error"] == 2) {
            $("#avisosModal .modal-body").text(result["result"]);
            $("#avisosModal").modal("show");
        }
    }, "json");
}

function getValorDolar() {
    var datos = { "accion":'getDolar' };
    
    $.post("./pages/proveedores/listadoMateriales/datos.php", datos, function(result) {
        if (result["error"] == 0) {
            $(".dolarActual").val(result["result"]);
            $(".labelDolares").text("Dólares ($"+ result["result"] +")");
        }
    }, "json");
}

function monedaRequerida(val, tipo) {
    addRules2(val, tipo);
}

function addRules2(val, tipo) {
    if (tipo == 0) {
        switch (val) {
            case 'P': //agregar regla de requerido a pesos
                $("#nprecio").rules( "add", { required: true });
                $("#nprecio").removeAttr('disabled', 'disabled');
                $("#dolares").rules('remove', 'required');
                $("#dolares").attr('disabled', 'disabled');
                $("#dolares").removeClass("error");
                $("#dolares").val("");
                $("#nprecio").val("");
                $("#niva").attr('disabled', false);
            break;
            case 'D': //agregar regla de requerido a dolares
                $("#dolares").rules( "add", { required: true });
                $("#dolares").removeAttr('disabled', 'disabled');
                $("#nprecio").rules('remove', 'required');
                $("#nprecio").attr('disabled', 'disabled');
                $("#nprecio").removeClass("error");
                $("#nprecio").val("");
                $("#dolares").val("");
                $("#niva").attr('disabled', true);
            break;
        }
    }
    else {
        switch (val) {
            case 'P': //agregar regla de requerido a pesos
                $("#precioM").rules("add", {
                    required: true
                });

                $("#precioM").removeAttr('disabled', 'disabled');
                $("#dolaresM").rules('remove', 'required');
                $("#dolaresM").attr('disabled', 'disabled');
                $("#dolaresM").removeClass("error");
                $("#dolaresM").val("");
                $("#precioM").val("");
                $("#ivaM").attr('disabled', false);
            break;
            case 'D': //agregar regla de requerido a dolares
                $("#dolaresM").rules( "add", { required: true });
                $("#dolaresM").removeAttr('disabled', 'disabled');
                $("#precioM").rules('remove', 'required');
                $("#precioM").attr('disabled', 'disabled');
                $("#precioM").removeClass("error");
                $("#precioM").val("");
                $("#dolaresM").val("");
                $("#ivaM").attr('disabled', true);
            break;
        }
    }
}