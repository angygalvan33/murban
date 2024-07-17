function inicializaSalidasTable() {
    $('#salidasTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/salidas/salidasData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".salidasTabla-error").html("");
                $("#salidasTabla").append('<tbody class="salidasTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#salidasTabla_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Nombre", orderable: true, width: "15%", className: 'details-control' },
            { 'data': "Cantidad", orderable: true, width: "20%" },
            { 'data': "MedidaNombre", orderable: true, width: "30%",
                mRender: function (data, type, row) {
                    // Si el IdMaterial es -1, la medida llega como "-"
                    if (row.MedidaNombre != "-") {
                        var html = "";
                        if (row.MedidaNombre != "") {
                            var datos = $.parseJSON(row.MedidaNombre);
                            $.each(datos, function(i, val) {
                                html += "<i>"+ val.nombre +"</i>: "+ val.valor + val.unidad +" ";
                            });
                        }

                        html += "";
                        return html;
                    }
                    else {
                        return "No disponible";
                    }
                }
            },
            { 'data': "CategoriaNombre", orderable: true, width: "10%" },
            { 'data': "Ubicacion", orderable: true, width: "10%" } //cambiar por Ubicacion
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function inicializaSalidaMaterialesPorObraTabla() {
    $('#salidaMatxObraTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/salidas/materialesPorObraData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".salidaMatxObraTabla-error").html("");
                $("#salidaMatxObraTabla").append('<tbody class="salidaMatxObraTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#salidaMatxObraTabla_processing").css("display", "none");
            },
            data: {
               "idMaterial": $(".detalles").attr("id"),
               "nombreMaterial": $(".detalles").attr("nombreMaterial"),
               "precioUnitario": $(".detalles").attr("precioUnitario")
            }
        },
        'columns': [
            { 'data': "NombreObra", orderable: true, width: "15%" },
            { 'data': "Cantidad", orderable: true, width: "20%" },
            { orderable: false, width: "15%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    if(row.IdObra !== "-1")
                        buttons += "<button type='button' id='salidaMatxObra' class='btn btn-warning btn-sm'><i class='fa fa-arrow-right'></i>&nbsp;Salida</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function autoCompletePersonal() {
    $('.personalSalida').select2( {
        placeholder: "Selecciona...",
        allowClear: true,
        ajax: {
            url: './pages/almacen/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'personal',
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

function autoCompleteObras() {
    $('.obraSalida').select2( {
        placeholder: "Selecciona...",
        allowClear: true,
        ajax: {
            url: './pages/almacen/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'obra',
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

function salidaDeMaterial(idObra, idMaterial, cantidad, idPersonal, nombreMaterial, precioUnitario, idObraSalida) {
    cantidad = cantidad.replace(/\,/g, '');
    var datos = { "accion":"salidaDeMaterial", "idObra":idObra, "idMaterial":idMaterial, "nombreMaterial":nombreMaterial, "precioUnitario":precioUnitario, "cantidad":cantidad, "idPersonal":idPersonal, "idObraSalida":idObraSalida, "descripcion":null };
    
    $.post("./pages/almacen/salidas/datos.php", datos, function(result) {
        $('#salidasTabla').DataTable().ajax.reload();

        switch (result["error"]) {
            case 0:
                $('#salidasTabla').DataTable().ajax.reload();
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