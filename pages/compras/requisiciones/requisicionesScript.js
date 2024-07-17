function loadDataTableRequisiciones(tipo, idProveedor) {
    if ($.fn.dataTable.isDataTable('#requisicionesTable')) {
        tablaRequisiciones.destroy();
    }
    
    tablaRequisiciones = $('#requisicionesTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/requisiciones/requisicionesData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".requisicionesTable-error").html("");
                $("#requisicionesTable").append('<tbody class="requisicionesTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#requisicionesTable_processing").css("display", "none");
            },
            data: {
                "Tipo": tipo,
                "IdProveedor": idProveedor
            }
        },
        'columns': [
            { 'data': "CantidadParaSolicitar", orderable: true, width: "10%", className: 'details-control' },
            { 'data': "Material", orderable: true, width: "20%" },
            { 'data': "Observacion", orderable: true, width: "25%" },
            { width: "20%",
                mRender: function (data, type, row) {
                    var obj = JSON.parse(row.Medida);
                    return "<p>"+ obj[0].valor + obj[0].unidad +"</p>";
                }
            },
            { width: "10%",
                mRender: function (data, type, row) {
                    if (tipo === 1)
                        return "<p>$"+ row.Precio +"</p>";
                    else
                        return "<p></p>";
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
    
    if (tipo === 0)
        tablaRequisiciones.column(4).visible(false);
    else
        tablaRequisiciones.column(4).visible(true);
}

function autoCompleteProveedoresRequisiciones() {
    $('#provSeleccionados').select2( {
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

/*function solicitarNuevaOC_Req(tipo) {
    $("#tipoOCReq").val(tipo); //1->requisicion, 2->requisicion especial
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
}*/

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

    $.post("./pages/compras/requisiciones/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $('#requisicionesTable').DataTable().ajax.reload();
				$('#requisicionesTablexkilo').DataTable().ajax.reload();
            break;
        }
    }, "json");
}
//tipo 0 stock, 1 OC
//tipoReq 0 manual, 1 especial
function asignarMaterial(tipoReq, tipo, idReqDetalle, idMaterial, idProyecto, cantidad, idProveedor, fechaProv) {
    if ($("#formAsignarMaterial").valid()) {
        var accion = tipo === "0" ? "asignarStock" : "comprarOC";
        var continuar = true;

        if (tipo === "0") {
            if (parseFloat($("#existenciaStockAsignar").val()) <= cantidad) {
                $("#errorcantidadAsignar").css("display", "block");
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
            
            $.post("./pages/compras/requisiciones/detalleMateriales/datos.php", datos, function(result) {
                var msjError = result["result"];

                switch(result["error"]) {
                    case 0:
                        $("#successModal .modal-body").text(msjError);
                        $("#successModal").modal("show");
                        
                        if (tipo === "0" || tipo === "1")
                            $('#materialesRequisicionesTable').DataTable().ajax.reload();
                        else
                            $('#requisicionesEspecialesTable').DataTable().ajax.reload();
                        break;
                    case 1:
                        $("#errorModal .modal-body").text(msjError);
                        $("#errorModal").modal("show");
                    break;
                }
            }, "json");
        }
    }
}