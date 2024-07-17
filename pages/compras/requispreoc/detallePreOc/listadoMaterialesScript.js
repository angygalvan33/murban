function loadDataTableMaterialesPreOC() {
    tablaProveedoresRequi = $('#materialesPreOCTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/requispreoc/detallePreOc/listadoMaterialesData.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdProveedor": $(".detalleMaterialesPreOCTable").attr("id")
            },
            error: function() { //error handling
                $(".materialesPreOCTable-error").html("");
                $("#materialesPreOCTable").append('<tbody class="materialesPreOCTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#materialesPreOCTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "CantidadPedida", orderable: true, width: "10%" },
            { 'data': "Material", orderable: true, width: "30%" },
            { orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    return "$"+ parseFloat(row.PrecioUnitario).toFixed(4);
                }
            },
            { 'data': "FechaCotizacion", orderable: true, width: "20%" },
            { orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    return "$"+ parseFloat((row.CantidadPedida*row.PrecioUnitario)).toFixed(4);
                }
            },
            { width: "10%", className: 'text-center', orderable: false,
                mRender: function (data, type, row) {
                    var buttons = "";
                    if (row.Seleccionada == 1)
                            buttons += "<input id='seleccionada' class='seleccionada icheckbox_flat-green' checked type='checkbox'>";
                        else
                            buttons += "<input id='seleccionada' class='seleccionada icheckbox_flat-green' type='checkbox'>";

                    return buttons;
                }
            },
            { width: "10%", className: 'text-center', orderable: false,
                mRender: function (data, type, row) {
                    var buttons = "";
                    buttons += "<button type='button' id='req_regresar' class='btn btn-danger btn-sm reqRegresar"+ row.IdRequisicionDetalle +"'>Más tarde</button>";
                    return buttons;
                }
            }
        ],
        "order": [[ 1, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function regresarReq(idDetalle, idAtendida) {
    var datos = {};
    datos["accion"] = 'regresarRequisicion';
    datos["idRequisicionDetalle"] = idDetalle;

    $.post("./pages/compras/requispreoc/detallePreOc/datos.php", datos, function(result) {
        var msjError = result["result"];
        
        if ($('#requispreoc_Table').DataTable().data().count() > 0)
            $('#descargaPreOC').prop("disabled", true);
        else
            $('#descargaPreOC').prop("disabled", false);

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(msjError);
                $("#successModal").modal("show");
                $('#materialesPreOCTable').DataTable().ajax.reload();
                $('#requispreoc_Table').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}

function seleccionarMaterial(idRequisicionAtendida, valor) {
    var datos = { "accion":"seleccionarMaterial", "IdRequisicionAtendida":idRequisicionAtendida, "valor":valor };

    $.post("./pages/compras/requispreoc/detallePreOc/datos.php", datos, function(result) {
        if (result["error"] == 1) {
            $("#errorModal .modal-body").text("ERROR AL SELECCIONAR. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
        else {
            $('#materialesPreOCTable').DataTable().ajax.reload();
        }
    }, "json");
}