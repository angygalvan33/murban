function inicializaTablaConciliacion(idUbicacion) {
    if ($.fn.dataTable.isDataTable('#concilicacionTabla')) {
        tablaconciliacion.destroy();
    }

    tablaconciliacion = $('#concilicacionTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/conciliacion/concilicacionData.php", //json datasource
            type: "post", //method, by default get
            error: function(){ //error handling
                $(".concilicacionTabla-error").html("");
                $("#concilicacionTabla").append('<tbody class="concilicacionTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#concilicacionTabla_processing").css("display", "none");
            },
            data: {
                "IdUbicacion": idUbicacion
            }
        },
        'columns': [
            { 'data': "Material", orderable: true, width: "40%" },
            { 'data': "Proyecto", orderable: true, width: "40%" },
            { 'data': "Cantidad", orderable: true, width: "20%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function autoCompleteUbicacionMaterial() {
    $('.ubicacionm').select2( {
        placeholder: "Seleccionar ubicación",
        allowClear: true,
        ajax: {
            url: './pages/almacen/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'ubicacion',
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