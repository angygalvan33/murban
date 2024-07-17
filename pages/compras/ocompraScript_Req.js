function agregaDetalleOC_Req(idMaterial, material, idObra, obra, cantidad, precio, idSolicita, solicita, idReqDetalle) {
    var nuevo = {};
    nuevo["IdObra"] = idObra;
    nuevo["Obra"] = obra;
    nuevo["Cantidad"] = cantidad;
    nuevo["IdMaterial"] = idMaterial;
    nuevo["Nombre"] = material;
    nuevo["Precio"] = precio;
    nuevo["Solicita"] = solicita;
    nuevo["IdSolicita"] = idSolicita;
    nuevo["Adjunto"] = null;
    nuevo["IdReqDetalle"] = idReqDetalle;

    var cantidadRepetido = actualizarMaterialPorObraRepetido(nuevo["IdObra"], nuevo["IdMaterial"], nuevo["Nombre"], nuevo["Cantidad"], nuevo["Precio"], nuevo["IdSolicita"]);
    
    if (cantidadRepetido !== 0)
        nuevo["Cantidad"] = (parseFloat(nuevo["Cantidad"]) + parseFloat(cantidadRepetido)).toString();

    $('#matsEspecial').DataTable().row.add(nuevo).draw();
    //vista general
    var aux = {};
    aux["IdObra"] = idObra;
    aux["IdMaterial"] = idMaterial;
    aux["Nombre"] = material;
    aux["Cantidad"] = cantidad;
    aux["Precio"] = precio;
    agregarOCGeneral(aux);
}

function eliminarOCEspecial_Req(actualRowEspecial) {
    eliminarDeOCGeneral(actualRowEspecial.data());
    actualRowEspecial.remove().draw();
}

function guardarOrdenDeCompra_Requisicion(permisoAutorizar, tipo, descripcionCompra, notasProveedor, idMetodoPago, idUsuario) {
    var datosDetalleCompra = [];
    var formData = new FormData();
    var cont = 1;

    $('#matsEspecial').DataTable().rows().every(function () {
        var d = this.data();
        var nuevo = {};
        nuevo["IdMaterial"] = d.IdMaterial;
        nuevo["Material"] = d.Nombre;
        nuevo["Cantidad"] = parseFloat(d.Cantidad);
        nuevo["PrecioUnitario"] = d.Precio;
        nuevo["Subtotal"] = (parseFloat(d.Cantidad) * parseFloat(d.Precio)).toFixed(2);
        nuevo["Recibido"] = 0;
        nuevo["Adjunto"] = d.Adjunto;
        nuevo["IdObra"] = d.IdObra;
        nuevo["IdSolicita"] = d.IdSolicita;
        nuevo["IdReqDetalle"] = d.IdReqDetalle;
        datosDetalleCompra.push(nuevo);
        formData.append(cont +":_IdObra", d.IdObra);
        formData.append(cont +":_IdMaterial", d.IdMaterial);
        formData.append(cont +":_Material", d.Nombre);
        formData.append(cont +":_Cantidad", d.Cantidad);
        formData.append(cont +":_PrecioUnitario", d.Precio);
        formData.append(cont +":_Subtotal", (parseFloat(d.Cantidad) * parseFloat(d.Precio)).toFixed(2));
        formData.append(cont +":_Adjunto", d.Adjunto);
        formData.append(cont +":_IdSolicita", d.IdSolicita);
        formData.append(cont +":_IdReqDetalle", d.IdReqDetalle);
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
        datosOrdenCompra["idsReqs"] = idsDetalleReq;
        var tipoPago, anticipo, metodoPago;
        tipoPago = $("#tipoPagoEspecial").val();
        metodoPago = 0;
        datosOrdenCompra["TipoPago"] = tipoPago;
        
        switch (tipoPago) {
            case "0": // por pagar
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
        formData.append("accion", "guardarOCRequisicion"); //de req
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