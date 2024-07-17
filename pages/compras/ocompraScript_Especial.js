var subtotalEspecial = 0;
var ivaEspecial = 0;
var totalEspecial = 0;
var actualRowEspecial;

function getTotalEspecial() {
    return totalEspecial;
}

function inicializaMaterialesByProveedorEspecial() {
    $('#matsEspecial').DataTable( {
        'lengthMenu': [ [10,15, 25, -1], [10,15, 25, "Todos"] ],
        'data': [],
        'columns': [
            { 'data': "Cantidad", sortable: false, width: "10%", orderable: false },
            { 'data': "Nombre", sortable: false, width: "20%", orderable: true },
            { 'data': "Precio", sortable: false, width: "15%",orderable: true, className: 'alinearDerecha',
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.Precio);
                }
            },
            {
                mRender: function (data, type, row) {
                   var costo = parseFloat(row.Cantidad) * parseFloat(row.Precio);
                   return formatNumber(costo.toFixed(4));
                }, width: "15%",sortable: false,
            },
            { 'data': "Obra", sortable: false, width: "15%", orderable: true },
            { 'data': "Solicita", sortable: false, width: "15%", orderable: true },
            {
                mRender: function (data, type, row) {
                    var button = "<button type='button' id='eliminarEspecial' style='margin-right:5px' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return button;
                }, width: "10%", sortable: false,
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        },
        'footerCallback': function (row, data, start, end, display) {
            var api = this.api(), data;
            var costoEspecial = 0;
            subtotalEspecial = 0;
            $.each(data, function(key, value) {
                costoEspecial = parseFloat(value.Cantidad) * parseFloat(value.Precio);
                subtotalEspecial += costoEspecial;
            });
            //Update footer
            $(api.column(3).footer()).html(
                subtotalEspecial.toFixed(3)
            );
            
            ivaEspecial = subtotalEspecial * 0.16;
            totalEspecial = subtotalEspecial + ivaEspecial;
            $("#subtotalEspecial").text("$"+ formatNumber(subtotalEspecial.toFixed(3)));
            $("#ivaEspecial").text("$"+ formatNumber(ivaEspecial.toFixed(3)));
            $("#totalEspecial").text("$"+ formatNumber(totalEspecial.toFixed(3)));
        }
    });
}

function inicializaVistaPreviaOC() {
    $('#ocCompleteTabla').DataTable( {
        'lengthMenu': [ [10,15, 25, -1], [10,15, 25, "Todos"] ],
        'data': [],
        'columns': [
            { 'data': "Cantidad", sortable: false, width: "10%", orderable: false },
            { 'data': "Nombre", sortable: false, width: "30%", orderable: true },
            { 'data': "Precio", sortable: false, width: "15%",orderable: true, className: 'alinearDerecha' },
            {
                mRender: function (data, type, row) {
                   var costo = parseFloat(row.Cantidad) * parseFloat(row.Precio);
                   return formatNumber(costo.toFixed(3));
                }, width: "15%", sortable: false,
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function agregaDetalleOC_Especial() {
    if ($("#formOC_Especial").valid()) {
        var dataM = $('#materialEspecial').select2('data');
        var dataO = $('#obraEspecial').select2('data');
        var dataS = $('#solicitaEspecial').select2('data');
        var nuevo = {};
        nuevo["IdObra"] = dataO[0].id;
        nuevo["Obra"] = dataO[0].text;
        nuevo["Cantidad"] = $("#cantidadEspecial").val().replace(/\,/g, '');
        nuevo["IdMaterial"] = dataM[0].id;
        nuevo["Nombre"] = dataM[0].text;
        nuevo["Precio"] = $("#precioEspecial").val().replace(/\,/g, '');
        nuevo["Solicita"] = dataS[0].text;
        nuevo["IdSolicita"] = dataS[0].id;
        nuevo["IdReqDetalle"] = 0;
        var archivos = $("#adjunto")[0].files;
        
        if (archivos.length > 0)
            nuevo["Adjunto"] = archivos[0];
        else
            nuevo["Adjunto"] = null;
        
        var cantidadRepetido = actualizarMaterialPorObraRepetido(nuevo["IdObra"], nuevo["IdMaterial"], nuevo["Nombre"], nuevo["Cantidad"], nuevo["Precio"], nuevo["IdSolicita"]);
        
        if (cantidadRepetido != 0)
            nuevo["Cantidad"] = (parseFloat(nuevo["Cantidad"]) + parseFloat(cantidadRepetido)).toString();

        $('#matsEspecial').DataTable().row.add(nuevo).draw();
        //vista general
        var aux = {};
        aux["IdObra"] = dataO[0].id;
        aux["IdMaterial"] = dataM[0].id;
        aux["Nombre"] = dataM[0].text;
        aux["Cantidad"] = $("#cantidadEspecial").val().replace(/\,/g, '');
        aux["Precio"] = $("#precioEspecial").val().replace(/\,/g, '');
        agregarOCGeneral(aux);
        $(".proveedorEspecial").attr("disabled", true);
        $("#materialEspecial").empty();
        $("#precioEspecial").val("");
        $("#precioEspecial").prop('disabled', false);
        $("#cantidadEspecial").val("");
        $("#adjunto").val("");
        $("#solicitaEspecial").empty();
    }
}
//si ya existe un material de una obra con el mismo usuario, actualizar la cantidad y precio total
function actualizarMaterialPorObraRepetido(idObra, idMaterial, material, cantidad, precio, idSolicita) {
    var existe = false;
    var actualRow = null, cantidadActual = 0;
    
    $('#matsEspecial').DataTable().rows().every(function (rowIdx, tableLoop, rowLoop) {
        d = this.data();
        existe = false;
        //si es un material de catálogo de materiales
        if (idMaterial != -1 && d.IdObra == idObra && d.IdMaterial == idMaterial && d.IdSolicita == idSolicita)
            existe = true;
        //si es un material que no existe en catálogo
        else if (idMaterial == -1 && d.IdObra == idObra && d.Nombre == material && d.Precio == precio && d.IdSolicita == idSolicita)
            existe = true;
        
        if (existe) {
            cantidadActual = d.Cantidad;
            actualRow = $('#matsEspecial').DataTable().row(rowIdx);
        }
    });
    
    if (actualRow != null)
        actualRow.remove().draw();
    
    return cantidadActual;
}

function agregarOCGeneral(nuevo) {
    var existe = false;
    var actualRow = null, cantidadActual = 0;

    $('#ocCompleteTabla').DataTable().rows().every(function (rowIdx, tableLoop, rowLoop) {
        d = this.data();
        existe = false;
        //si es un material de catálogo de materiales
        if (nuevo["IdMaterial"] != -1 && d.IdMaterial == nuevo["IdMaterial"])
            existe = true;
        //si es un material que no existe en catálogo
        else if (nuevo["IdMaterial"] == -1 && d.Nombre == nuevo["Nombre"] && d.Precio == nuevo["Precio"])
            existe = true;
        
        if (existe) {
            nuevo["Cantidad"] = parseFloat(nuevo["Cantidad"]) + parseFloat(d.Cantidad);
            actualRow = $('#ocCompleteTabla').DataTable().row(rowIdx);
        }
    });
    
    if (actualRow != null)
        actualRow.remove().draw();

    $('#ocCompleteTabla').DataTable().row.add(nuevo).draw();
}

function mostrarOcultarNuevaOC_Especial(accion) {
    //ocultar
    if (accion == 0)
        $("#nuevaOC_Especial").slideUp("slow");
    //mostrar
    else {
        $("#BtnGuardar").prop("disabled", false);
        resetValuesEspecial();
        $("#tipoOC_Req").val(1); //1-> OC, 2->de requsicion
        $("#nuevaOC_Especial").slideDown("slow");
    }
}

function cancelar_Especial() {
    resetValuesEspecial();
    mostrarOcultarNuevaOC_Especial(0);
}

function resetValuesEspecial() {
    $(".enReq").prop("disabled", false);
    $('#matsEspecial').DataTable().columns([6]).visible(true);
    $('#matsEspecial').DataTable().columns.adjust().draw(true);
    $('#matsEspecial').DataTable().clear().draw();
    $('#ocCompleteTabla').DataTable().clear().draw();
    $("#presupuestoEspecial").html("");
    $("#precioEspecial").val("");
    $("#proveedorEspecial").empty();
    $("#materialEspecial").empty();
    $("#obraEspecial").empty();
    $("#cantidadEspecial").val("");
    $("#tipoPagoEspecial").val(0);
    $("#adjunto").val("");
    muestraExtraTipoPagoEspecial('0');
    $("#metodoPagoEspecial").val(-1);
    $("#anticipoEspecial").val("");
    $("#totalFacturaEspecial").val("");
    $("#folioFacturaEspecial").val("");
    $("#fechaFacturaEspecial").val("");
    $("#solicitaEspecial").empty();
    $("#numCotizacionEspecial").val("");
    $(".proveedorEspecial").attr("disabled", false);
    //$("#formOC_Especial").validate().resetForm();
    $("#formOC_Especial :input").removeClass('error');
    $("#pagoFormEspecial").validate().resetForm();
    $("#pagoFormEspecial :input").removeClass('error');
    $("#descripcionCompra").val("");
    $("#BtnGuardar").prop("disabled", false);
}

function guardarOrdenDeCompraEspecial(permisoAutorizar, tipo, descripcionCompra, notasProveedor, idMetodoPago, idUsuario) {
    //obteniendo datos para detalle de compra
    var datosDetalleCompra = [];
    var formData = new FormData();
    var cont = 1;

    $('#matsEspecial').DataTable().rows().every(function () {
        var d = this.data();
        var nuevo = {};
        nuevo["IdMaterial"] = d.IdMaterial;
        nuevo["Material"] = d.Nombre;
        nuevo["Cantidad"] = parseFloat(d.Cantidad.replace(/\,/g, ''));
        nuevo["PrecioUnitario"] = d.Precio;
        nuevo["Subtotal"] = (parseFloat(d.Cantidad.replace(/\,/g, '')) * parseFloat(d.Precio)).toFixed(2);
        nuevo["Recibido"] = 0;
        nuevo["Adjunto"] = d.Adjunto;
        nuevo["IdObra"] = d.IdObra;
        nuevo["IdSolicita"] = d.IdSolicita;
        datosDetalleCompra.push(nuevo);
        formData.append(cont+ ":_IdObra", d.IdObra);
        formData.append(cont+ ":_IdMaterial", d.IdMaterial);
        formData.append(cont+ ":_Material", d.Nombre);
        formData.append(cont+ ":_Cantidad", d.Cantidad.replace(/\,/g, ''));
        formData.append(cont+ ":_PrecioUnitario", d.Precio);
        formData.append(cont+ ":_Subtotal", (parseFloat(d.Cantidad.replace(/\,/g, '')) * parseFloat(d.Precio)).toFixed(2));
        formData.append(cont+ ":_Adjunto", d.Adjunto);
        formData.append(cont+ ":_IdSolicita", d.IdSolicita);
        cont++;
    });
    
    if (datosDetalleCompra.length > 0) {
        var datosOrdenCompra = {};
        var dataProveedor = $('#proveedorEspecial').select2('data');
        datosOrdenCompra["IdProveedor"] = dataProveedor[0].id;
        datosOrdenCompra["IdUsuario"] = idUsuario; //debe cambiar de acuerdo al usuario
        datosOrdenCompra["IdEstadoOC"] = 1; //debe cambiar de acuerdo al usuario
        datosOrdenCompra["Subtotal"] = subtotalEspecial.toFixed(2);
        datosOrdenCompra["IVA"] = ivaEspecial.toFixed(2);
        datosOrdenCompra["Total"] = totalEspecial.toFixed(2);
        datosOrdenCompra["PermisoAutorizar"] = permisoAutorizar;
        datosOrdenCompra["Descripcion"] = descripcionCompra;
        datosOrdenCompra["NotasProveedor"] = notasProveedor;
        datosOrdenCompra["IdMetodoPagoReporte"] = idMetodoPago;

        var tipoPago, anticipo, metodoPago;
        tipoPago = $("#tipoPagoEspecial").val();
        metodoPago = 0;
        datosOrdenCompra["TipoPago"] = tipoPago;
        
        switch (tipoPago) {
            case "0": //por pagar
            case "3": //sin autorizacion
                datosOrdenCompra["Pagada"] = 0;
                datosOrdenCompra["Anticipo"] = 0;
                datosOrdenCompra["IdMetodoPago"] = metodoPago;
            break;
            case "1": //pagado
                datosOrdenCompra["Pagada"] = 1;
                datosOrdenCompra["IdMetodoPago"] = metodoPago;
                datosOrdenCompra["Anticipo"] = 0;
            break;
            case "2": //anticipo
                datosOrdenCompra["Pagada"] = 0;
                datosOrdenCompra["IdMetodoPago"] = metodoPago;
                datosOrdenCompra["Anticipo"] = anticipo;
            break;
        }
        
        datosOrdenCompra["Referencia"] = $("#referenciaEspecial").val();
        datosOrdenCompra["totalFactura"] = $("#totalFacturaEspecial").val().replace(/\,/g, '');
        datosOrdenCompra["folioFactura"] = $("#folioFacturaEspecial").val();
        datosOrdenCompra["fechaFactura"] = $("#fechaFacturaEspecial").val();
        datosOrdenCompra["IdMetodoPago"] = idMetodoPago;
        datosOrdenCompra["numCotizacion"] = $("#numCotizacionEspecial").val();
        
        $.each(datosOrdenCompra, function(key, value) {
            formData.append(key, value);
        });

        var url = "";

        if (tipo === "1") {
            formData.append("accion", "guardarOCEspecial");
            url = './pages/compras/datos.php';
        }
        else if (tipo === "2") {
            formData.append("accion", "guardarCxPEspecial");
            url = './pages/cuentasPorPagar/datos.php';
        }
        
        $.ajax( {
            type: 'POST',
            url: url,
            data: formData,
            success: function(result) {
                result = result.slice(1, result.length - 1);
                var resultado = result.split(",");
                var error = resultado[0].split(":")[1];
                var msj = resultado[1].split(":")[1];
                msj = msj.slice(1, msj.length - 1);
                
                switch (error) {
                    case "0":
                        $("#successModal .modal-body").text(msj);
                        $("#successModal").modal("show");
                        //reinicio de valores
                        resetValuesEspecial();
                        mostrarOcultarNuevaOC_Especial(0);
                        $('#sinAutTable').DataTable().ajax.reload();
                        $('#emitidasTable').DataTable().ajax.reload();
                    break;
                    case "1":
                        msjError = msj;
                        $("#errorModal .modal-title").text("ERROR");
                        $("#errorModal .modal-body").text("ERROR DE BASE DE DATOS. "+ msjError);
                        $("#errorModal").modal("show");
                    break;
                    case "2":
                        msjError = msj;
                        $("#avisosModal .modal-title").text("ERROR DE VALIDACION. "+ msjError);
                        $("#avisosModal").modal("show");
                    break;
                }
            },
            error: function(response) {
                $("#errorModal .modal-body").text("ERROR AL GUARDAR");
                $("#errorModal").modal("show");
            },
            processData: false,
            contentType: false
        });
    }
    else {
        $("#avisosModal .modal-title").text("ORDEN DE COMPRA");
        $("#avisosModal .modal-body").text("ORDEN DE COMPRA VACÍA");
        $("#avisosModal").modal("show");
    }
}

function getMaterialByIdEspecial(idMaterial, idProveedor) {
    var datos = { "accion":'getInfoMaterialById', "idMaterial":idMaterial, "idProveedor":idProveedor };
    
    $.post("./pages/compras/datos.php", datos, function(result) {
        var precio = result["result"][0]["Precio"];
        $("#precioEspecial").val(precio);
    }, "json");
}

function autoCompleteProveedoresEspecial() {
    $('.proveedorEspecial').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
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

function autoCompleteMaterialesEspecial(idProveedor) {
    $('.materialEspecial').select2( {
        placeholder: "Selecciona una opción",
        tags: true,
        createTag: function (params) {
            $("#materialEspecial").text("");
            $("#precioEspecial").val("");
            $("#precioEspecial").prop('disabled', false);
            return {
                id: "-1",
                text: params.term
            }
        },
        ajax: {
            url: './pages/compras/autocompleteOC.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                $("#precioEspecial").prop('disabled', true);
                return {
                    nombreAutocomplete: 'material',
                    IdProveedor: idProveedor,
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

function autoCompleteObrasEspecial() {
    $('.obraEspecial').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/compras/autocompleteOC.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'obra',
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

function llenaMetodoPagoEspecial() {
    var datos = { "accion":'getMetodosPago' };
    $("select[name='metodoPagoEspecial']").append($("<option value='' selected='selected' disabled>Selecciona método de pago</option>"));
    
    $.post("./pages/compras/datos.php", datos, function(result) {
        $.each(result, function(i, val) {
            $("select[name='metodoPagoEspecial']").append($("<option>", {
                value: val.IdMetodoPago,
                text: val.Nombre
            }));
        });
    }, "json");
}

function muestraExtraTipoPagoEspecial(tipoPago) {
    switch (tipoPago) {
        case '0':
            $(".divMetodoPagoEspecial").css("display", "none");
            $("#metodoPagoEspecial").val("");
            $("#divAnticipoEspecial").css("display", "none");
        break;
        case '1':
            $("#metodoPagoEspecial").val("");
            $(".divMetodoPagoEspecial").css("display", "block");
            $("#divAnticipoEspecial").css("display", "none");
        break;
        case '2':
            $("#metodoPagoEspecial").val("");
            $(".divMetodoPagoEspecial").css("display", "block");
            $("#divAnticipoEspecial").css("display", "block");
        break;
    }
}

function eliminarOCEspecial(actualRowEspecial) {
    eliminarDeOCGeneral(actualRowEspecial.data());
    actualRowEspecial.remove().draw();
    
    var cont = $('#matsEspecial').DataTable().data().count();
    
    if (cont === 0)
        $(".proveedorEspecial").attr("disabled", false);
}

function eliminarDeOCGeneral(data) {
    var existe = false;
    var rowGeneral = null, cantidadActual = 0;
    var cantidad = 0;
    var nuevaRow = {};
    
    $('#ocCompleteTabla').DataTable().rows().every(function (rowIdx, tableLoop, rowLoop) {
        d = this.data();
        existe = false;
        //si es un material de catálogo de materiales
        if (data.IdMaterial !== -1 && d.IdMaterial === data.IdMaterial)
            existe = true;
        //si es un material que no existe en catálogo
        else if (data.IdMaterial === -1 && d.Nombre === data.Nombre && d.Precio === data.Precio)
            existe = true;
        
        if (existe) {
            rowGeneral = $('#ocCompleteTabla').DataTable().row(rowIdx);
            cantidad = rowGeneral.data().Cantidad - data.Cantidad;
        }
    });
    
    if (rowGeneral !== null) {
        rowGeneral.remove().draw();
        nuevaRow["Cantidad"] = cantidad;
    }
    else
        nuevaRow["Cantidad"] = data.Cantidad;

    nuevaRow["IdMaterial"] = data.IdMaterial;
    nuevaRow["Nombre"] = data.Nombre;
    nuevaRow["Precio"] = data.Precio;
    
    $('#ocCompleteTabla').DataTable().row.add(nuevaRow).draw();
}

function esCantidadMenorOIgualADeuda(cantidad, deuda) {
    if (parseFloat(cantidad) > parseFloat(deuda))
        return false;
    return true;
}

function dameReferencia(idMetodoPago) {
    var datos = { "accion":'getReferenciaMetodoPago', "idMetodoPago":idMetodoPago };
    
    $.post("./pages/compras/datos.php", datos, function(result) {
        $("#referenciaEspecial").val(result["Referencia"]);
    }, "json");
}

function cancelarOC(idRegistro) {
    var datos = {};
    datos["accion"] = 'cancelar';
    datos["id"] = idRegistro;
    
    $.post("./pages/compras/sinAutorizacion/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA CANCELADO LA ORDEN DE COMPRA.");
                $("#successModal").modal("show");
                $('#sinAutTable').DataTable().ajax.reload();
                $('#emitidasTable').DataTable().ajax.reload();
                $('#canceladasTable').DataTable().ajax.reload();
            break;
            case 1:
                var msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR AL CANCELAR LA ORDEN DE COMPRA. ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}

function autoCompleteUsuariosEspecial() {
    $('.solicitaEspecial').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/compras/autocompleteOC.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'usuario',
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
//Requisiciones
function mostrarOcultarNuevaOCReq(accion) {
    //ocultar
    if (accion == 0)
        $("#nuevaOCReq").slideUp("slow");
    //mostrar
    else {
        resetValuesOCReq();
        $("#nuevaOCReq").slideDown("slow");
    }
}

function autoCompleteProveedoresCambio() {
    $('.provnvo').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
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