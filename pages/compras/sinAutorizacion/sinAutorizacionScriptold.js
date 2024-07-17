function loadDataTableSinAutorizacion(permisoAutorizar) {
    $('#sinAutTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/compras/sinAutorizacion/sinAutorizacionData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".sinAutTable-error").html("");
                $("#sinAutTable").append('<tbody class="sinAutTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#sinAutTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "IdOrdenCompra", orderable: true, width: "5%", className: 'details-control' },
            { 'data': "Creado", orderable: true, width: "10%" },
            { 'data': "NombreProveedor", orderable: true, width: "15%" },
            { 'data': "Total", orderable: true, width: "15%", className: 'alinearDerecha',
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(row.Total);
                }
            },
            { 'data': "Descripcion", orderable: true, width: "30%" },
            { 'data': "Genera", orderable: true, width: "15%" },
            { width: "10%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    
                    if (permisoAutorizar) {
                        if (row.PagoRequerido === "1")
                            buttons += "<button type='button' id='ea_autorizarPagar' class='btn btn-success btn-sm'>Autorizar y Pagar</button>";
                        else
                            buttons += "<button type='button' id='ea_autorizar' class='btn btn-success btn-sm'>Autorizar</button>";
                    }
                    buttons += "<button type='button' id='ea_cancelar' style='margin-right:5px' class='btn btn-danger btn-sm'>Cancelar</button>";
                    return buttons;
                 }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function autorizarOC(idRegistro, idUsuario, tipo) {
    var datos = {};
    datos["accion"] = 'autorizar';
    datos["id"] = idRegistro;
    datos["idUsuarioAutoriza"] = idUsuario;
    datos["tipo"] = tipo;
    
    $.post("./pages/compras/sinAutorizacion/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA AUTORIZADO LA ORDEN DE COMPRA.");
                $("#successModal").modal("show");
                $('#sinAutTable').DataTable().ajax.reload();
                $('#emitidasTable').DataTable().ajax.reload();
            break;
            case 1:
                var msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR AL AUTORIZAR LA ORDEN DE COMPRA. ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}