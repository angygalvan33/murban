function loadProductosObraDataTable() {
    $('#productosObraTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/obras/listadoProductos/listadoProductosData.php", //json datasource
            type: "post", //method, by default get
            data: {
                "IdObra": $(".detalles").attr("id")
            },
            error: function() { //error handling
                $(".productosObraTable-error").html("");
                $("#productosObraTable").append('<tbody class="productosObraTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#productosObraTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Producto", orderable: true, width: "30%" },
            { orderable: false, width: "30%",
                mRender: function (data, type, row) {
                    var spath = '';
                    if (row.Foto != null)
                        spath = 'images/articulos/'+ row.Foto;
                    else
                        spath = 'images/fotoparte.png';
                    return "<img src='"+ spath +"' style='width:50px;height:50px'/>";
                }
            },
            { 'data': "Cantidad", orderable: true, width: "20%" },
            { orderable: true, width: "20%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(parseFloat(row.Costo).toFixed(2));
                }
            },
            {  orderable: false, width: "10%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    buttons += "<button type='button' id='eliminar_p' class='btn btn-danger btn-sm btn-block'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return buttons;
                 }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function autoCompleteProductosProyecto() {
    $('.productoO').select2( {
        placeholder: "Selecciona el producto",
        allowClear: true,
        ajax: {
            url: './pages/obras/autocompleteObras.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'productos',
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

function agregarAProyecto(idObra) {
    var dataProducto = $('#productoO').select2('data');
    var idProducto = dataProducto[0].id;
    var cantidad = $("#cantidadO").val().replace(/\,/g, '');

    var datos = { "accion":'agregarAProyecto', "idProducto":idProducto, "cantidad":cantidad, "idObra":idObra };
    
    $.post("./pages/obras/datos.php", datos, function(result) {
        $('#productosObraTable').DataTable().ajax.reload();

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                resetFormProductosxObra();
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
    },"json");
}

function resetFormProductosxObra() {
    $("#productoO").empty();
    $("#cantidadO").val("");
}

function eliminarProducto(idRequisicion) {
    //console.log(idRequisicion);
    var datos = { "accion":"eliminarDeProyecto", "idRequisicion":idRequisicion };

    $.post("./pages/obras/datos.php", datos, function(result) {
        $('#productosObraTable').DataTable().ajax.reload();

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                resetFormProductosxObra();
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
    },"json");
}