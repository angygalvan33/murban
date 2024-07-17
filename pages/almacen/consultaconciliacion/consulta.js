function inicializaTablaAjustesxEvento(idEvento) {
    if ($.fn.dataTable.isDataTable('#ajustesxEventoTabla')) {
        tablaajustesevento.destroy();
    }

    tablaajustesevento = $('#ajustesxEventoTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/consultaconciliacion/consultaData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".ajustesxEventoTabla-error").html("");
                $("#ajustesxEventoTabla").append('<tbody class="ajustesxEventoTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#ajustesxEventoTabla_processing").css("display", "none");
            },
            data: {
                "IdEvento": idEvento
            }
        },
        'columns': [
            { 'data': "Material", orderable: true, width: "30%" },
            { 'data': "Ajuste", orderable: true, width: "10%" },
            { 'data': "Nota", orderable: true, width: "30%" },
            { 'data': "Usuario", orderable: true, width: "20%" },
            { 'data': "Creado", orderable: true, width: "10%" }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function autoCompleteEventos() {
    $('.cevento').select2( {
        placeholder: "Selecciona el evento",
        allowClear: true,
        ajax: {
            url: './pages/almacen/consultaconciliacion/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'eventos',
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