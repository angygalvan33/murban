function loadDataTable(permisoAdministrar, permisoPresupuestos, fechaIni, fechaFin) {
    $('#obTable').DataTable( {
        'processing': true,
        'serverSide': true,
        "bDestroy": true,
        'ajax': {
            url: "pages/obras/obraData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".obTable-error").html("");
                $("#obTable").append('<tbody class="obTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#obTable_processing").css("display", "none");
            },
            data: {
                "fechaIni": fechaIni,
                "fechaFin": fechaFin
            }
        },
        'columns': [
            { 'data': "Nombre", orderable: true, width: "10%", className: 'details-control' },
            { 'data': "Terminado", orderable: true, width: "10%",
                mRender: function (data, type, row) {
                    if (permisoAdministrar) {
                        if (row.Terminado == 1)
                            return "<input id='terminar' class='terminar icheckbox_flat-green' checked type='checkbox'>";
                        else
                            return "<input id='terminar' class='terminar icheckbox_flat-green' type='checkbox'>";
                    }
                }
            },
            { 'data': "TipoObra", orderable: true, width: "10%" },
            { 'data': "Cliente", orderable: true, width: "10%" },
            { 'data': "FechaEntregaEstimada", orderable: true, width: "10%" },
            { 'data': "DiasEntregaRestante", orderable: true, width: "10%" },
            { orderable: true, width: "5%", className: 'alinearDerecha',
                mRender: function (data, type, row) {
                    if (permisoPresupuestos)
                        return "$"+ formatNumber(parseFloat(row.Presupuesto).toFixed(2));
                    else
                        return "No disponible";
                }
            },
            { orderable: true, width: "5%", className: 'alinearDerecha',
                mRender: function (data, type, row) {
                    if (permisoPresupuestos)
                        return "$"+ formatNumber(parseFloat(row.Pendiente).toFixed(2));
                    else
                        return "No disponible.";
                }
            },
            { orderable: true, width: "10%", className: 'alinearDerecha',
                mRender: function (data, type, row) {
                    if (permisoPresupuestos)
                        return "$"+ formatNumber(parseFloat(row.Gastado).toFixed(2));
                    else
                        return "No disponible.";
                }
            },
            { orderable: false, width: "20%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    buttons += "<button type='button' id='gastos' style='margin-right:5px' class='btn btn-info btn-sm gastos btn-block'><i class='fa fa-edit'></i>&nbsp;Gastos</button>";
                    buttons += "<button type='button' id='materiales' style='margin-right:5px' class='btn btn-warning btn-sm materiales btn-block'><i class='fa fa-cubes'></i>&nbsp;Materiales</button>";
                    
                    if (permisoAdministrar) {
                        buttons += "<button type='button' id='productos' class='btn btn-primary btn-sm btn-block'><i class='fa fa-cart-plus'></i>&nbsp;Productos</button>";
                        buttons += "<button type='button' id='editar' style='margin-right:5px' class='btn btn-success btn-sm btn-block'><i class='fa fa-edit'></i>&nbsp;Editar</button>";
                        buttons += "<button type='button' id='eliminar' class='btn btn-danger btn-sm btn-block'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    }
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function resetValues() {
    $("#nombre").val("");
    $("#tipoObra").val("");
    $("#descripcion").val("");
    $("#ComboClientes").val("");
    $("#ocFolio").val("");
    $("#ocMonto").val("");
	$("#artFotoNombre").val("");
	$("#foto_articulo").attr("src", "images/fotoparte.png");
	$("#archivo").val("");
	$("artFotoNombre").val("");
    var hoy = moment().format("DD/MM/YYYY");
    $('#fechaEntregaEstimada').val(hoy);
    $('#fechaH').val(hoy);
    $("#formObra").validate().resetForm();
    $("#formObra :input").removeClass('error');
}

function openModalObra() {
    resetValues();
    $("#accion").val(0);
    $("#idRegistro").val(0);
    $('#nuevaObraModal').modal('show');
}
//accion 0 => guardar, 1 => editar
//idRegistro en editar trae el Id del que se eliminarÃ¡, en alta viene con 0
function guardarObra(accion, idRegistro) {
    var formData = new FormData();
    var data = $("#formObra").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    datos["fechaEntregaEstimada"] = $("#fechaH").val();
    datos["foto"] = $("#artFotoNombre").val();
    
    $.each(data, function(key, value) {
        if (value.name == "presupuesto" || value.name == "ocMonto")
            datos[value.name] = value.value.replace(/\,/g, '');
        else
            datos[value.name] = value.value;
    });

    $.post("./pages/obras/datos.php", datos, function(result) {
        $('#obTable').DataTable().ajax.reload();

        var msj1 = "";
        var msj2 = "";
        var msjError = "";
        
        if (accion == 0) {
            msj1 = "DADO DE ALTA";
            msj2 = "DAR DE ALTA";
        }
        else {
            msj1 = "EDITADO";
            msj2 = "EDITAR";
        }
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA "+ msj1 +" LA OBRA.");
                $("#successModal").modal("show");
				$('#obTable').DataTable().ajax.reload();
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

function loadEditarObra(data) {
    $("#nombre").val(data.Nombre);
    $("#tipoObra").val(data.IdTipoObra);
    $("#descripcion").val(data.Descripcion);
    $("#ComboClientes").val(data.IdCliente);
    $("#ocFolio").val(data.OCFolio);
    $("#ocMonto").val(data.OCMonto);
    $('#fechaEntregaEstimada').val(data.FechaEntregaEstimada);
    $('#fechaH').val(data.FechaEntregaEstimada);
	$('#archivo').val('');
    $("#accion").val(1);
    $("#idRegistro").val(data.IdObra);
    $('#nuevaObraModal').modal('show');

	if (data.Archivo != null && data.Archivo.length > 0) {
	   $("#foto_articulo").attr("src", "images/obra/"+ data.Archivo);
	   $("artFotoNombre").val(data.Archivo);
	}
    else {
		$("#foto_articulo").attr("src", "images/fotoparte.png");
		$("artFotoNombre").val("");
	}
    
    $("#formObra").validate().resetForm();
    $(".error").removeClass("error");
}

function eliminarObra(idObra) {
    //eliminacion en bd
    var datos = { "accion":'baja', "id":idObra };
    
    $.post("./pages/obras/datos.php", datos, function(result) {
        $('#obTable').DataTable().ajax.reload();

        if (result["error"] == 0) { //insertado
            $("#successModal .modal-body").text("SE HA ELIMINADO LA OBRA");
            $("#successModal").modal("show");
        }
        else {
            $("#errorModal .modal-body").text("ERROR AL ELIMINAR. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
    }, "json");
}

function eliminarRegistro(id, tipo) {
    eliminarObra(id);
}

function llenaTiposObra() {
    var datos = { "accion":'getTiposObra' };

    $.post("./pages/obras/datos.php", datos, function(result) {
        $.each(result.result, function(i, val) {
            $("select[name='tipoObra']").append($("<option>", {
                value: val.IdTipoObra,
                text: val.Nombre
            }));
        });
    }, "json");
}

function llenaClientes() {
    var datos = { "accion":'getClientes' };

    $.post("./pages/obras/datos.php", datos, function(result) {
        $.each(result.result, function(i, val) {
            $("select[name='ComboClientes']").append($("<option>", {
                value: val.IdCliente,
                text: val.Nombre
            }));
        });
    }, "json");
}

function cambiarFotoArt() {
    var archivos = $("#archivo")[0].files;
    var formData = new FormData();
    
    if (archivos.length > 0) {
        formData.append("accion", "subeFoto");
        formData.append("artFoto", archivos[0]);
        
        $.ajax( {
            type: 'POST',
            url: './pages/obras/datos.php',
            data: formData,
            success: function(result) {
                if (result == false) {
                    $("#errorModal .modal-body").text("Error al cargar la foto. Asegúrese de seleccionar un archivo PNG / JPG");
                    $("#errorModal").modal("show");
                }
                else {
                    $("#foto_articulo").attr("src", "./images/obra/"+ result);
					$("#artFotoNombre").val(result);
                }
            },
            error: function(response) {
                $("#errorModal .modal-body").text("Error al cargar la foto.");
                $("#errorModal").modal("show");
            },
            processData: false,
            contentType: false
        });
    }
    else {
        alert("No se ha seleccionado un archivo válido.");
    }
}

function terminarObra(idObra, estatus) {
    var datos = { "accion":"terminar", "IdObra":idObra, "Estatus":estatus };
        
    $.post("./pages/obras/datos.php", datos, function(result) {
        if (result["error"] == 0) {
            $('#obTable').DataTable().ajax.reload();
            $("#successModal .modal-body").text("SE HA TERMINADO LA OBRA");
            $("#successModal").modal("show");
        }
        else {
            $("#errorModal .modal-body").text("ERROR AL CAMBIAR EL ESTADO DE TERMINADO. POR FAVOR INTENTA DE NUEVO.");
            $("#errorModal").modal("show");
        }
    }, "json");
}