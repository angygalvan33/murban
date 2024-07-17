function inicializaTablaConsultasReq() {
    $('#requisicionesConsultaTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/requisiciones/consultas/consultasData.php", //json datasource
            type: "post", //method, by default get
            error: function(){ //error handling
                $(".requisicionesConsultaTable-error").html("");
                $("#requisicionesConsultaTable").append('<tbody class="requisicionesConsultaTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#requisicionesConsultaTable_processing").css("display", "none");
            },
            data: function(data) {
                data.Status = $("#statusCons").val(),
                data.IdProyecto = $("#idProyectoConsValue").val()
            }
        },
        'createdRow': function(row, data, dataIndex) { },
        'columns': [
            { 'data': "IdRequisicion", orderable: true, width: "10%" },
            { 'data': "Proyecto", orderable: true, width: "15%", visible: false },
            { 'data': "CantidadSolicitada", orderable: true, width: "5%" },
            { 'data': "CantidadAtendida", orderable: true, width: "5%" },
            { 'data': "Material", orderable: true, width: "25%" },
            { 'data': "Estado", orderable: true, width: "25%",
                mRender: function (data, type, row) {
                    if (row.Estado === "CANCELADA" || row.Estado === "PARCIALMENTE CANCELADA")
                        return "<a href='#' data-toggle='tooltip' data-html='true' title='<p>Cancelada por "+ row.UsuarioCancelacion +" el "+ row.FechaCancelacion +"</p><p>"+ row.Motivo +"</p>'>"+ row.Estado +"</a>";
                    else
                        return "<p>"+ row.Estado +"</p>";
                }
            },
            { 'data': "Fecha", orderable: true, width: "15%" }
        ],
        "order": [[ 0, "asc" ]],
        'drawCallback': function (settings) {
            $('[data-toggle="tooltip"]').tooltip();
        },
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
    muestraOcultaProyecto();
}

function muestraOcultaProyecto() {
    var tabla = $('#requisicionesConsultaTable').DataTable();
    
    if ($("#idProyectoConsValue").val() === "-2")
        tabla.columns([ 1 ]).visible(true, true);
    else
        tabla.columns([ 1 ]).visible(false, true);
}

function llenaProyectosCons() {
    $('#idProyectoCons').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/requisiciones/consultas/autocompletes.php',
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