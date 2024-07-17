function loadDataTable(permisoAdministrar, permisoCotizar) {
    $('#provTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/proveedores/proveedoresData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".provTable-error").html("");
                $("#provTable").append('<tbody class="provTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#provTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Nombre", orderable: true, width: "20%", className: 'details-control' },
            { 'data': "Telefono", orderable: true, width: "10%" },
            { 'data': "Representante", orderable: true, width: "20%" },
            { 'data': "Email", orderable: true, width: "15%" },
            { orderable: false, width: "35%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    
                    if (permisoAdministrar) {
                        buttons += "<button type='button' id='editar' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Editar</button>";
                        buttons += "<button type='button' id='eliminar' style='margin-right:5px' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    }
                    if (permisoCotizar) {
                        buttons += "<button type='button' id='precioMaterial' class='btn btn-primary btn-sm precioDetail'><i class='fa fa-usd'></i>&nbsp;Precio</button>";
					}
                    return buttons;
                }
            },
			{ orderable: false, width: "35%",
                mRender: function (data, type, row) {
                    var buttonkg = "";

                    if (permisoCotizar) {
                 	    buttonkg += "<button type='button' id='precioMaterialKg' class='btn btn-primary btn-sm precioDetailKg'><i class='fa fa-usd'></i>&nbsp;PrecioxKilo</button>";
					}
                    return buttonkg;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function resetValues() {
    $("#nombre").empty();
    $("#direccion").val("");
    $("#representante").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#rfc").val("");
    $("#diasCredito").val("");
    $("#limiteCredito").val("");
    $("#formProv" ).validate().resetForm();
    $("#formProv :input").removeClass('error');
}

function openModalMP() {
    resetValues();
    $("#accion").val(0);
    $("#idRegistro").val(0);
    $('#nuevoProvModal').modal('show');
}
//accion 0 => guardar, 1 => editar
//idRegistro en editar trae el Id del que se eliminará, en alta viene con 0
function guardarProveedor(accion, idRegistro, nombre) {
    var data = $("#formProv").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    
    $.each (data, function(key, value) {
        if (value.name == "limiteCredito")
            datos[value.name] = value.value.replace(/\,/g, '');
        else
            datos[value.name] = value.value;
    });
    datos["nombre"] = nombre;
    //console.log(datos);
    //guardar en bd
    $.post("./pages/proveedores/datos.php", datos, function(result) {
        $('#provTable').DataTable().ajax.reload();
        
        var msj1 = "";
        var msj2 = "";
        var msjError = "";
        
        if (accion == 0) { //alta
            msj1 = "DADO DE ALTA";
            msj2 = "DAR DE ALTA";
        }
        else {
            msj1 = "EDITADO";
            msj2 = "EDITAR";
        }
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA "+ msj1 +" EL PROVEEDOR.");
                $("#successModal").modal("show");
            break;
            case 1:
                msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR AL "+ msj2 +". ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
            case 2:
                msjError = result["result"];
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text("ERROR AL "+ msj2 +": "+ msjError);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function loadEditarProveedor(data) {
    //$("#nombre").val(data.Nombre);
    var option = new Option(data.Nombre, data.IdProveedor, true, true);
    $('#nombre').append(option);
    $("#direccion").val(data.Direccion);
    $("#representante").val(data.Representante);
    $("#telefono").val(data.Telefono);
    $("#email").val(data.Email);
    $("#rfc").val(data.Rfc);
    $("#diasCredito").val(data.DiasCredito);
    $("#limiteCredito").val(data.LimiteCredito);
    $("#accion").val(1);
    $("#idRegistro").val(data.IdProveedor);
    $('#nuevoProvModal').modal('show');
    $("#formProv").validate().resetForm();
    $(".error").removeClass("error");
}

function eliminarProveedor(idProveedor) {
    //eliminacion en bd
    var datos = { "accion":'baja', "id":idProveedor };
    
    $.post("./pages/proveedores/datos.php", datos, function(result) {
        $('#provTable').DataTable().ajax.reload();

        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA ELIMINADO EL PROVEEDOR.");
                $("#successModal").modal("show");
            break;
            case 1:
                $("#errorModal .modal-body").text("ERROR AL ELIMINAR. ERROR DE BASE DE DATOS.");
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

function eliminarRegistro(id, tipo) {
    eliminarProveedor(id);
}

function autoCompleteMaterialesInventario() {
    $('.nombre').select2( {
        placeholder: "Nombre del proveedor",
        //allowClear: true,
        tags: true,
        ajax: {
            url: './pages/proveedores/autocompletes.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    nombreAutocomplete: 'proveedor',
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