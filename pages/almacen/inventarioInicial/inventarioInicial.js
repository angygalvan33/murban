function inicializaTablaInventarioInicial() {
    $('#inventarioInicialTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/inventarioInicial/inventarioInicialData.php", //json datasource
            type: "post", //method, by default get
            error: function(){ //error handling
                $(".inventarioInicialTabla-error").html("");
                $("#inventarioInicialTabla").append('<tbody class="inventarioInicialTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#inventarioInicialTabla_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Material", orderable: true, width: "15%" },
            { 'data': "Descripcion", orderable: true, width: "20%" },
            { 'data': "Medida", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    var html = "";

                    if (row.Medida != "") {
                        var datos = $.parseJSON(row.Medida);
                        $.each(datos, function(i, val) {
                            html += val.nombre +": "+ val.valor + val.unidad +" ";
                        });
                    }
                    
                    html += "";
                    return html;
                }
            },
            { 'data': "Categoria", orderable: true, width: "15%" },
            { 'data': "Cantidad", orderable: true, width: "10%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function autoCompleteMaterialesInventario() {
    $('.iimaterial').select2( {
        placeholder: "Selecciona el material",
        allowClear: true,
        ajax: {
            url: './pages/almacen/autocompletes.php',
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

function resetFormInventarioInicial() {
    $("#iimaterial").empty();
    $("#iicantidad").val("");
    $("#iiprecio").val("");
}

function agregarAInventarioInicial() {
    var dataMaterial = $('#iimaterial').select2('data');
    var idMaterial = dataMaterial[0].id;
    var datos = { "accion":'agregarAInventario', "idMaterial":idMaterial, "cantidad":$("#iicantidad").val().replace(/\,/g, ''), "precioUnitario":$("#iiprecio").val().replace(/\,/g, '') };
    
    $.post("./pages/almacen/inventarioInicial/datos.php", datos, function(result) {
        $('#inventarioInicialTabla').DataTable().ajax.reload();
        $('#movimientosInventarioTable').DataTable().ajax.reload();
         switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                resetFormInventarioInicial();
            break;
            case 1:
                var msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
            case 2:
                var msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR DE VALIDACIÓN. "+ msjError);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}
//solo se editara precio y cantidad
function loadEditarMaterialInventario(data) {
    $("#idMaterialII").val(data.IdMaterial);
    $("#iicant").val(data.Cantidad);
    $("#iiprec").val(data.Precio);
    $("#iieditartMaterialModal").modal("show");
}

function editarMaterial_InventarioInicial(idMaterial, cantidad, precio) {
    var datos = { "accion":'editarMaterial', "idMaterial":idMaterial, "cantidad":cantidad, "precioUnitario":precio };
    
    $.post("./pages/almacen/inventarioInicial/datos.php", datos, function(result) {
        $('#inventarioInicialTabla').DataTable().ajax.reload();

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

function eliminarMaterial_InventarioInicial(idMaterial) {
    var datos = { "accion":'eliminarMaterial', "idMaterial":idMaterial };
    
    $.post("./pages/almacen/inventarioInicial/datos.php", datos, function(result) {
        $('#inventarioInicialTabla').DataTable().ajax.reload();

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

function getPrecioMaterialInventarioInicial(idMaterial) {
    var datos = { "accion":'getPrecioMaterial', "idMaterial":idMaterial };
    
    $.post("./pages/almacen/inventarioInicial/datos.php", datos, function(result) {
        var precio = "";

        if(result["error"] === 0) {
            precio = result["result"];
            $("#iiprecio").val(precio);
            $("#iiprecio").prop("disabled", false);
        }
        else {
            $("#iiprecio").val("");
            $("#iiprecio").prop("disabled", false);
        }
    }, "json");
}