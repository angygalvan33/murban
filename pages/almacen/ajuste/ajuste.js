function autoCompleteMaterialesAjuste() {
    $('.amaterial').select2( {
        placeholder: "Selecciona el material",
        allowClear: true,
        ajax: {
            url: './pages/almacen/ajuste/autocompletes.php',
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

function buscaCantidadMaterial(idMaterial, idProyecto) {
	var datos = { "accion":'getCantidadMaterial', "idMaterial":idMaterial, "idProyecto":idProyecto };
    
    $.post("./pages/almacen/ajuste/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                if (result["result"] === null) {
                	$("#acantidad").val('Agregar en Inventario Inicial');
                    $("#aconteo").prop( "disabled", true );
                    $("#anota").prop( "disabled", true );
                }
                else
                	$("#acantidad").val(result["result"]);
            break;
        }
    }, "json");
}

function autoCompleteProyectosMaterial(idMaterial) {
    $('.aproyecto').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/almacen/ajuste/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    nombreAutocomplete: 'proyectos',
                    idMaterial:idMaterial,
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

function agregarAjusteMaterial(idMaterial, idProyecto, cantidad, conteo, nota) {
    if ($("#form_ajuste").valid()) {
        var datos = { "accion":'agregarAjuste', "idMaterial":idMaterial, "idProyecto":idProyecto, "cantidad":cantidad, "conteo":conteo, "nota":nota };
        
        $.post("./pages/almacen/ajuste/datos.php", datos, function(result) {
            $('#ajusteTabla').DataTable().ajax.reload();
            resetFormAjustes();

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
                    var msjError = result["result"];
                    $("#errorModal .modal-body").text("ERROR DE VALIDACIÓN. "+ msjError);
                    $("#errorModal").modal("show");
                break;
            }
        },"json");
    }
}

function resetFormAjustes() {
    $("#amaterial").val("");
    $("#aproyecto").val("");
    $("#acantidad").val("");
    $("#aconteo").val("");
    $("#anota").val("");
}

function inicializaTablaAjustesMaterial() {
    if ($.fn.dataTable.isDataTable('#ajusteTabla')) {
        tablaajustes.destroy();
    }

    tablaajustes = $('#ajusteTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/ajuste/ajustesData.php", //json datasource
            type: "post", //method, by default get
            error: function() {//error handling
                $(".ajusteTabla-error").html("");
                $("#ajusteTabla").append('<tbody class="ajusteTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#ajusteTabla_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Material", orderable: true, width: "20%" },
            { 'data': "Cantidad", orderable: true, width: "10%" },
            { 'data': "Conteo", orderable: true, width: "10%" },
            { 'data': "Ajuste", orderable: true, width: "10%" },
            { 'data': "Nota", orderable: true, width: "30%" },
            { 'data': "Usuario", orderable: true, width: "20%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function registraEvento(evento) {
    var datos = { "accion":'registrarEvento', "evento":evento };
    var registros = $('#ajusteTabla').find('tr').length - 1;

    if ($('#form_evento').valid()) {
        if (registros > 0) {
            $.post("./pages/almacen/ajuste/datos.php", datos, function(result) {
                $('#ajusteTabla').DataTable().ajax.reload();
                $('#aevento').val("");

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
                        var msjError = result["result"];
                        $("#errorModal .modal-body").text("ERROR DE VALIDACIÓN. "+ msjError);
                        $("#errorModal").modal("show");
                    break;
                }
            }, "json");
        }
        else {
            var msjError = "NO EXISTEN REGISTROS NUEVOS";
            $("#errorModal .modal-body").text("ERROR. "+ msjError);
            $("#errorModal").modal("show");
        }
    }
}

function eliminarAjuste(IdHistoricoAjustes) {
    var datos = {};
    datos["accion"] = 'acancelar';
    datos["id"] = IdHistoricoAjustes;

    $.post("./pages/almacen/ajuste/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#matsOCReq').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
        }
    }, "json");
}