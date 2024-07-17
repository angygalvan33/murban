function autoCompleteMaterialesArt() {
    $('.materialMat').empty();
    $('.materialMat').select2( {
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

function inicializaTablaMaterialesArt() {
    $('#tablaMaterialesArt').DataTable( {
        'destroy': true,
        'lengthMenu': [ [10,15, 25, -1], [10,15, 25, "Todos"] ],
        'data': [],
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
        },
        'footerCallback': function ( row, data, start, end, display ) { }
    });
}

function addMaterialToArticulo(cantidad, idMaterial, nombreMaterial) {
    var nuevo = { };
    nuevo["Cantidad"] = cantidad;
    nuevo["IdMaterial"] = idMaterial;
    nuevo["Material"] = nombreMaterial;

    var cantidadRepetido = actualizarMaterialByCantidad(nuevo["IdMaterial"], nuevo["Cantidad"]);

    if (cantidadRepetido != 0)
        nuevo["Cantidad"] = parseFloat(nuevo["Cantidad"]) + parseFloat(cantidadRepetido);
    
    $('#tablaMaterialesArt').DataTable().row.add(nuevo).draw();
    $(".cantidadMat").val("");
    $(".materialMat").empty();
}

//si ya existe un material se actualiza la cantidad
function actualizarMaterialByCantidad(idMaterial, cantidad) {
    var existe = false;
    var actualRow = null, cantidadActual = 0;
    
    $('#tablaMaterialesArt').DataTable().rows().every(function (rowIdx, tableLoop, rowLoop) {
        d = this.data();
        existe = false;
        //si es un material de catálogo de materiales
        if (idMaterial != -1 && d.IdMaterial == idMaterial)
            existe = true;
        if (existe) {
            cantidadActual = d.Cantidad;
            actualRow = $('#tablaMaterialesArt').DataTable().row(rowIdx);
        }
    });
    
    if (actualRow != null)
        actualRow.remove().draw();

    return cantidadActual;
}

function eliminarMaterialArt(actualRow) {
    actualRow.remove().draw();
}

function editarMaterialArt(idMaterial, cantidad) {
    var existe = false;
    var actualRow = null;
    var nombreMaterial = "";
    $('#tablaMaterialesArt').DataTable().rows().every(function (rowIdx, tableLoop, rowLoop) {
        d = this.data();
        existe = false;
        //si es un material de catálogo de materiales
        if (d.IdMaterial == idMaterial)
            existe = true;
        
        if (existe) {
            nombreMaterial = d.Material;
            actualRow = $('#tablaMaterialesArt').DataTable().row(rowIdx);
        }
    });
    
    if(actualRow != null) {
        actualRow.remove().draw();
        var nuevo = {};
        nuevo["Cantidad"] = cantidad;
        nuevo["IdMaterial"] = idMaterial;
        nuevo["Material"] = nombreMaterial;
        $('#tablaMaterialesArt').DataTable().row.add(nuevo).draw();
    }
}