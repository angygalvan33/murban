function loadDataTableAdministracion() {
    $('#adminCChTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'responsive':true,
        'ajax': {
            url: "pages/cajaChica/administracion/adminCajaChicaData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".adminCChTable-error").html("");
                $("#adminCChTable").append('<tbody class="catTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#adminCChTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "IdCajaChica",orderable: true, width: "5%", className: 'details-control' },
            { 'data': "Usuario", orderable: true, width: "30%" },
            { 'data': "PresupuestoDisponible", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    return "$"+ formatNumber(parseFloat(row.PresupuestoDisponible).toFixed(2));
                } 
            },
            { 'data': "Activa", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    if (row.Activa == 1)
                        buttons += "<button type='button' id='edoCerrar' style='margin-right:5px' class='btn btn-default btn-sm'><i class='fa fa-lock'></i>&nbsp;Cerrar</button>";
                    else
                        buttons += "<button type='button' id='edoAbrir' style='margin-right:5px' class='btn btn-default btn-sm'><i class='fa fa-unlock'></i>&nbsp;Abrir</button>";
                    return buttons;
                 }
            },
            { width: "40%", orderable: false,
                mRender: function (data, type, row) {
                    var buttons = "";
                    buttons += "<button type='button' id='rembolsar' style='margin-right:5px' class='btn btn-info btn-sm'><i class='fa fa-angle-double-left'></i>&nbsp;Reponer efectivo</button>";
                    if (row.FacturasPendientes == 1)
                        buttons += "<button type='button' id='rembolsarFacturas' class='btn btn-warning btn-sm facturasDetail'><i class='fa fa-angle-double-left'></i>&nbsp;Pagar facturas</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function resetValuesAdmin() {
    $("#presupuesto").val("");
    $("#usuario").empty();
    $("#usuarioValue").val("");
    $("#formCC").validate().resetForm();
    $("#formCC :input").removeClass('error');
}

function openModalNC() {
    resetValuesAdmin();
    $("#accion").val(0);
    $("#idRegistro").val(0);
    $(".usuarioDiv").css("display", "block");
    $('#nuevaCajaChicaModal').modal('show');
}

function loadEditarCajaChica(data) {
    resetValuesAdmin();
    $("#presupuesto").val(data.PresupuestoTotal);
    $(".usuarioDiv").css("display", "none");
    $("#accion").val(1);
    $("#idRegistro").val(data.IdCajaChica);
    $('#nuevaCajaChicaModal').modal('show');
    $("#formCC").validate().resetForm();
    $(".error").removeClass("error");
}

function loadReembolsar() {
    $("#totalRembolso").val("");
    $("#formRCCh").validate().resetForm();
    $("#formRCCh :input").removeClass('error');
    $("#rembolsarModal").modal("show");
}

function guardarCajaChica(accion, idRegistro) {
    var data = $("#formCC").serializeArray();
    var datos = {};
    var idCajaChicaAbonos = -1;

    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    
    $.each(data, function(key, value) {
        datos[value.name] = value.value;
        
        if (value.name == "presupuesto")
            datos[value.name] = value.value.replace(/\,/g, '');
    });
    //guardar en bd
    $.post("./pages/cajaChica/administracion/datos.php", datos, function(result) {
        $('#adminCChTable').DataTable().ajax.reload();
        console.log(result);
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
                $("#successModal .modal-body").text("SE HA "+ msj1 +" LA CAJA CHICA.");
                $("#successModal").modal("show");
                IdCajaChicaAbonos = result['idCajaChicaAbonos'];
                imprimirReciboAbono(IdCajaChicaAbonos);
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

function reembolsar(idCajaChica, total, descripcion) {
    var datos = {};
    datos["accion"] = "reembolsar";
    datos["IdCajaChica"] = idCajaChica;
    idCajaChicaReembolso = -1;
    datos["Total"] = total.replace(/\,/g, '');
    datos["Descripcion"] = descripcion;
    //guardar en bd
    $.post("./pages/cajaChica/administracion/datos.php", datos, function(result) {
        $('#adminCChTable').DataTable().ajax.reload();
        var msjError = "";
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA REALIZADO EL REEMBOLSO CORRECTAMENTE");
                $("#successModal").modal("show");
                idCajaChicaReembolso = result['idCajaChicaReembolso'];
                imprimirReciboReembolso(idCajaChicaReembolso);
            break;
            case 1:
                msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR AL REEMBOLSAR. ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
            case 2:
                msjError = result["result"];
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(msjError);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function cambiarEdoCajaChica(id, tipo) {
    //tipo 1-> abrir 
    //tipo 0-> cerrar
    var datos = {};
    datos["accion"] = "cambiarEdoC";
    datos["IdCajaChica"] = id;
    datos["Edo"] = tipo;
    //guardar en bd
    $.post("./pages/cajaChica/administracion/datos.php", datos, function(result) {
        $('#adminCChTable').DataTable().ajax.reload();
        var msjError = "";
        
        switch(result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA REALIZADO EL CAMBIO CORRECTAMENTE");
                $("#successModal").modal("show");
            break;
            case 1:
                msjError = result["result"];
                $("#errorModal .modal-body").text("ERROR AL CAMBIAR ESTADO. ERROR DE BASE DE DATOS. "+ msjError);
                $("#errorModal").modal("show");
            break;
            case 2:
                msjError = result["result"];
                $("#avisosModal .modal-title").text("ERROR DE VALIDACION");
                $("#avisosModal .modal-body").text(msjError);
                $("#avisosModal").modal("show");
            break;
        }
    }, "json");
}

function imprimirReciboReembolso(idCajaChicaDetalle) {
    window.location.href = "./excel/reportes/reciboReembolso.php?idCajaChicaDetalle="+ idCajaChicaDetalle;
}

function imprimirReciboAbono(idCajaChicaDetalle) {
    window.location.href = "./excel/reportes/reciboAbono.php?idCajaChicaDetalle="+ idCajaChicaDetalle;
}