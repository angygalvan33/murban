function loadDataTableMaterialesRequisicionesxkilo(checkboxHabilitado, tipo, idProveedor, callback) {
    tablaDetalleRequisiciones = $('#materialesRequisicionesTablexkilo').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/requisicionesxkilo/detalleMaterialesxkilo/listadoMaterialesDataxkilo.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdMaterial": $(".detallesMatRequisicionesxkilo").attr("id"),
                "Tipo": tipo,
                "IdProveedor": idProveedor
            },
            error: function() { //error handling
                $(".materialesRequisicionesTablexkilo-error").html("");
                $("#materialesRequisicionesTablexkilo").append('<tbody class="materialesRequisicionesTablexkilo-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#materialesRequisicionesTablexkilo_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "TotalPeso", orderable: true, width: "5%" },
			{ width: "5%",
                mRender: function (data, type, row) {
				   return "Kilos";
                }
            },
            { 'data': "CantidadPreOC", orderable: true, width: "10%" },
            { 'data': "Proyecto", orderable: true, width: "10%" },
            { 'data': "CantidadAtendida", orderable: true, width: "10%", className: 'text-center' },
            { 'data': "ExistenciaStock", orderable: true, width: "10%", className: 'text-center' },
            { 'data': "Solicita", orderable: true, width: "10%", className: 'text-center' },
            { 'data': "FechaReq", orderable: true, width: "10%", className: 'text-center' },
            { width: "10%", className: 'text-center', orderable: false,
                mRender: function (data, type, row) {
                    if (row.IdProyecto !== "-1" && parseFloat(row.ExistenciaStock) > 0) {
                        if (checkboxHabilitado === 0)
                            return "<button type='button' id='req_asignarxkilo' style='margin-right:5px' class='btn btn-warning btn-sm' disabled>Asignar</button>";
                        else
                            return "<button type='button' id='req_asignarxkilo' style='margin-right:5px' class='btn btn-warning btn-sm'>Asignar</button>";
                    }
                    else
                        return "";
                }
            },
            { width: "10%", className: 'text-center', orderable: false,
                mRender: function (data, type, row) {
					if (checkboxHabilitado === 0) {
                        return "<button type='button' id='req_comprarxkilo' style='margin-right:5px' class='btn btn-success btn-sm' disabled>Comprar</button>";
                    }
                    else {
                        if (row.IdProveedor === "-1")
                            return "<button type='button' id='req_comprarxkilo' style='margin-right:5px' class='btn btn-success btn-sm' disabled>Comprar</button>";
                        else
                            return "<button type='button' id='req_comprarxkilo' style='margin-right:5px' class='btn btn-success btn-sm'>Comprar</button>";
                    }
                }
            },
            { width: "10%", className: 'text-center', orderable: false,
                mRender: function (data, type, row) {
                    if (checkboxHabilitado === 0)
                        return "<button type='button' id='req_cancelarxkilo' class='btn btn-danger btn-sm' disabled>Cancelar</button>";
                    else
                        return "<button type='button' id='req_cancelarxkilo' class='btn btn-danger btn-sm reqCancelar"+ row.IdRequisicionDetalle +"'>Cancelar</button>";
                }
            }
        ],
        "order": [[ 1, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
	callback();
}

function cuentaCantidad() {
	var $rows = $("#materialesRequisicionesTablexkilo tr");
}

function cancelarReq(idDetalle, motivo) {
    var datos = {};
    datos["accion"] = 'cancelarRequisicion';
    datos["idRequisicionDetalle"] = idDetalle;
    datos["motivo"] = motivo;

    $.post("./pages/compras/requisicionesxkilo/detalleMaterialesxkilo/datosxkilo.php", datos, function(result) {
        var msjError = result["result"];

        switch(result["error"]) {
            case 0:
                $("#successModal .modal-body").text(msjError);
                $("#successModal").modal("show");
                $('#requisicionesTablexkilo').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}