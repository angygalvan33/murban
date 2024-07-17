function loadListadoMaterialesDataTableKg() {
    $('#listadoMaterialesTableKg').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/proveedores/listadoMaterialesKg/listadoMaterialesDataKg.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdProveedor": $(".detalles").attr("id")
            },
            error: function(){ //error handling
                $(".listadoMaterialesTable-error").html("");
                $("#listadoMaterialesTableKg").append('<tbody class="listadoMaterialesTableKg-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#listadoMaterialesTableKg_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Material", orderable: true, width: "25%" },
            { 'data': "Medida", orderable: true, width: "20%",
                mRender: function (data, type, row) {
                    var html = "<p>";

                    if (row.Medida != "") {
                        var datos = $.parseJSON(row.Medida);
                        $.each (datos, function(i, val) {
                           html += "<i>"+ val.nombre +"</i>: "+ val.valor + val.unidad +" ";
                        });
                    }
                    
                    html += "";
                    return html;
                }
            },
            { 'data': "Precio", orderable: true, width: "20%", className:"alinearDerecha",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.Precio);
                }
            },
            { 'data': "Cotizador", orderable: true, width: "20%" },
            { orderable: false,
                mRender: function (data, type, row) {
                    var buttons = "<button type='button' id='eliminarPreciosKg' style='margin-right:5px' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function openPrecioMaterialModalKg(data) {
    $("#precioMKg").val("0");
    $("#dolaresMKg").val("0");
    $('#cotizadorKg').empty();
    $('#ivaKg').prop('checked', false);
    $("input[name$='precioMKg']").val( "0" );
    $("input[name$='dolaresMKg']").val( "0" );
    $('input[type=radio][name=monedaMKg][value=P]').iCheck('uncheck');
    $('input[type=radio][name=monedaMKg][value=D]').iCheck('uncheck');
    $("input[name$='precioMKg']").rules('remove', 'required');
    $("input[name$='dolaresMKg']").rules('remove', 'required');
    $("#precioMKg").removeAttr('disabled', 'disabled');
    $("#dolaresMKg").removeAttr('disabled', 'disabled');
    $(".radio").css("border", "1px solid white");
    $('#precioMaterialModalKg').modal('show');
}

function openEditPrecioMaterialModalKg(idprecioxkilo, idprov, precio, iva, moneda) {
    $("#editidProveedorKg").val(idprov);
    $("#editidPrecioxkilo").val(idprecioxkilo);
    $('#editprecioMKg').val(precio);
    $('#editIva').val(iva);
    $('#editMoneda').val(moneda);
    $('#editprecioMaterialModalKg').modal('show');
}
//guarda el precio del material asociado al Proveedor
function guardarPrecioMaterialKg(idMaterial, idProveedor, idPrecio) {
    var datos = {};
    datos["accion"] = "asignarmat";
    datos["idProveedor"] = idProveedor;
    datos["idMaterial"] = idMaterial;
	datos["idPrecio"] = idPrecio;
    
    $.post("./pages/proveedores/listadoMaterialesKg/datosKg.php", datos, function(result) {
        $('#listadoMaterialesTableKg').DataTable().ajax.reload();

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
//guarda el precio del material asociado al Proveedor
function editPrecioxKilo(idPrecioxkilo, idProveedor, Moneda, Iva, PrecioxKilo) {
    var datos = {};
    datos["accion"] = "editarprecio";
    datos["idPrecio"] = idPrecioxkilo;
    datos["idProveedor"] = idProveedor;
    datos["moneda"] = Moneda;
    datos["iva"] = Iva;
	datos["precioxkilo"] = PrecioxKilo;
	
    $.post("./pages/proveedores/listadoMaterialesKg/datosKg.php", datos, function(result) {
        $('#listadoMaterialesTableKg').DataTable().ajax.reload();

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

function eliminarMaterialKg(idProveedor, idMaterial) {
    //eliminacion en bd
    var datos = { "accion":'baja', "idProveedor":idProveedor, "idMaterial":idMaterial };
    
    $.post("./pages/proveedores/listadoMaterialesKg/datosKg.php", datos, function(result) {
        $('#listadoMaterialesTableKg').DataTable().ajax.reload();
        
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

function resetValoresPrecioKg() {
	$("#cotizadorMKg").empty();
	$('input[type=radio][name=monedaMKg][value=P]').iCheck('uncheck');
    $('input[type=radio][name=monedaMKg][value=D]').iCheck('uncheck');
	$("input[name$='precioMKg']").val("0");
    $("input[name$='dolaresMKg']").val("0");
	$("input[name$='precioMKg']").rules('remove', 'required');
    $("input[name$='dolaresMKg']").rules('remove', 'required');
    $("#precioMKg").removeAttr('disabled', 'disabled');
    $("#dolaresMKg").removeAttr('disabled', 'disabled');
    $(".radio").css("border","1px solid white");
}
//agrega un nuevo material al idProveedor especificado
function nuevoPrecioxKilo() {
    var datos = {};
    datos["accion"] = "primeraVezHistorico";
    var precio = parseFloat($("#precioMKg").val().replace(/\,/g, ''));
    
    if($("#ivaMKg").prop("checked")) {
        datos["precio"] =  precio / 1.16;
        datos["iva"] = 1;
    }
    else {
        datos["precio"] = precio;
        datos["iva"] = 0;
    }
    
    datos["dolares"] = parseFloat($("#dolaresMKg").val().replace(/\,/g, ''));
    datos["moneda"] = $('input[type=radio][name=monedaMKg]:checked').val();
    
    var dataCot = $('#cotizadorMKg').select2('data');
    
    if (dataCot.length > 0)
        datos["cotizador"] = dataCot[0].id;
    
    resetValoresPrecioKg();
    datos["IdProveedor"] = $('.detalles').attr('id');
	
    $.post("./pages/proveedores/listadoMaterialesKg/datosKg.php", datos, function(result) {
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
    
    $.post("./pages/proveedores/listadoMaterialesKg/datosKg.php", datos, function(result) {
        if (result["error"] == 0) {
            $(".dolarActualKg").val(result["result"]);
            $(".labelDolaresKg").text("Dólares ($"+ result["result"] +")");
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
                $("#nprecioKg").rules( "add", { required:true });
                $("#nprecioKg").removeAttr('disabled', 'disabled');
                $("#dolaresKg").rules('remove', 'required');
                $("#dolaresKg").attr('disabled', 'disabled');
                $("#dolaresKg").removeClass("error");
                $("#dolaresKg").val("0");
                $("#nprecioKg").val("0");
                $("#nivaKg").attr('disabled', false);
            break;
            case 'D': //agregar regla de requerido a dolares
                $("#dolaresKg").rules( "add", { required:true });
                $("#dolaresKg").removeAttr('disabled', 'disabled');
                $("#nprecioKg").rules('remove', 'required');
                $("#nprecioKg").attr('disabled', 'disabled');
                $("#nprecioKg").removeClass("error");
                $("#nprecioKg").val("0");
                $("#dolaresKg").val("0");
                $("#nivaKg").attr('disabled', true);
            break;
        }
    }
    else {
        switch (val) {
            case 'P': //agregar regla de requerido a pesos
                $("#precioMKg").rules( "add", { required:true });
                $("#precioMKg").removeAttr('disabled', 'disabled');
                $("#dolaresMKg").rules('remove', 'required');
                $("#dolaresMKg").attr('disabled', 'disabled');
                $("#dolaresMKg").removeClass("error");
                $("#dolaresMKg").val("0");
                $("#precioMKg").val("0");
                $("#ivaMKg").attr('disabled', false);
            break;
            case 'D': //agregar regla de requerido a dolares
                $("#dolaresMKg").rules( "add", { required:true });
                $("#dolaresMKg").removeAttr('disabled', 'disabled');
                $("#precioMKg").rules('remove', 'required');
                $("#precioMKg").attr('disabled', 'disabled');
                $("#precioMKg").removeClass("error");
                $("#precioMKg").val("0");
                $("#dolaresMKg").val("0");
                $("#ivaMKg").attr('disabled', true);
            break;
        }
    }
}