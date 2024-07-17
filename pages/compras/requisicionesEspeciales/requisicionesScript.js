function loadDataTableRequisicionesEspecial(checkboxHabilitado, tipo, idProveedor) {
    if ($.fn.dataTable.isDataTable('#requisicionesEspecialesTable')) {
        tablaRequisicionesEspecial.destroy();
    }
    
    tablaRequisicionesEspecial = $('#requisicionesEspecialesTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/requisicionesEspeciales/requisicionesData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".requisicionesEspecialesTable-error").html("");
                $("#requisicionesEspecialesTable").append('<tbody class="requisicionesEspecialesTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#requisicionesEspecialesTable_processing").css("display", "none");
            },
            data: {
                "Tipo": tipo,
                "IdProveedor": idProveedor
            }
        },
        'columns': [
            { 'data': "Cantidad", orderable: true, width: "10%" },
            { 'data': "CantidadPreOC", orderable: true, width: "10%" },
            { 'data': "Material", orderable: true, width: "20%" },
            { width: "10%",
                mRender: function (data, type, row) {
                    return "<p>$"+row.Precio+"</p>";
                }
            },
            { 'data': "Proyecto", orderable: true, width: "20%" },
            { 'data': "CantidadAtendida", orderable: true, width: "15%", className: 'text-center' },
            { 'data': "UnicaOcasion", orderable: true, width: "20%",
                mRender: function (data, type, row) {
                    if (row.UnicaOcasion === "1")
                        return "<p>Sí</p>";
                    else
                        return "<p>No</p>";
                }
            },
            { 'data': "Proveedor", orderable: true, width: "25%" },
            { 'data': "FechaReq", orderable: true, width: "10%", className: 'text-center' },
            { width: "15%", orderable: false,
                mRender: function (data, type, row) {
                    var buttons = "";
                    buttons += "<button type='button' id='asignaProveedor' style='margin-right:5px' class='btn btn-warning btn-sm'>Asignar proveedor</button>";
                    return buttons;
                }
            },
            { width: "10%", className: 'text-center', orderable: false,
                mRender: function (data, type, row) {
                    if (checkboxHabilitado === 0) {
                        if (row.Proveedor === null || row.Proveedor === "")
                            return "<button type='button' id='req_comprar' style='margin-right:5px' class='btn btn-success btn-sm' disabled>Comprar</button>";
                        else
                            return "<button type='button' id='req_comprar' style='margin-right:5px' class='btn btn-success btn-sm'>Comprar</button>";
                    }
                    else
                        return "<button type='button' id='req_comprar' style='margin-right:5px' class='btn btn-success btn-sm' disabled>Comprar</button>";
                }
            },
            { width: "10%", className: 'text-center', orderable: false,
                mRender: function (data, type, row) {
                    return "<button type='button' id='req_cancelar' class='btn btn-danger btn-sm reqCancelar"+ row.IdRequisicionDetalle +"'>Cancelar</button>";
                }
            }
        ],
        "order": [[ 1, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function autoCompleteProveedoresRequisicionesEspecial() {
    $('#provSeleccionadosEspecial').select2( {
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
                    nombreAutocomplete: 'proveedorReqEspecial',
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

function solicitarNuevaOC_ReqEspecial() {
    $("#tipoOC_Req").val(2); //1-> OC, 2->de requsicion
    $("#BtnGuardar").prop("disabled", false);
    $("#nuevaOC_Especial").slideDown("slow");
    $(".enReq").prop("disabled", true);
    $('#matsEspecial').DataTable().columns([6]).visible(false);
    $('#matsEspecial').DataTable().columns.adjust().draw(false);
    //cerrar panel Requisiciones
    $("#requisicionesEspecialesPanel").collapse('hide');
}

function cancelarReq(idDetalle, motivo) {
    var datos = {};
    datos["accion"] = 'cancelarRequisicion';
    datos["idRequisicionDetalle"] = idDetalle;
    datos["motivo"] = motivo;

    $.post("./pages/compras/requisiciones/detalleMateriales/datos.php", datos, function(result) {
        var msjError = result["result"];
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(msjError);
                $("#successModal").modal("show");
                $('#requisicionesEspecialesTable').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}