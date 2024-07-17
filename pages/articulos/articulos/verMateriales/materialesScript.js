function verautoCompleteMaterialesArt() {
    $('#vermaterialMat').empty();
    $('#vermaterialMat').select2({
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/articulos/articulos/autocompleteArticulos.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'material',
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

function verinicializaTablaMaterialesArt() {
    $('#vertablaMaterialesArt').DataTable( {
        'processing': true,
        'serverSide': true,
        "bDestroy": true,
        'ajax': {
            url: "pages/articulos/articulos/verMateriales/materialesData.php", //json datasource
            type: "post", //method, by default get
            error: function(){ //error handling
                $(".vertablaMaterialesArt-error").html("");
                $("#vertablaMaterialesArt").append('<tbody class="vertablaMaterialesArt-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#vertablaMaterialesArt_processing").css("display", "none");
            },
            data: function(d) {
                d.IdArticulo = $(".detalles").attr("id")
            }
        },
        'columns': [
            { 'data': "Cantidad", sortable: false, width: "10%", orderable: false },
            { 'data': "Material", sortable: false, width: "20%", orderable: true },
            {
                mRender: function (data, type, row) {
                    var buttons = "<button type='button' id='editarMaterialArt' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Editar</button>";
                    buttons += "<button type='button' id='eliminarMaterialArt' style='margin-right:5px' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return buttons;
                }, width: "30%", sortable: false,
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function veraddMaterialToArticulo(cantidad, idMaterial) {
    var datos = { "accion":'nuevoMaterial', "Cantidad":cantidad, "IdMaterial":idMaterial, "IdArticulo":$(".detalles").attr("id") };

    $.post("./pages/articulos/articulos/verMateriales/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $("#vercantidadMat").val("");
                $("#vermaterialMat").empty();
                $('#vertablaMaterialesArt').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
            case 2:
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function vereditarMaterialArt(idArticuloDetalle, cantidad) {
    var datos = { "accion":'editarMaterial', "Cantidad":cantidad, "IdArticuloDetalle":idArticuloDetalle };
    
    $.post("./pages/articulos/articulos/verMateriales/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#vertablaMaterialesArt').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
            case 2:
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function vereliminarMaterialArt(idArticuloDetalle, idMaterial) {
    var datos = { "accion":'eliminarMaterial', "IdArticuloDetalle":idArticuloDetalle };
    
    $.post("./pages/articulos/articulos/verMateriales/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#vertablaMaterialesArt').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
            case 2:
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}