function loadDataTablePagosPendientes() {
    $('#ppusuarioCChTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'responsive':true,
        'ajax': {
            url: "pages/cajaChica/pagosPendientes/pagosPendientesData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".ppusuarioCChTable-error").html("");
                $("#ppusuarioCChTable").append('<tbody class="usuarioCChTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#ppusuarioCChTable_processing").css("display", "none");
            },
            data: function(data){
                data.IdUsuario = $("#ppusuarioCUValue").val()
            }
        },
        'columns': [
            { 'data': "Creado",orderable: true, width: "10%" },
            { 'data': "Obra",orderable: true, width: "15%" },
            { 'data': "Material", orderable: true, width: "15%" },
            { 'data': "FolioFactura", orderable: true, width: "15%" },
            { 'data': "Total", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(parseFloat(row.Total).toFixed(2));
                }
            }
        ],
        "order": [[ 0, "desc" ]],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function resetValues() {
    $("#obraAut").empty();
    $("#obraValue").val("");
    $("#materialAut").empty();
    $('#proveedorAut').empty();
    $("#materialValue").val("");
    $("#ctotalFactura").val("");
    $("#cfolioFactura").val("");
    $(".cfolio").css("display", "none");
    $('#cfacturada').prop('checked', false);
    $('#cfolioPendiente').prop('checked', false);
    $("#cfolioFactura").prop("disabled", false);
    $("#formCompra").validate().resetForm();
    $("#formCompra :input").removeClass('error');
}

function openModalNuevaCompra() {
    resetValues();
    $('#nuevaCompraModal').modal('show');
}

function guardarCompra() {
    var datos = {};
    var idCajaChicaDetalle = -1;
    var materialData = $('#materialAut').select2('data');

    if (materialData) {
        datos["IdMaterial"] = materialData[0].id;
        datos["Material"] = materialData[0].text;
    }
    
    var obraData = $('#obraAut').select2('data');

    if (obraData)
        datos["IdObra"] = obraData[0].id;
    
    datos["accion"] = "guardar";
    datos["IdUsuario"] = $("#ppusuarioCUValue").val();
    
    var proveedorData = $('#proveedorAut').select2('data');

    if(proveedorData) {
        datos["IdProveedor"] = proveedorData[0].id;
        datos["Proveedor"] = proveedorData[0].text;
    }
    
    if ($('#cfacturada').prop('checked')) {
        datos["Facturada"] = 1;
        datos["FolioFactura"] = $("#cfolioFactura").val();
    }
    else {
        datos["Facturada"] = 0;
        datos["FolioFactura"] = null;
    }

    datos["Total"] = $("#ctotalFactura").val().replace(/\,/g, '');
    //guardar en bd
    $.post("pages/cajaChica/pagosPendientes/datos.php", datos, function(result) {
        $('#usuarioCChTable').DataTable().ajax.reload();
        
        var msj1 = "";
        var msj2 = "";
        var msjError = "";
        msj1 = "DADO DE ALTA";
        msj2 = "DAR DE ALTA";
        
        switch (result["error"]) {
            case 0:
                $('#ppusuarioCChTable').DataTable().ajax.reload();
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                idCajaChicaDetalle = result['idCajaChicaDetalle'];
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
        
        getPresupuestoDeUsuario($("#ppusuarioCUValue").val());
        imprimirRecibo(idCajaChicaDetalle);
    }, "json");
}

function getPresupuestoDeUsuario(idUsuario) {
    var datos = {};
    datos["accion"] = "getPresupuestoDeUsuario";
    datos["idUsuario"] = idUsuario;
    
    $.post("pages/cajaChica/pagosPendientes/datos.php", datos, function(result) {
        $("#ppCCh").html("<h4>Presupuesto:&nbsp;<strong>$"+ formatNumber(result.toFixed(2)) +"<strong></h4>");
    }, "json");
}

function autoCompleteProveedoresCU() {
    $('.proveedorAut').select2( {
        placeholder: "Selecciona una opción",
        tags: true,
        createTag: function (params) {
            $("#proveedorAut").text("");
            return {
                id: "-1",
                text: params.term
            }
        },
        ajax: {
            url: 'pages/cajaChica/pagosPendientes/datos.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    accion: 'proveedorAutocomplete',
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

function imprimirRecibo(idCajaChicaDetalle) {
    window.location.href = "./excel/reportes/reciboPago.php?idCajaChicaDetalle="+ idCajaChicaDetalle;
}