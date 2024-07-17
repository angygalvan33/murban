function autoCompleteObrasEspecial2() {
    $('.obraEspecial2').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/requisiciones/autocompleteOC.php',
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

function autoCompleteUsuariosEspecial2() {
    $('.solicitaEspecial2').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/requisiciones/autocompleteOC.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'usuario',
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

function autoCompleteMaterialesEspecial2(idProveedor) {
    $('.materialEspecial2').select2( {
        placeholder: "Selecciona una opción",
        tags: true,
        allowClear: true,
        createTag: function (params) {
            $("#materialEspecial2").text("");
            return {
                id: "-1",
                text: params.term
            };
        },
        ajax: {
            url: './pages/requisiciones/autocompleteOC.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                $("#precioEspecial").prop('disabled', true);
                return {
                    nombreAutocomplete: 'material',
                    IdProveedor:idProveedor,
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

function inicializaMaterialesByProveedorEspecial2() {
    $('#matsEspecial2').DataTable( {
        'lengthMenu': [ [10,15, 25, -1], [10,15, 25, "Todos"] ],
        'data': [],
        'columns': [
            { 'data': "Cantidad", sortable: false, width: "10%", orderable: false },
            { 'data': "Nombre", sortable: false, width: "15%", orderable: true },
            { 'data': "Obra", sortable: false, width: "10%", orderable: true },
            { 'data': "Solicita", sortable: false, width: "15%", orderable: true },
            {
                mRender: function (data, type, row) {
                    if (row.UnicaOcasion === true)
                        return "<p>Sí</p>";
                    else
                        return "<p>No</p>";
                 }, width: "15%",sortable: false,
            },
            { 'data': "FechaRequiEsp", sortable: false, width: "10%", orderable: true },
            {
                mRender: function (data, type, row) {
                    var button = "<button type='button' id='eliminarEspecial' style='margin-right:5px' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return button;
                }, width: "10%", sortable: false,
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function agregaDetalleOC_Especial2() {
    if ($("#formRequisicion2").valid()) {
        var dataO = $('#obraEspecial2').select2('data');
        var dataS = $('#solicitaEspecial2').select2('data');
        var nuevo = {};
        nuevo["IdObra"] = dataO[0].id;
        nuevo["Obra"] = dataO[0].text;
        nuevo["Cantidad"] = $("#cantidadEspecial2").val().replace(/\,/g, '');
        nuevo["IdMaterial"] = -1;
        nuevo["Nombre"] = $('#materialEspecial2').val();
        nuevo["Solicita"] = dataS[0].text;
        nuevo["IdSolicita"] = dataS[0].id;
        nuevo["UnicaOcasion"] = $("#unicaOcasion").prop("checked");
        nuevo["FechaRequiEsp"] = $("#fecharequiesp").val();
        
        $('#matsEspecial2').DataTable().row.add(nuevo).draw();
        $("#materialEspecial2").val("");
        $("#cantidadEspecial2").val("");
        $("#fecharequiesp").val("");
        $("#unicaOcasion").prop("checked", false);
    }
}

function eliminarOCEspecial2(actualRowEspecial) {
    actualRowEspecial.remove().draw();
    $('#matsEspecial2').DataTable().data().count();
}

function resetValuesEspecial() {
    $('#matsEspecial2').DataTable().clear().draw();
    $("#unicaOcasion").prop("checked", false);
    $("#materialEspecial2").val("");
    $("#obraEspecial2").empty();
    $("#cantidadEspecial2").val("");
    $("#formRequisicion2").validate().resetForm();
    $("#formRequisicion2 :input").removeClass('error');
    $("#descripcionRequisicion2").val("");
}

function guardarRequisicion2(observaciones) {
    $("#BtnGuardar2").prop("disabled", true);
    //obteniendo datos
    var datosDetalleCompra = [];
    var formData = new FormData();
    var cont = 1;
    
    $('#matsEspecial2').DataTable().rows().every( function () {
        var d = this.data();
        var nuevo = {};
        nuevo["IdMaterial"] = d.IdMaterial;
        nuevo["Material"] = d.Nombre;
        nuevo["Cantidad"] = parseFloat(d.Cantidad.replace(/\,/g, ''));
        nuevo["IdObra"] = d.IdObra;
        nuevo["IdSolicita"] = d.IdSolicita;
        nuevo["UnicaOcasion"] = d.UnicaOcasion;
        nuevo["FechaRequiEsp"] = d.FechaRequiEsp;
        datosDetalleCompra.push(nuevo);
        formData.append(cont+":_IdObra",d.IdObra);
        formData.append(cont+":_IdMaterial",d.IdMaterial);
        formData.append(cont+":_Material",d.Nombre);
        formData.append(cont+":_Cantidad",d.Cantidad.replace(/\,/g, ''));
        formData.append(cont+":_IdSolicita",d.IdSolicita);
        formData.append(cont+":_UnicaOcasion",d.UnicaOcasion);
        formData.append(cont+":_FechaRequiEsp",d.FechaRequiEsp);
        cont++;
    });
    
    if (datosDetalleCompra.length > 0) {
        formData.append("Observaciones",observaciones);
        formData.append("accion","guardarRequisicionEspecial");

        $.ajax( {
            type: 'POST',
            url: './pages/requisiciones/datos.php',
            data: formData,
            success: function(result) {
                result = result.slice(1, result.length - 1);
                var resultado = result.split(",");
                var error = resultado[0].split(":")[1];
                var msj = resultado[1].split(":")[1];
                
                switch (error) {
                    case "0":
                        $("#successModal .modal-body").text(msj);
                        $("#successModal").modal("show");
                        //reinicio de valores
                        resetValuesEspecial();
                        mostrarOcultarNuevaRequisiscion(0, 2);
                        $('#requisicionesTable').DataTable().ajax.reload();
                        $('#requisicionesSinAutorizarTable').DataTable().ajax.reload();
                    break;
                    case "1":
                        msjError = msj;
                        $("#errorModal .modal-title").text("ERROR");
                        $("#errorModal .modal-body").text("ERROR DE BASE DE DATOS. "+ msjError);
                        $("#errorModal").modal("show");
                    break;
                    case "2":
                        msjError = msj;
                        $("#avisosModal .modal-title").text("ERROR DE VALIDACION. "+ msjError);
                        $("#avisosModal").modal("show");
                    break;
                }
            },
            error: function(response) {
                $("#errorModal .modal-body").text("ERROR AL GUARDAR");
                $("#errorModal").modal("show");
            },
            processData: false,
            contentType: false
        });
    }
    else {
        $("#BtnGuardar2").prop("disabled", false);
        $("#avisosModal .modal-title").text("REQUISICIÓN ESPECIAL");
        $("#avisosModal .modal-body").text("REQUISICIÓN VACÍA");
        $("#avisosModal").modal("show");
    }
}