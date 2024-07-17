function loadDataTableMaterialesRequisiciones(checkboxHabilitado, tipo, idProveedor) {
    if ($.fn.dataTable.isDataTable('#materialesRequisicionesTable')) {
        tablaDetalleRequisiciones.destroy();
    }
    
    tablaDetalleRequisiciones = $('#materialesRequisicionesTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/requisiciones/detalleMateriales/listadoMaterialesData.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdMaterial": $(".detallesMatRequisiciones").attr("id"),
                "Tipo": tipo,
                "IdProveedor": idProveedor
            },
            error: function() { //error handling
                $(".materialesRequisicionesTable-error").html("");
                $("#materialesRequisicionesTable").append('<tbody class="materialesRequisicionesTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#materialesRequisicionesTable_processing").css("display", "none");
            }
        },
        'columns': [
            { orderable: false, width: "5%",
                mRender: function (data, type, row) {
                    if (checkboxHabilitado === 0)
                        return "<input id='materialSeleccionado' class='materialSeleccionado icheckbox_flat-green' type='checkbox' disabled>";
                    else {
                        if ($.inArray(row.IdRequisicionDetalle, idsDetalleReq) !== -1) {
                            $(".reqCancelar"+ row.IdRequisicionDetalle).prop("disabled", true);
                            return "<input id='materialSeleccionado' class='materialSeleccionado icheckbox_flat-green' type='checkbox' checked='true'>";
                        }
                        else {
                            $(".reqCancelar"+ row.IdRequisicionDetalle).prop("disabled", false);
                            return "<input id='materialSeleccionado' class='materialSeleccionado icheckbox_flat-green' type='checkbox'>";
                        }
                    }
                }
            },
            { 'data': "CantidadParaSolicitar", orderable: true, width: "15%" },
            { 'data': "Proyecto", orderable: true, width: "80%" },
            { width: "15%",
                mRender: function (data, type, row) {
                    if (row.IdProyecto !== "-1") {
                        if (checkboxHabilitado === 0)
                            return "<button type='button' id='req_cancelar' class='btn btn-danger btn-sm' disabled>Cancelar</button>";
                        else
                            return "<button type='button' id='req_cancelar' class='btn btn-danger btn-sm reqCancelar"+ row.IdRequisicionDetalle +"'>Cancelar</button>";
                    }
                    else
                        return "";
                }
            }
        ],
        "order": [[ 1, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
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
                $('#requisicionesTable').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(msjError);
                $("#errorModal").modal("show");
            break;
       }
    }, "json");
}