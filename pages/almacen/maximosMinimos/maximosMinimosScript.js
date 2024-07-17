function inicializaTablaMaximosMinimos() {
    $('#maximosMinimosTabla').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/almacen/maximosMinimos/maximosMinimosData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".maximosMinimosTabla-error").html("");
                $("#maximosMinimosTabla").append('<tbody class="maximosMinimosTabla-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#maximosMinimosTabla_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Material", orderable: true, width: "25%" },
            { 'data': "CantidadMinima", orderable: true, width: "20%" },
            { 'data': "CantidadMaxima", orderable: true, width: "20%" },
            {
                mRender: function (data, type, row) {
                   if (row.Alerta == 1)
                        return "<input class='alerta icheckbox_flat-green' checked type='checkbox' disabled>";
                    else
                        return "<input class='alerta icheckbox_flat-green' type='checkbox' disabled>";
                 }, sortable: false, width: "10%"
            },
            {   orderable: false, width: "15%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    buttons += "<button type='button' id='mmeditar' class='btn btn-success btn-sm'><i class='fa fa-trash'></i>&nbsp;Editar</button>";
                    buttons += "<button type='button' id='mmeliminar' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function llenaMateriales() {
    $('#mm_material').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
        ajax: {
            url: './pages/almacen/maximosMinimos/datos.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
                return {
                    accion: 'autocompleteMateriales',
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

function resetValuesMaxMin() {
    $("#mm_material").empty();
    $("#mm_min").val(0);
    $("#mm_max").val(0);
    $("#formMaxMin" ).validate().resetForm();
    $("#formMaxMin :input").removeClass('error');
}

function guardarMaxMin(accion, idRegistro) {
    var data = $("#formMaxMin").serializeArray();
    var datos = {};
    datos["accion"] = accion === "0" ? "alta" : "editar";
    
    $.each(data, function(key, value) {
        if (value.name !== "alerta")
            datos[value.name] = value.value;
    });
    datos["max"] = datos["max"].replace(/\,/g, '');
    datos["alerta"] = $('#mm_alerta').is(':checked');
    datos["idRegistro"] = idRegistro;
    console.log(datos);
    //guardar en bd
    $.post("./pages/almacen/maximosMinimos/datos.php", datos, function(result) {
        $('#maximosMinimosTabla').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
            break;
            case 1:
                $("#errorModal .modal-body").text("ERROR DE BASE DE DATOS. "+ result["result"]);
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

function loadEditarMinMax(data) {
    console.log(data);
    $("#mm_material").append('<option selected value="'+ data.IdMaterial +'">'+ data.Material +'</option>');
    $("#mm_min").val(data.CantidadMinima);
    $("#mm_max").val(data.CantidadMaxima);
    $("#mm_alerta").prop('checked', data.Alerta == 1 ? true : false);
    $("#accion").val(1);
    $("#idMaxMin").val(data.IdInventarioMaxMin);
    $('#nuevaMaxMinModal').modal('show');
    $("#formMaxMin").validate().resetForm();
    $(".error").removeClass("error");
}

function eliminarRegistro(id, tipo) {
    if (tipo === "2")
        eliminarMinMax(id);
    else
        eliminarMaterial_InventarioInicial(id);
}

function eliminarMinMax(idMaterial) {
    //eliminacion en bd
    var datos ={ "accion":'baja', "id":idMaterial };
    
    $.post("./pages/almacen/maximosMinimos/datos.php", datos, function(result) {
        $('#maximosMinimosTabla').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
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