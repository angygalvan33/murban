function mostrarOcultarNuevoArticulo(accion) {
    //ocultar
    if (accion == 0)
        $("#nuevoArticulo").slideUp("slow");
    //mostrar
    else {
        resetValuesArticulos();
        $("#nuevoArticulo").slideDown("slow");
    }
}

function resetValuesArticulos() {
    $("#nombreArticulo").val("");
    $("#descripcionArticulo").val("");
    $("#artLinea").empty();
    $(".cantidadMat").val("");
    $(".materialMat").empty();
    $('#tablaMaterialesArt').DataTable().clear().draw();
    $("#formNuevoArticulo").validate().resetForm();
    $("#formNuevoArticulo :input").removeClass('error');
    $("#formNuevoMaterialArt").validate().resetForm();
    $("#formNuevoMaterialArt :input").removeClass('error');
}

function autoCompleteLineas(idAutocomplete) {
    $('.'+ idAutocomplete).select2( {
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
                    nombreAutocomplete: 'linea',
                    searchTerm: params.term
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

function guardarNuevoArticulo(nombre, descripcion, idLinea, Foto, clave) {
    $("#loading").modal("show");
    var datos = {};
    datos["accion"] = "guardarArticulo";
    datos["Clave"] = clave;
    datos["Nombre"] = nombre;
    datos["Descripcion"] = descripcion;
    datos["IdLinea"] = idLinea;
    datos["Foto"] = Foto;
    var materialesxArticulo = [];
    var fotosxArticulo = [];
    
    $('#tablaMaterialesArt').DataTable().rows().every( function () {
        var d = this.data();
        var nuevo = {};
        nuevo["Cantidad"] = d.Cantidad;
        nuevo["IdMaterial"] = d.IdMaterial;
        materialesxArticulo.push(nuevo);
    });

    if (materialesxArticulo.length == 0)
        datos["materiales"] = null;
    else
        datos["materiales"] = materialesxArticulo;
    
    $('#tablafotosArt').DataTable().rows().every( function () {
        var dfoto = this.data();
        var nuevofoto = {};
        nuevofoto["Foto"] = dfoto.Foto;
        fotosxArticulo.push(nuevofoto);
    });

    if (fotosxArticulo.length == 0)
        datos["fotos"] = null;
    else
        datos["fotos"] = fotosxArticulo;;

    //console.log(materialesxArticulo);

    $.post("./pages/articulos/articulos/datos.php", datos, function(result) {
        $("#loading").modal("hide");

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                resetValuesArticulos();
                $("#nuevoArticulo").slideUp("slow");
                $('#tablaArticulos').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
            case 2:
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function inicializaTablaArticulos() {
    $('#tablaArticulos').DataTable( {
        'processing': true,
        'serverSide': true,
        "bDestroy": true,
        'ajax': {
            url: "pages/articulos/articulos/articulosData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".tablaArticulos-error").html("");
                $("#tablaArticulos").append('<tbody class="tablaArticulos-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#tablaArticulos_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Clave", orderable: true, width: "5%" },
            { 'data': "Nombre", orderable: true, width: "10%" },
            { orderable: false,  width: "15%", mRender: function (data, type, row) {
                    var spath = '';
                    if (row.Foto != 'null' || row.Foto != "" || !row.Foto)
                        spath = 'images/articulos/' + row.Foto;
                    else
                        spath = 'images/fotoparte.png';
                    return "<img src='"+ spath +"' style='width:70px;height:70px'/>";
                }
            },
            { 'data': "Descripcion", orderable: true, width: "20%" },
            { 'data': "Linea", orderable: true, width: "10%" },
            { orderable: false, width: "10%",
                mRender: function (data, type, row) {
                    return "<button type='button' id='verMateriales' class='btn btn-primary btn-sm verMateriales'><i class='fa fa-eye'></i>&nbsp;Ver materiales</button>";
                }
            },
            { orderable: false, width: "30%",
                mRender: function (data, type, row) {
                    var buttons = "<button type='button' id='editarArticulo' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Editar</button>";
                    buttons += "<button type='button' id='eliminarArticulo' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>"
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function editarArticulo(idArticulo, clave, nombre, descripcion, idLinea) {
    var datos = {};
    datos["accion"] = "editar";
    datos["IdArticulo"] = idArticulo;
    datos["Clave"] = clave;
    datos["Nombre"] = nombre;
    datos["Descripcion"] = descripcion;
    datos["IdLinea"] = idLinea;
    
    $.post("./pages/articulos/articulos/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#tablaArticulos').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(result["result"]);
                $("#errorModal").modal("show");
            break;
            case 2:
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(result["result"]);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function eliminarArticulo(idArticulo) {
    //eliminacion en bd
    var datos = { "accion":'baja', "id":idArticulo };
    
    $.post("./pages/articulos/articulos/datos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#tablaArticulos').DataTable().ajax.reload();
            break;
            case 1:
                $("#errorModal .modal-body").text(result["result"]);
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