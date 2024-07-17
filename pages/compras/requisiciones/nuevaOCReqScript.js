var subtotalOCReq = 0;
var ivaOCReq = 0;
var totalOCReq = 0;

function autoCompleteProveedoresOCReq() {
    $('#proveedorOCReq').select2( {
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
                    nombreAutocomplete: 'proveedorReq',
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

function inicializaVistaPreviaOCReq() {
    $('#ocCompleteTablaOCReq').DataTable( {
        'lengthMenu': [ [10,15, 25, -1], [10,15, 25, "Todos"] ],
        'data': [],
        'columns': [
            { 'data': "Cantidad", sortable: false, width: "10%", orderable: false },
            { 'data': "Nombre", sortable: false, width: "30%", orderable: true },
            { 'data': "Precio", sortable: false, width: "15%", orderable: true, className: 'alinearDerecha' },
            {
                mRender: function (data, type, row) {
                    var costo = parseFloat(row.Cantidad) * parseFloat(row.Precio);
                    return formatNumber(costo.toFixed(2));
                }, width: "15%", sortable: false,
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function inicializaMaterialesByProveedorOCReq() {
    $('#matsOCReq').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/requisiciones/detalleCompraData.php", //json datasource
            type: "post", //method, by default get
            data: function(data) {
                data.IdProveedor = $("#valIdProvOCReq").val();
                data.TipoDetalle = $("#tipoOCReq").val();
            },
            error: function() { //error handling
                $(".matsOCReq-error").html("");
                $("#matsOCReq").append('<tbody class="matsOCReq-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#matsOCReq_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "CantidadPedida", orderable: false, width: "10%" },
            { 'data': "Material", orderable: true, width: "20%" },
            { 'data': "PrecioUnitario", orderable: false, width: "10%" },
            { 'data': "CostoTotal", orderable: false, width: "10%" },
            { 'data': "Proyecto", orderable: true, width: "15%" },
            { 'data': "Usuario", orderable: true, width: "10%" },
            { 'data': "FechaProv", orderable: true, width: "15%" },
            { width: "10%", orderable: false,
                mRender: function (data, type, row) {
					if (row.IdProyecto > -1)
                        return "<button type='button' id='eliminar_OCReq' style='margin-right:5px' class='btn btn-danger btn-sm'>Eliminar</button>";
                    else
						return "";
				}
            }
        ],
        "order": [[ 4, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        },
        'footerCallback': function (row, data, start, end, display) {
            var api = this.api(), data;
            subtotalOCReq = 0;

            $.each(data, function(key, value) {
                subtotalOCReq += parseFloat(value.CostoTotal);
                var nuevoOCReq = {};
                nuevoOCReq["Cantidad"] = value.CantidadPedida;
                nuevoOCReq["IdMaterial"] = value.IdMaterial;
                nuevoOCReq["Nombre"] = value.Material;
                nuevoOCReq["Precio"] = value.PrecioUnitario;
                agregarOCReq(nuevoOCReq);
            });
            // Update footer
            $(api.column(3).footer()).html (
                subtotalOCReq.toFixed(2)
            );
            ivaOCReq = subtotalOCReq * 0.16;
            totalOCReq = subtotalOCReq + ivaOCReq;
            $("#subtotalOCReq").text("$"+ formatNumber(subtotalOCReq.toFixed(2)));
            $("#ivaOCReq").text("$"+ formatNumber(ivaOCReq.toFixed(2)));
            $("#totalOCReq").text("$"+ formatNumber(totalOCReq.toFixed(2)));
        }
    });
}

function eliminarOCReq(idReqAtendida) {
    var datos = {};
    datos["accion"] = 'eliminarOCReq';
    datos["id"] = idReqAtendida;

    $.post("./pages/compras/requisiciones/detalleMateriales/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#matsOCReq').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}

function agregarOCReq(nuevo) {
    var existe = false;
    var actualRow = null;
    
    $('#ocCompleteTablaOCReq').DataTable().rows().every(function (rowIdx, tableLoop, rowLoop) {
        d = this.data();
        existe = false;
        //si es un material de catálogo de materiales
        if (nuevo["IdMaterial"] != -1 && d.IdMaterial == nuevo["IdMaterial"])
            existe = true;
        //si es un material que no existe en catálogo
        else if (nuevo["IdMaterial"] == -1 && d.Nombre == nuevo["Material"] && d.Precio == nuevo["PrecioUnitario"])
            existe = true;
        
        if (existe) {
            nuevo["Cantidad"] = parseFloat(nuevo["Cantidad"]) + parseFloat(d.Cantidad);
            actualRow = $('#ocCompleteTablaOCReq').DataTable().row(rowIdx);
        }
    });
    
    if (actualRow != null)
        actualRow.remove().draw();

    $('#ocCompleteTablaOCReq').DataTable().row.add(nuevo).draw();
}
//tipo = 1 Requisicion, tipo = 2 Requisicion Especial
function guardarOrdenDeCompraOCReq(tipo, permisoAutorizar, descripcionCompra, notasProveedor, idMetodoPago, idUsuario) {
    var idReqsDetalle = [];
    var idReqsAtendida = [];
    //obteniendo datos para detalle de compra
    var datosDetalleCompra = [];
    var formData = new FormData();
    var cont = 1;
    
    $('#matsOCReq').DataTable().rows().every(function () {
        var d = this.data();
        var nuevo = {};
        nuevo["IdMaterial"] = d.IdMaterial;
        nuevo["Material"] = d.Material;
        nuevo["Cantidad"] = parseFloat(d.CantidadPedida);
        nuevo["PrecioUnitario"] = d.PrecioUnitario;
        nuevo["Subtotal"] = (parseFloat(d.CantidadPedida) * parseFloat(d.PrecioUnitario)).toFixed(2);
        nuevo["Recibido"] = 0;
        nuevo["Adjunto"] = null;
        nuevo["IdObra"] = d.IdProyecto;
        nuevo["IdSolicita"] = d.IdUsuario;
        nuevo["IdReqAtendida"] = d.IdRequisicionAtendida;
        nuevo["IdReqDetalle"] = d.IdRequisicionDetalle;
        datosDetalleCompra.push(nuevo);
        formData.append(cont +":_IdObra", d.IdProyecto);
        formData.append(cont +":_IdMaterial", d.IdMaterial);
        formData.append(cont +":_Material", d.Material);
        formData.append(cont +":_Cantidad", d.CantidadPedida);
        formData.append(cont +":_PrecioUnitario", d.PrecioUnitario);
        formData.append(cont +":_Subtotal", (parseFloat(d.CantidadPedida) * parseFloat(d.PrecioUnitario)).toFixed(2));
        formData.append(cont +":_Adjunto", null);
        formData.append(cont +":_IdSolicita", d.IdUsuario);
        formData.append(cont +":_IdReqDetalle", d.IdRequisicionDetalle);
        formData.append(cont +":_IdReqAtendida", d.IdRequisicionAtendida);
        cont++;
        idReqsDetalle.push(d.IdRequisicionDetalle);
        idReqsAtendida.push(d.IdRequisicionAtendida);
    });
    
    if (datosDetalleCompra.length > 0) {
        //obteniendo datos para orden de compra
        var datosOrdenCompra = {};
        var dataProveedor = $('#proveedorOCReq').select2('data');
        datosOrdenCompra["IdProveedor"] = dataProveedor[0].id;
        datosOrdenCompra["IdUsuario"] = idUsuario; //debe cambiar de acuerdo al usuario
        datosOrdenCompra["IdEstadoOC"] = 1; //debe cambiar de acuerdo al usuario
        datosOrdenCompra["Subtotal"] = subtotalOCReq.toFixed(2);
        datosOrdenCompra["IVA"] = ivaOCReq.toFixed(2);
        datosOrdenCompra["Total"] = totalOCReq.toFixed(2);
        datosOrdenCompra["PermisoAutorizar"] = permisoAutorizar;
        datosOrdenCompra["Descripcion"] = descripcionCompra;
        datosOrdenCompra["NotasProveedor"] = notasProveedor;
        datosOrdenCompra["IdMetodoPagoReporte"] = idMetodoPago;
        datosOrdenCompra["idsReqs"] = idReqsDetalle;
        datosOrdenCompra["idsReqsAtendidas"] = idReqsAtendida;
        var tipoPago, anticipo, metodoPago;
        tipoPago = $("#tipoPagoOCReq").val();
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
        
        datosOrdenCompra["Referencia"] = "";
        datosOrdenCompra["totalFactura"] = "";
        datosOrdenCompra["folioFactura"] = "";
        datosOrdenCompra["fechaFactura"] = "";
        datosOrdenCompra["IdMetodoPago"] = idMetodoPago;
        datosOrdenCompra["numCotizacion"] = "";
        
        $.each(datosOrdenCompra, function(key, value) {
            formData.append(key, value);
        });

        var url = "";
        formData.append("accion", "guardarOCRequisicionNueva"); //de req
        url = './pages/compras/datos.php';

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
                        resetValuesOCReq();
                        mostrarOcultarNuevaOCReq(0);
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