function getUnidadLabel(UnidadRef) {
	sunidad = 'pz';
	if (UnidadRef == 1)
		sunidad = 'm';
	else if (UnidadRef == 2)
		sunidad = 'cm';
	else if (UnidadRef == 3)
		sunidad = 'in';
	else if (UnidadRef == 4)
		sunidad = 'ft';
	else if (UnidadRef == 5)
		sunidad = 'gm';
	else if (UnidadRef == 6)
		sunidad = 'kg';
	else if (UnidadRef == 7)
		sunidad = 'm3';
	else if (UnidadRef == 8)
		sunidad = 'lt';
	return sunidad;
}

function loadDataTable(permisoAdministrar) {
    $('#matTable').DataTable( {
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/material/materialData.php", //json datasource
            type: "post", //method, by default get
            error: function() { //error handling
                $(".matTable-error").html("");
                $("#matTable").append('<tbody class="matTable-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#matTable_processing").css("display", "none");
            }
        },
        'columns': [
            { 'data': "Nombre", orderable: true, width: "15%", className: 'details-control' },
            { 'data': "Clave", orderable: true, width: "5%" },
            { 'data': "Descripcion", orderable: true, width: "15%" },
            { 'data': "MedidaNombre", orderable: true, width: "15%",
                mRender: function (data, type, row) {
                    var html = "<p><i>Tipo</i>: "+ row.MedidaNombre +". ";
                    var sunidad = getUnidadLabel(row.Unidad);

                    if(row.Medida != "") {
                        try {
                            var datos = $.parseJSON(row.Medida);
                            
                            $.each(datos, function(i, val) {
						        snombrelado = val.nombre;
                                if (snombrelado == 'Alto') {
                                    snombrelado = 'Calibre';
							        sunidad = 'mm';
						        }
                                
                                html += "<i>"+ snombrelado +"</i>: "+ val.valor + sunidad +" ";
                            });
					    }
						catch {
							html += "<i>El material se guardo incorrectamente por favor, modifiquelo ahora</i>";
						}
                    }

                    html += "";
                    return html;
                 }
            },
            { 'data': "CategoriaNombre", orderable: true, width: "5%" },
			{ 'data': "Usuario", orderable: true, width: "5%" },
			{ orderable: false, width: "20%",
                mRender: function (data, type, row) {
                    var buttons = "";
                    if (permisoAdministrar) {
                        buttons += "<button type='button' id='editar' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Editar</button>";
                        buttons += "<button type='button' id='eliminar' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                        buttons += "<button type='button' id='precioMatProveedor' class='btn btn-primary btn-sm precioDetail'><i class='fa fa-usd'></i>&nbsp;Precio</button>";
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
    $("#descripcion").val("");
    $("#tipoMedida").val("");
    $("#tiposDinamicos").empty();
    $('.idCategoriaNM').empty();
    $("#formMat").validate().resetForm();
    $("#formMat :input").removeClass('error');
}

function openModalMat() {
    resetValues();
    $("#accion").val(0);
    $("#idRegistro").val(0);
    $('#nuevoMatModal').modal('show');
}
//accion 0 => guardar, 1 => editar
//idRegistro en editar trae el Id del que se eliminará, en alta viene con 0
function guardarMaterial(accion, idRegistro) {
    var data = $("#formMat").serializeArray();
    var datos = {};
    datos["accion"] = accion == 0 ? "alta" : "editar";
    datos["id"] = idRegistro;
    
	$.each(data, function(key, value) {
        datos[value.name] = value.value;
		if ($("#tipoMedida").val() == 6 && value.name == 'Longitud') {
			datos["Largo"] = value.value;
		}
    });
	
    var inputs = $(".inputDinamico");
    var bandComa = true;
    var medida = "[";
	var cntcoma = 1;
    $.each(inputs, function(key, value) {
        medida += "{'nombre':" + "'"+ value.name +"',";
        medida += "'valor':" + "'"+ obtenerValorMedidaBase(value.value.replace(/,/g, '')) +"',";
		medida += "'unidad':" + "'"+ value.id +"'}";
		
        if ($("#tipoMedida").val() == 4 && bandComa == true) {
            medida += ",";
			if (cntcoma == 2)
				bandComa = false;
			cntcoma = cntcoma + 1;
        }
    });
    
    medida += "]";
    medida = medida.replace(/'/g, '"');
    
    try {
		var testjson = $.parseJSON(medida);
	}
    catch {
		$("#errorModal .modal-body").text("ERROR AL CONVERTIR LAS MEDIDAS POR FAVOR INTENTE DE NUEVO SI EL PROBLEMA PERSISTE POR FAVOR REPORTELO");
        $("#errorModal").modal("show");
		return;
	}
    
    datos["medida"] = medida;
    //guardar en bd
	$.post("./pages/material/datos.php", datos, function(result) {
        $('#matTable').DataTable().ajax.reload();
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
                $("#successModal .modal-body").text("SE HA "+ msj1 +" EL MATERIAL.");
                $("#successModal").modal("show");
            break;
            case 1:
                var msjError = result["result"];
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

function obtenerValorMedidaBase(valorAConvetir) {
    var idTipoMedida = $('select[name="tipoMedida"] option:selected').val();
    var valorUnidadBase = valorAConvetir;
    var unidadEnrada = "";
    /*if(idTipoMedida == 4 || idTipoMedida == 6)//Area o Longitud
    {
        console.log("estoy en IdTipoMedida 4,6");
        var unidadEntrada = $('select[name="comboLongitud"] option:selected').val().split("-");
        console.log("Unidad de entrada: ",unidadEntrada,unidadEntrada[1]);
        
        if(unidadEntrada[1] === "cm")
            valorUnidadBase = valorAConvetir / 100;
        else if(unidadEntrada[1] === "m")
            valorUnidadBase = valorAConvetir / 1;
        else if(unidadEntrada[1] === "pulg")
            valorUnidadBase = valorAConvetir / 39.37;
        else if(unidadEntrada[1] === "pies")
            valorUnidadBase = valorAConvetir / 3.281;
    }
    else if(idTipoMedida == 3)//Peso
    {
        console.log("estoy en IdTipoMedida 3");
        var unidadEntrada = $('select[name="comboPeso"] option:selected').val().split("-");
        console.log("Unidad de entrada: ",unidadEntrada,unidadEntrada[1]);
        
        if(unidadEntrada[1] === "gr")
            valorUnidadBase = valorAConvetir / 1000;
        else if(unidadEntrada[1] === "kg")
            valorUnidadBase = valorAConvetir / 1;
    }*/
    console.log(valorAConvetir,valorUnidadBase);
    return valorUnidadBase;
}

function getUnidadValuefromRef(ref) {
	svalue = 'long-m-1';
	if (ref == 2)
	   svalue = 'long-cm-100';
    else if(ref == 3)
	   svalue = 'long-pulg-39.37';
    else if(ref == 4)
	   svalue = 'long-pies-3.281';
    else if(ref == 5)
	   svalue = 'peso-gr-100';
    else if(ref == 6)
	   svalue = 'peso-kg-1';
  return svalue;
}

function loadEditarMaterial(data) {
    $("#nombre").val(data.Nombre);
    $("#descripcion").val(data.Descripcion);
    $("#tipoMedida").val(data.IdMedida);
    $("#clave").val(data.Clave);
	lblunidad = getUnidadLabel(data.Unidad);
	$("#tiposDinamicos").empty();
    
	try {
        var datos = $.parseJSON(data.Medida);
        $.each(datos, function(i, val) {
            var lbl = val.nombre;

            if (lbl == 'Alto') {
        	    lbl = 'Calibre';
        	    lblunidad = 'mm';
            }

            var inputD = "<div class='col-md-6'><label>"+ lbl +"("+ lblunidad +")</label><input id='"+ lblunidad +"' name='"+ val.nombre +"' type='text' value='"+ val.valor +"' class='form-control inputDinamico' required></input></div>";
            $("#tiposDinamicos").append(inputD);
        });
	    
        if ($("#tipoMedida").val() == 4 && datos.length == 2) {
		    var inputD = "<div class='col-md-6'><label>Calibre(mm)</label><input id='"+ lblunidad +"' name='Alto' type='text' value='' class='form-control inputDinamico' required></input></div>";
	        $("#tiposDinamicos").append(inputD);
	    }
	}
	catch { }

    $('.idCategoriaNM').append('<option selected value="'+ data.IdCategoria +'">'+ data.CategoriaNombre +'</option>');
    $("#accion").val(1);
    $("#idRegistro").val(data.IdMaterial);
    $('#nuevoMatModal').modal('show');
	muestraComboUnidad(data.IdMedida);
	
	if (data.IdMedida == 4 || data.IdMedida == 6)
		$("#comboLongitud").val(getUnidadValuefromRef(data.Unidad));
	else if (data.IdMedida == 3)
		$("#comboPeso").val(getUnidadValuefromRef(data.Unidad));

	if (data.IdMedida == 4) {
		$("#pesoespecificozona").show();
		$("#pesoespecifico").show();
        $("#lblpesoespecifico").show();
		$("#btncalularpeso").show();
		$("#pesopieza").val(data.Peso);
	}
	else if (data.IdMedida == 6) {
		$("#pesoespecificozona").show();
		$("#pesoespecifico").hide();
		$("#lblpesoespecifico").hide();
		$("#btncalularpeso").hide();
		$("#pesopieza").val(data.Peso);
	}
	else {
		$("#pesoespecificozona").hide();
		$("#pesopieza").val(0);
	}
    
	$("#pesoespecifico").val(data.PesoEspecifico);
    $("#formMat").validate().resetForm();
    $(".error").removeClass("error");
}

function eliminarMaterial(idMaterial) {
    //eliminacion en bd
    var datos = { "accion":'baja', "id":idMaterial };
    
    $.post("./pages/material/datos.php", datos, function(result) {
        $('#matTable').DataTable().ajax.reload();
        
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text("SE HA ELIMINADO EL MATERIAL.");
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
    if (tipo == 1)
        eliminarMaterial(id);
    else if (tipo == 2)
        eliminarMaterial_InventarioInicial(id);
}

function llenaMedidas() {
    var datos = { "accion":'getMedidas' };

    $("select[name='tipoMedida']").append($("<option>", {
        value: "",
        text: ""
    }));
    
    $.post("./pages/material/datos.php", datos, function(result) {
        $.each(result, function(i, val) {
            $("select[name='tipoMedida']").append($("<option>", {
                value: val.IdMedida,
                text: val.Nombre
            }));
        });
    }, "json");
}
//obtiene el json de acuerdo al id de la medida
function getJsonMedidas(idMedida) {
    $("#btn_guardarMat").prop("disabled", true);
    var datos ={ "accion":'getJsonMedidas', "idMedida":idMedida };
    $("#tiposDinamicos").empty();
	lblunidad = '';
	
	if (idMedida == 4 || idMedida == 6) {
	    if ($('#comboLongitud').val() == "long-m-1")
		    unidadref = 1;
	    if ($('#comboLongitud').val() == "long-cm-100")
		    unidadref = 2;
	    if ($('#comboLongitud').val() == "long-pulg-39.37")
		    unidadref = 3;
	    if ($('#comboLongitud').val() == "long-pies-3.281")
		    unidadref = 4;

        lblunidad = getUnidadLabel(unidadref);
	}
	else if (idMedida == 3) {
		if ($('#comboPeso').val() == "peso-gr-100")
		    unidadref = 5;
	    if ($('#comboPeso').val() == "peso-kg-1")
		    unidadref = 6;

        lblunidad = getUnidadLabel(unidadref);
	}
	
    $.post("./pages/material/datos.php", datos, function(result) {
        var datos = $.parseJSON(result.Metadato);
        $.each(datos, function(i, val) {
            var lbl = val.nombre;
		    
            if(lblunidad == '')
			    lblunidad = val.unidad;

            if(lbl == 'Alto') {
		        lbl = 'Calibre';
			    lblunidad = 'mm';
            }

            var inputD = "<div class='col-md-6'><label>"+ lbl +"("+ lblunidad +")</label><input id='"+ lblunidad +"' name='"+ val.nombre +"' type='text' value='' class='form-control inputDinamico' required></input></div>";
            $("#tiposDinamicos").append(inputD);
        });
        
        $("#btn_guardarMat").prop("disabled", false);
    }, "json");
}

function llenaCategorias() {
    $('.idCategoriaNM').select2( {
        placeholder: "Selecciona una opción",
        ajax: {
            url: './pages/material/datos.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    accion: 'autocompleteCategorias',
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