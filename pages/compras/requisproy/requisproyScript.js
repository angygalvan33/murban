function loadDataTableRequisProy(checkboxHabilitado, tipoQuery, idProyecto, permisoAsignar) {
    if ($.fn.dataTable.isDataTable('#requisProyTable')) {
        tablaRequisProy.destroy();
    }
    
    tablaRequisProy = $('#requisProyTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'order': [[2, 'desc']],
        'ajax': {
            url: "pages/compras/requisproy/requisproyData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".requisProyTable-error").html("");
                $("#requisProyTable").append('<tbody class="requisProyTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#requisProyTable_processing").css("display", "none");
            },
            data: {
                "IdProyecto": idProyecto,
                "tipo": tipoQuery,
                "piezas": checkboxHabilitado
            }
        },
        'columns': [
            { 'data': "Cantidad", orderable: true, width: "5%", className: 'details-control' },
            { width: "10%", className: 'text-center', orderable: true,
                mRender: function (data, type, row) {
                    if (checkboxHabilitado == 0) {
                        return " Piezas ";
                    }
                    else
                        return " Kilos ";
                }
            },
            { 'data': "Folio", orderable: true, width: "5%" },
            { 'data': "Material", orderable: true, width: "15%" },
            { 'data': "CantidadAtendida", orderable: true, width: "5%" },
            { width: "10%", className: 'text-center', orderable: false,
                mRender: function (data, type, row) {
                    if (row.IdProyecto !== "-1" && parseFloat(row.ExistenciaStock) > 0 && permisoAsignar) {
                        return row.ExistenciaStock +" <button type='button' id='req_asignar' style='margin-right:5px' class='btn btn-warning btn-sm'>Asignar</button>";
                    }
                    else
                        return "";
                }
            },
            { 'data': "Observacion", orderable: true, width: "20%" },
            { 'data': "Proyecto", orderable: true, width: "10%" },
            { 'data': "FechaReq", orderable: true, width: "10%" },
            { width: "10%", className: 'text-center', orderable: false,
                mRender: function (data, type, row) {
                    return "<button type='button' id='req_cancelar' class='btn btn-danger btn-sm reqCancelar"+ row.IdRequisicionDetalle +"'>Cancelar</button>";
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function autoCompleteProyectosRequisiciones(band) {
    $('#proyectosRequi').select2( {
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
                    band: band,
                    nombreAutocomplete: 'proyectosReq',
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

function solicitarNuevaOC_Req(tipo) {
    $("#tipoOCReq").val(tipo); //1-> requisicion, 2->requisicion especial
    $("#nuevaOCReq").slideDown("slow");
    $(".enReq").prop("disabled", true);
    $('#matsOCReq').DataTable().ajax.reload();
	$('#tablematsbykg').hide();
	$('#tablematsbypz').show();
	$('#btnsaveocreqbykg').hide();
	$('#btnsaveocreq').show();
    //cerrar panel Requisiciones
    $("#requisicionesPanel").collapse('hide');
    $("#requisicionesEspecialesPanel").collapse('hide');
    $("#BtnGuardarOCReqxkilo").prop("disabled", false);
    $("#BtnGuardar").prop("disabled", false);
    $("#BtnGuardarOCReq").prop("disabled", false);
}

function cancelarOCReq() {
    resetValuesOCReq();
    mostrarOcultarNuevaOCReq(0);
}

function resetValuesOCReq() {
    $(".enReq").prop("disabled", false);
    $('#ocCompleteTablaOCReq').DataTable().clear().draw();
    $("#precioOCReq").val("");
    $("#proveedorOCReq").empty();
    $("#materialOCReq").empty();
    $("#obraOCReq").empty();
    $("#cantidadOCReq").val("");
    $("#tipoPagoOCReq").val(0);
    $("#adjuntoOCReq").val("");
    $("#solicitaOCReq").empty();
    $("#numCotizacionOCReq").val("");
    $("#formOCReq").validate().resetForm();
    $("#formOCReq :input").removeClass('error');
    $("#descripcionOCReq").val("");
    $("#BtnGuardarOCReqxkilo").prop("disabled", false);
    $("#BtnGuardar").prop("disabled", false);
    $("#BtnGuardarOCReq").prop("disabled", false);
}

function resetRequisiciones(tipo) {
    var datos = {};
    datos["accion"] = 'resetRequisiciones';
    datos["tipoRequisicion"] = tipo === 0 ? "Manual" : "Especial";
    
    $.post("./pages/compras/requisproy/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $('#requisProyTable').DataTable().ajax.reload();
            break;
        }
    }, "json");
}

//tipo = 0 stock, tipo = 1 OC
//tipoReq 0-> manual, 1 especial
function asignarMaterial(tipoReq, tipo, idReqDetalle, idMaterial, idProyecto, cantidad, idProveedor, fechaProv) {
    var accion = tipo === "0" ? "asignarStock" : "comprarOC";
    var continuar = true;

    if (tipo === "0") {
        if (parseFloat($("#existenciaStockAsignar").val()) < cantidad) {
            $("#errorcantidadAsignar").css("display","block");
            continuar = false;
        }
    }
    
    if (continuar === true) {
        $("#asignarComprarModal").modal("hide");
        var datos = {};
        datos["accion"] = accion;
        datos["idRequisicionDetalle"] = idReqDetalle;
        datos["idMaterial"] = idMaterial;
        datos["idProyecto"] = idProyecto;
        datos["idProveedor"] = idProveedor;
        datos["cantidad"] = cantidad;
        datos["fechaProv"] = fechaProv;

        if (tipo !== "0")
            datos["tipoRequisicion"] = tipoReq === "0" ? "2" : "4";
        else
            datos["tipoRequisicion"] = 2;

        $.post("./pages/compras/requisproy/detalleProveedores/datos.php", datos, function(result) {
            var msjError = result["result"];

            switch (result["error"]) {
                case 0:
                    $("#successModal .modal-body").text(msjError);
                    $("#successModal").modal("show");
                    //if (tipo === "0" || tipo === "1") {
                        $('#proveedoresRequisicionesTable').DataTable().ajax.reload();
                        $('#requisProyTable').DataTable().ajax.reload();
                        $('#requispreoc_Table').DataTable().ajax.reload();
                        //$('#materialesPreOCTable').DataTable().ajax.reload();
                    //}
                    //else{
                        $('#requisicionesEspecialesTable').DataTable().ajax.reload();
                        //$('#requispreoc_Table').DataTable().ajax.reload();
                    //}
                break;
                case 1:
                    $("#errorModal .modal-body").text(msjError);
                    $("#errorModal").modal("show");
                break;
            }
        }, "json");
    }
}

function cancelarReq(idDetalle, motivo) {
    var datos = {};
    datos["accion"] = 'cancelarRequisicion';
    datos["idRequisicionDetalle"] = idDetalle;
    datos["motivo"] = motivo;

    $.post("./pages/compras/requisproy/detalleProveedores/datos.php", datos, function(result) {
        var msjError = result["result"];

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(msjError);
                $("#successModal").modal("show");
                $('#requisicionesTable').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}