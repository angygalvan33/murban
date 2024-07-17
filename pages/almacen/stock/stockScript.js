function inicializaStockTable() {
    $('#stockTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/stock/stockData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".stockTabla-error").html("");
                $("#v").append('<tbody class="stockTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#stockTabla_processing").css("display", "none");
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

function inicializaMaterialesStockTabla(permisoReducir, permisoAsignar) {
    $('#MatStockTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/stock/materialesStockData.php", //json datasource
            type: "post", //method, by default get
            error: function(){ //error handling
                $(".MatStockTabla-error").html("");
                $("#MatStockTabla").append('<tbody class="MatStockTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#MatStockTabla_processing").css("display", "none");
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

                    if (row.IdObra === '-1') {
                        if (permisoAsignar)
                            buttons += "<button type='button' id='asignarMatxObra' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Asignar</button>";
                        else
                            buttons += "<button type='button' id='asignarMatxObra' class='btn btn-success btn-sm' disabled><i class='fa fa-edit'></i>&nbsp;Asignar</button>";
                    }
                    else {
                        if (permisoReducir)
                            buttons += "<button type='button' id='reducirMatxObra' style='margin-right:5px' class='btn btn-warning btn-sm'><i class='fa fa-arrow-right'></i>&nbsp;Reducir</button>";
                        else
                            buttons += "<button type='button' id='reducirMatxObra' style='margin-right:5px' class='btn btn-warning btn-sm' disabled><i class='fa fa-arrow-right'></i>&nbsp;Reducir</button>";
                    }
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function resetAsignarModal() {
    $("#cantidadAsignar").val("");
    $("#obraAsignar").empty();
    $("#descripcionAsignar").val("");
    $("#errorcantidadAsignar").css("display", "none");
    $("#formAsignarMaterial" ).validate().resetForm();
    $("#formAsignarMaterial :input").removeClass('error');
}

function autoCompleteObrasStock() {
    $('.obraAsignar').select2( {
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

function asignacionDeMaterial(idObra, idMaterial, cantidad, descripcion, nombreMaterial) {
    cantidad = cantidad.replace(/\,/g, '');
    var datos = { "accion":"asignarMaterial", "idObra":idObra, "idMaterial":idMaterial, "nombreMaterial":nombreMaterial, "cantidad":cantidad, "descripcion":descripcion };
    
    $.post("./pages/almacen/stock/datos.php", datos, function(result) {
        $('#MatStockTabla').DataTable().ajax.reload();
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

function reducirMaterial(idMaterial, nombreMaterial, cantidad, idObra, reponerRequisicion) {
    cantidad = cantidad.replace(/\,/g, '');
    var datos = { "accion":"reducirMaterial", "idObra":idObra, "idMaterial":idMaterial, "nombreMaterial":nombreMaterial, "cantidad":cantidad, "reponerRequisicion":reponerRequisicion };
    
    $.post("./pages/almacen/stock/datos.php", datos, function(result) {
        $('#MatStockTabla').DataTable().ajax.reload();
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

function eliminarRegistro2(id, tipo) {
    if (tipo === "1")
        eliminarMaterialDeProyecto(id);
}

function eliminarMaterialDeProyecto(idProyecto) {
    var idMaterial = $(".detalles").attr("id");
    var cantidad = $("#cantidadEliminar").val();
    var material = $("#materialEliminar").val();
    //eliminacion en bd
    var datos = { "accion":'eliminarMaterial', "id":idMaterial, "idObra":idProyecto, "cantidad":cantidad, 'nombreMaterial':material };
    
    $.post("./pages/almacen/stock/datos.php", datos, function(result) {
        $('#MatStockTabla').DataTable().ajax.reload();
        
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
            case 2:
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text("ERROR AL ELIMINAR: "+ result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}