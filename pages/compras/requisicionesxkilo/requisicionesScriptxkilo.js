function loadDataTableRequisicionesxkilo(tipo, idProveedor) {
    if ($.fn.dataTable.isDataTable('#requisicionesTablexkilo')) {
        tablaRequisicionesxkilo.destroy();
    }
    
    tablaRequisicionesxkilo = $('#requisicionesTablexkilo').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/requisicionesxkilo/requisicionesDataxkilo.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".requisicionesTablexkilo-error").html("");
                $("#requisicionesTablexkilo").append('<tbody class="requisicionesTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#requisicionesTablexkilo_processing").css("display", "none");
            },
            data: {
                "Tipo": tipo,
                "IdProveedor": idProveedor
            }
        },
        'columns': [
            { 'data': "TotalPeso", orderable: true, width: "10%", className: 'details-control' },
            { 'data': "Material", orderable: true, width: "20%" },
            { 'data': "Descripcion", orderable: true, width: "25%" },
            { width: "20%",
                mRender: function (data, type, row) {
				    return "Kilos";
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
        tablaRequisicionesxkilo.column(4).visible(false);
    else
        tablaRequisicionesxkilo.column(4).visible(true);
}

function autoCompleteProveedoresRequisicionesxkilo() {
    $('#provSeleccionadosxkilo').select2( {
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
                    nombreAutocomplete: 'proveedorReqxkilo',
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

function solicitarNuevaOC_Reqxkilo(tipo) {
    $("#tipoOCReq").val(tipo); //1-> requisicion, 2->requisicion especial
    $("#nuevaOCReq").slideDown("slow");
    $(".enReq").prop("disabled", true);
	$('#matsOCReqxkilo').DataTable().ajax.reload();
	$('#tablematsbykg').show();
	$('#tablematsbypz').hide();
	$('#btnsaveocreqbykg').show();
	$('#btnsaveocreq').hide();
    $("#requisicionesPanelxkilo").collapse('hide');
    $("#requisicionesEspecialesPanel").collapse('hide');
}

function resetRequisicionesxkilo(tipo) {
    var datos = {};
    datos["accion"] = 'resetRequisiciones';
    datos["tipoRequisicion"] = tipo === 0 ? "Manual" : "Especial";

    $.post("./pages/compras/requisiciones/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $('#requisicionesTablexkilo').DataTable().ajax.reload();
            break;
        }
    }, "json");
}
//tipo = 0 stock, tipo = 1 OC
//tipoReq 0-> manual, 1 especial
function asignarMaterialxkilo(tipoReq, tipo, idReqDetalle, idMaterial, idProyecto, cantidad, idProveedor, fechaProv) {
    var accion = tipo === "0" ? "asignarStock" : "comprarOC";
    var continuar = true;

    if (tipo === "0") {
        if (parseFloat($("#existenciaStockAsignar").val()) < cantidad) {
            $("#errorcantidadAsignar").css("display", "block");
            continuar = false;
        }
    }

    if (continuar === true) {
        $("#asignarComprarModalxkilo").modal("hide");
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

        $.post("./pages/compras/requisicionesxkilo/detalleMaterialesxkilo/datosxkilo.php", datos, function(result) {
            var msjError = result["result"];

            switch(result["error"]) {
                case 0:
                    $("#successModal .modal-body").text(msjError);
                    $("#successModal").modal("show");
                    
                    if (tipo === "0" || tipo === "1")
                        $('#materialesRequisicionesTablexkilo').DataTable().ajax.reload();
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