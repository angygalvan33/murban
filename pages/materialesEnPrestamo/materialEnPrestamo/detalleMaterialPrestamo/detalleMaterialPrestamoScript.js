function inicializaDetalleMaterialPrestamoTable() {
    $('#DetalleMaterialPrestamoPTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/materialesEnPrestamo/materialEnPrestamo/detalleMaterialPrestamo/detalleMaterialPrestamoData.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdMaterial":$(".detalles").attr("id")
            },
            error: function() { //error handling
                $(".DetalleMaterialPrestamoPTabla-error").html("");
                $("#DetalleMaterialPrestamoPTabla").append('<tbody class="DetalleMaterialPrestamoPTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#DetalleMaterialPrestamoPTablaprocessing").css("display", "none");
            }
        },
        'createdRow': function(row, data, dataIndex) {
            if (data.DiasDisponibles != null) {
                if (parseInt(data.DiasDisponibles) <= 0)
                    $(row).addClass('alertaPrestamo');
            }
        },
        'columns': [
            { 'data': "Cantidad", orderable: true, width: "10%" },
            { 'data': "Personal", orderable: true, width: "20%" },
            { 'data': "Descripcion", orderable: true, width: "20%" },
            { 'data': "Fecha", orderable: true, width: "15%" },
            { 'data': "DiasPrestamo", orderable: true, width: "10%" },
            { 'data': "DiasDisponibles", orderable: true, width: "10%" },
            { orderable: false, width: "15%",
                mRender: function (data, type, row) {
                    var buttons = "<button type='button' id='recibir' style='margin-right:5px' class='btn btn-warning btn-sm'><i class='fa fa-angle-double-left'></i>&nbsp;Recibir</button>";
                        buttons += "<button type='button' id='refrendar' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-angle-double-left'></i>&nbsp;Refrendar</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function loadEditarMaterialPrestamo(data) {
    $("#accion").val(1);
    $("#tipo").val(1); //prestamo = 1
    $("#idMaterial").prop("disabled", true);
    $("#idMaterial").append(new Option(data.Material, data.IdMaterial, true, true));
    $("#cantidad").val(data.Cantidad);
    $("#idPersonal").append(new Option(data.Personal, data.IdPersonal, true, true));
    $("#descripcion").val(data.Descripcion);
    $('#fecha').val(data.Fecha);
    $('#fechaH').val(data.Fecha);
    $('input[type=radio][name=tipoPrestamo][value=P]').iCheck('check');
    $('input[type=radio][name=tipoPrestamo][value=R]').iCheck('uncheck');
    $("#nuevoPrestamoModal").modal("show");
}

function refrendarPrestamo(idPrestamoResguardo, diasExtraPrestamo) {
    var datos = { "accion":"refrendar", "idPrestamoResguardo":idPrestamoResguardo, "diasExtraPrestamo":diasExtraPrestamo };
    $.post("./pages/materialesEnPrestamo/datos.php", datos, function(result) {
        $('#MaterialPrestamoPTabla').DataTable().ajax.reload();

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                var msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}

function cobrarMaterial(idPrestamoResguardo) {
    var datos = { "accion":"cobrar", "idPrestamoResguardo":idPrestamoResguardo };
    $.post("./pages/materialesEnPrestamo/datos.php", datos, function(result) {
        $('#MaterialPrestamoPTabla').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                var msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}