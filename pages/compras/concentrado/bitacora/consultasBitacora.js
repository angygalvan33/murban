function loadDataTableBitacora(idProveedor, idMaterial) {
    if ($.fn.dataTable.isDataTable('#bitacoraConsultaTable')) {
        tablabitacora.destroy();
    }

    tablabitacora = $('#bitacoraConsultaTable').DataTable( {
        'processing': true,
        'serverSide': true,
        "bDestroy": true,
        'ajax': {
            url: "./pages/compras/concentrado/bitacora/bitacoraData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".bitacoraConsultaTable-error").html("");
                $("#bitacoraConsultaTable").append('<tbody class="bitacoraConsultaTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#bitacoraConsultaTable_processing").css("display", "none");
            },
            data: function(data) {
                data.FechaIni = $("#fIniB").val(),
                data.FechaFin = $("#fFinB").val(),
                data.idProveedor = idProveedor,
                data.idMaterial = idMaterial
            }
        },
        'columns': [
            { 'data': "FolioRequi", orderable: true, width: "5%" },
            { 'data': "FechaReq", orderable: true, width: "10%" },
            { 'data': "Material", orderable: true, width: "15%" },
            { 'data': "CantidadSolicitada", orderable: true, width: "5%" },
            { 'data': "Piezas", orderable: true, width: "10%" },
            { 'data': "CantidadAtendida", orderable: true, width: "5%" },
            { 'data': "FolioOC", orderable: true, width: "10%" },
            { 'data': "Fecha", orderable: true, width: "10%" },
            { 'data': "Proveedor", orderable: true, width: "10%" },
            { 'data': "Comprador", orderable: true, width: "10%" },
            { 'data': "FechaIngr", orderable: true, width: "10%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function llenaMaterialesBit() {
    $('.materialCtrl').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/compras/concentrado/bitacora/autocomplete.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    accion: 'materiales',
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

function llenaProveedoresBit() {
    $('.proveedorBit').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/compras/concentrado/bitacora/autocomplete.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    accion: 'proveedores',
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