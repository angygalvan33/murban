function autoCompleteProveedoresOCReq() {
    $('#proveedorOCReq').select2( {
        placeholder: "Selecciona una opción",
        allowClear: true,
    	ajax: {
            url: './pages/compras/autocompleteOC.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            selectOnClose: true,
            data: function (params) {
             	return {
               		nombreAutocomplete: 'proveedorReqEspecial',
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

function autoCompleteObrasEspecial() {
    $('.obraEspecial').select2( {
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

function autoCompleteUsuariosEspecial() {
    $('.solicitaEspecial').select2( {
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

function setComboUnidades(unidadMaterial, Repetido, medida, peso) {
	var unidad = unidadMaterial;
	
	if (Repetido != 0) {
		$("#unidadMat option").each( function() {
          	var unidadref = $(this).val();
		  	
		  	if (unidadref == unidad)
			  	$(this).css("display", "");
		  	else
			  	$(this).css("display", "none");
		});
	}
	else if (medida == 4 && unidadMaterial == 2) {
		$("#unidadMat option").each( function() {
          	var unidadref = $(this).val();
		  	
		  	if (unidadref == 2 || unidadref == 0)
			  	$(this).css("display", "");
		  	else if (unidadref == 6 && peso > 0)
			  	$(this).css("display", "");
		  	else
			  	$(this).css("display", "none");
		});
	}
	else if (medida == 4 && unidadMaterial == 1) {
		$("#unidadMat option").each( function() {
          	var unidadref = $(this).val();
		  	
		  	if (unidadref == 9 || unidadref == 0)
			  	$(this).css("display", "");
		  	else if (unidadref == 6 && peso > 0)
			  	$(this).css("display", "");
		  	else
			  	$(this).css("display", "none");
		});
	}
	else if (medida == 4 && unidadMaterial == 4) {
		$("#unidadMat option").each( function() {
          	var unidadref = $(this).val();
		  	
		  	if (unidadref == 10 || unidadref == 0)
			  	$(this).css("display", "");
		  	else if (unidadref == 6 && peso > 0)
			  	$(this).css("display", "");
		  	else
			  	$(this).css("display", "none");
		});
	}
	else {
		$("#unidadMat option").each( function() {
        	var unidadref = $(this).val();

			if (unidad > 0 && unidad < 5) {
		  		if (unidadref == unidad || unidadref == 0)
		      		$(this).css("display", "");
          		else
			  		$(this).css("display", "none");
			}
			else if (unidad == 0) {
				if (unidadref == 0)
				    $(this).css("display", "");
            	else
				 	$(this).css("display", "none");
			}
			else if (unidad == 5 || unidadref == 0) {
				if (unidadref == 5)
			    	$(this).css("display", "");
            	else
					$(this).css("display", "none");
			}
			else if (unidad == 6) {
				if (unidadref == 0 || unidadref == unidad)
			    	$(this).css("display", "");
            	else
					$(this).css("display", "none");
			}
			else if (unidad == 7) {
				if (unidadref == 0 || unidadref == 7)
			    	$(this).css("display", "");
            	else
					$(this).css("display", "none");
			}
			else if (unidad == 8) {
				if (unidadref == 0 || unidadref == 8)
			    	$(this).css("display", "");
            	else
					$(this).css("display", "none");
			}
      	});
	}
}

function getMaterialByIdEspecial(idMaterial) {
	var datos = { "accion":'getMaterialById', "idMaterial":idMaterial };
    
    $.post("./pages/requisiciones/datos.php", datos, function(result) {
		var unidad = result["result"][0]["Unidad"];
		var medida = result["result"][0]["IdMedida"];
        var largo = result["result"][0]["Largo"];
		var ancho = result["result"][0]["Ancho"];
		var alto = result["result"][0]["Alto"];
		var peso = result["result"][0]["Peso"];
		var dataO = $('#obraEspecial').select2('data');
        var dataS = $('#solicitaEspecial').select2('data');
		var cantidadRepetido = MaterialRepetido(dataO[0].id, idMaterial, dataS[0].id);
		
		if (cantidadRepetido["Cantidad"] !== 0) {
			unidad = cantidadRepetido["Unidad"];
		}
        
		setComboUnidades(unidad, cantidadRepetido["Cantidad"], medida, peso);

		if (unidad == 1 && medida == 4)
			$("#unidadMat").val(9);
		else if (unidad == 4 && medida == 4)
			$("#unidadMat").val(10);
		else
			$("#unidadMat").val(unidad);

		$("#unidadMatEspecial").val(unidad);
		$("#largoMatEspecial").val(largo);
		$("#anchoMatEspecial").val(ancho);
		$("#altoMatEspecial").val(alto);
		$("#pesoMatEspecial").val(peso);
		$("#medidaEspecial").val(medida);
    }, "json");
}

function autoCompleteMaterialesEspecial(idProveedor) {
    $('.materialEspecial').select2( {
        placeholder: "Selecciona una opción",
    	ajax: {
            url: './pages/requisiciones/autocompleteOC.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
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

function inicializaMaterialesByProveedorEspecial() {
    $('#matsEspecial').DataTable( {
        'lengthMenu': [ [10,15, 25, -1], [10,15, 25, "Todos"] ],
        'data': [],
        'columns': [
            { 'data': "Cantidad", sortable: false, width: "5%", orderable: false },
            { 'data': "Unidad", sortable: false, width: "5%", orderable: true },
            { 'data': "Nombre", sortable: false, width: "25%", orderable: true },
            { 'data': "Piezas", sortable: false, width: "5%", orderable: true },
            { 'data': "Obra", sortable: false, width: "20%", orderable: true },
            { 'data': "Solicita", sortable: false, width: "15%", orderable: true },
            { 'data': "FechaReq", sortable: false, width: "15%", orderable: true },
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

function getCantidadReal(Cant, UnitMat, UnitSelected, Medida, Factor) {
	var CantFinal = {};
	CantFinal["Cantidad"] = Cant;
	CantFinal["Piezas"] = Cant;
	
	if (UnitMat == 1 && Medida == 6) {
		if (UnitMat == UnitSelected) {
			aux = Cant;
			aCant = Math.ceil(aux / Factor);
            CantFinal["Cantidad"] = aux;
	        CantFinal["Piezas"] = aCant;
		}
		else if (UnitSelected == 2) {
			aux = Cant / 100;
			aCant = Math.ceil(aux / Factor);
            CantFinal["Cantidad"] = aux.toFixed(2);
	        CantFinal["Piezas"] = aCant;
		}
		else if (UnitSelected == 3) {
			aux = Cant / 39.37;
			aCant = Math.ceil(aux / Factor);
            CantFinal["Cantidad"] = aux.toFixed(2);
	        CantFinal["Piezas"] = aCant;
		}
		else if (UnitSelected == 4) {
			aux = Cant / 3.281;
			aCant = Math.ceil(aux / Factor);
            CantFinal["Cantidad"] = aux.toFixed(2);
	        CantFinal["Piezas"] = aCant;
	    }
	}
	else if (UnitMat == 2 && Medida == 6) {
		if (UnitMat == UnitSelected) {
			aux = Cant;
			aCant = Math.ceil(aux / Factor);
            CantFinal["Cantidad"] = aux;
	        CantFinal["Piezas"] = aCant;
		}
		else if (UnitSelected == 1) {
			aux = Cant * 100.00;
			aCant = Math.ceil(aux / Factor);
            CantFinal["Cantidad"] = aux.toFixed(2);
	        CantFinal["Piezas"] = aCant;
		}
		else if (UnitSelected == 3) {
			aux = Cant * 2.54;
			aCant = Math.ceil(aux / Factor);
            CantFinal["Cantidad"] = aux.toFixed(2);
	        CantFinal["Piezas"] = aCant;
		}
		else if (UnitSelected == 4) {
			aux = Cant * 30.48;
			aCant = Math.ceil(aux / Factor);
            CantFinal["Cantidad"] = aux.toFixed(2);
	        CantFinal["Piezas"] = aCant;
		}
	}
	else if (UnitSelected == 9 && Medida == 4) {
		aux = Cant;
		aCant = Math.ceil(aux / Factor);
        CantFinal["Cantidad"] = aux;
	    CantFinal["Piezas"] = aCant;
	}
	else if (UnitSelected == 10 && Medida == 4) {
		aux = Cant;
		aCant = Math.ceil(aux / Factor);
        CantFinal["Cantidad"] = aux;
	    CantFinal["Piezas"] = aCant;
	}
	else if (UnitSelected == 6 && Medida == 3) {
		aux = Cant;
		aCant = Math.ceil(aux / 1);
        CantFinal["Cantidad"] = aux;
	    CantFinal["Piezas"] = aCant;
	}
	else if (UnitSelected == 6 && Medida == 4) {
		aux = Cant;
		aCant = Math.ceil(aux / Factor);
        CantFinal["Cantidad"] = aux;
	    CantFinal["Piezas"] = aCant;
	}
	else if (UnitSelected == 6 && Medida == 6) {
		aux = Cant;
		aCant = Math.ceil(aux / Factor);
        CantFinal["Cantidad"] = aux;
	    CantFinal["Piezas"] = aCant;
	}
	
	return CantFinal;
}

function getCantidadConverted(Cant, UnitFrom, UnitTo) {
	//all to mts
	CantFinal = Cant;
	if (UnitFrom == 2)
		CantFinal = Cant / 100;
	else if (UnitFrom == 3)
		CantFinal = Cant / 39.37;
	else if (UnitFrom == 4)
		CantFinal = Cant / 3.281;
	return CantFinal;
}

function agregaDetalleOC_Especial() {
    if ($("#formRequisicion").valid()) {
        var dataM = $('#materialEspecial').select2('data');
        var dataO = $('#obraEspecial').select2('data');
        var dataS = $('#solicitaEspecial').select2('data');
		var acant = $("#cantidadEspecial").val().replace(/\,/g, '');
		var amedida = $('#medidaEspecial').val();
		var aunidad = $('#unidadMatEspecial').val();
		var alargo = $('#largoMatEspecial').val();
		var aancho = $('#anchoMatEspecial').val();
		var aunidadselected = $('#unidadMat').val();
		var aunidadtext = $('#unidadMat option:selected').text();
		var apeso = $('#pesoMatEspecial').val();
		var fecharequi = $('#fecharequi').val();
        var afactor = alargo;

		if (amedida == 4) {
			afactor = alargo * aancho;
		}
		else if (amedida == 3) {
			afactor = apeso;
		}

		if (aunidadselected == 6)
			afactor = apeso;
		
		var cantfinal = getCantidadReal(acant, aunidad, aunidadselected, amedida, afactor);
        var nuevo = {};
        nuevo["IdObra"] = dataO[0].id;
        nuevo["Obra"] = dataO[0].text;
        nuevo["Cantidad"] = acant;
        nuevo["IdMaterial"] = dataM[0].id;
        nuevo["Unidad"] = aunidadtext;
        nuevo["Nombre"] = dataM[0].text;
		nuevo["Piezas"] = cantfinal["Piezas"];
		nuevo["Solicita"] = dataS[0].text;
        nuevo["IdSolicita"] = dataS[0].id;
		nuevo["Medida"] = amedida;
		nuevo["UnitMat"] = aunidad;
		nuevo["UnitMatSelected"] = aunidadselected;
		nuevo["LargoMat"] = alargo;
		nuevo['FechaReq'] = fecharequi;
        /**checar cuando actualiza el mismo renglon**/
        var cantidadRepetido = actualizarMaterialPorObraRepetido(nuevo["IdObra"], nuevo["IdMaterial"], nuevo["IdSolicita"]);
        
        if (cantidadRepetido["Cantidad"] !== 0) {
			var _cantRepetido = cantidadRepetido["Cantidad"];
			nuevo["Cantidad"] = (parseFloat(nuevo["Cantidad"]) + parseFloat(_cantRepetido)).toString();
			cantfinal = getCantidadReal(nuevo["Cantidad"], aunidad, aunidadselected, amedida, afactor);
			nuevo["Piezas"] = cantfinal["Piezas"];
		}
        
		$('#matsEspecial').DataTable().row.add(nuevo).draw();
        $("#materialEspecial").empty();
        $("#cantidadEspecial").val("");
    }
}

function MaterialRepetido(idObra, idMaterial, idSolicita) {
    var existe = false;
    var actualRow = null;
	var cantidadActual = {};
	cantidadActual["Cantidad"] = 0;
	cantidadActual["Unidad"] = 0;
	cantidadActual["Piezas"] = 0;
    
    $('#matsEspecial').DataTable().rows().every( function (rowIdx, tableLoop, rowLoop) {
        d = this.data();
        existe = false;
        //si es un material de catálogo de materiales
        if (idMaterial !== -1 && d.IdObra === idObra && d.IdMaterial === idMaterial && d.IdSolicita === idSolicita)
        	existe = true;
        
        if (existe) {
            cantidadActual["Cantidad"] = d.Cantidad;
			cantidadActual["Unidad"] = d.UnitMatSelected;
			cantidadActual["Piezas"] = d.Piezas;
            actualRow = $('#matsEspecial').DataTable().row(rowIdx);
        }
    });

    return cantidadActual;
}
//si ya existe un material de una obra con el mismo usuario, actualizar la cantidad
function actualizarMaterialPorObraRepetido(idObra, idMaterial, idSolicita) {
    var existe = false;
    var actualRow = null;
	var cantidadActual = {};
	cantidadActual["Cantidad"] = 0;
	cantidadActual["Unidad"] = 0;
	cantidadActual["Piezas"] = 0;
    
    $('#matsEspecial').DataTable().rows().every(function (rowIdx, tableLoop, rowLoop) {
        d = this.data();
        existe = false;
        //si es un material de catálogo de materiales
        if (idMaterial !== -1 && d.IdObra === idObra && d.IdMaterial === idMaterial && d.IdSolicita === idSolicita)
        	existe = true;
        
        if (existe) {
            cantidadActual["Cantidad"] = d.Cantidad;
			cantidadActual["Unidad"] = d.UnitMatSelected;
			cantidadActual["Piezas"] = d.Piezas;
            actualRow = $('#matsEspecial').DataTable().row(rowIdx);
        }
    });
    
    if (actualRow !== null)
        actualRow.remove().draw();
    
    return cantidadActual;
}

function eliminarOCEspecial(actualRowEspecial) {
    actualRowEspecial.remove().draw();
    $('#matsEspecial').DataTable().data().count();
}
//tipo 1 normal, 2 especial
function mostrarOcultarNuevaRequisiscion(accion, tipo) {
    var req = "", reqEspecial = "", reqEditar = "", IdRequisicion = "", editarDetalle = "";
    req = $("#nuevaRequisicion");
    reqEspecial = $("#nuevaRequisicionEspecial");
    reqEditar = $("#nuevaRequisicion3");
    reqEditarDetalle = $("#nuevaRequisicion4");
    //requisicion
    if (tipo === 1) {
        //ocultar
        if (accion === 0)
            req.slideUp("slow");
        //mostrar
        else {
            $("#BtnGuardar").prop("disabled", false);
            resetValues();
            req.slideDown("slow");
            reqEspecial.slideUp("slow");
            reqEditar.slideUp("slow");
            reqEditarDetalle.slideUp("slow");
        }
    }
    else if (tipo === 2) {
        //ocultar
        if (accion === 0)
            reqEspecial.slideUp("slow");
        //mostrar
        else {
            $("#BtnGuardar2").prop("disabled", false);
            resetValues();
            reqEspecial.slideDown("slow");
            req.slideUp("slow");
            reqEditar.slideUp("slow");
            reqEditarDetalle.slideUp("slow");
        }
    }
    else if (tipo === 3) {
        //ocultar
        if (accion === 0)
            reqEditar.slideUp("slow");
        //mostrar
        else {
            $("#BtnGuardar").prop("disabled", false);
            resetValues3();
            req.slideUp("slow");
            reqEspecial.slideUp("slow");
            reqEditar.slideDown("slow");
            reqEditarDetalle.slideUp("slow");
        }
    }
    else {
    	if (accion === 0)
            reqEditarDetalle.slideUp("slow");
        //mostrar
        else {
            $("#BtnGuardar").prop("disabled", false);
            resetValues4();
            req.slideUp("slow");
            reqEspecial.slideUp("slow");
            reqEditar.slideUp("slow");
            reqEditarDetalle.slideDown("slow");
        }
    }
}

function resetValues() {
    $('#matsEspecial').DataTable().clear().draw();
    $("#materialEspecial").empty();
    $("#obraEspecial").empty();
    $("#cantidadEspecial").val("");
   	$('#fecharequi').val('');
    $("#formRequisicion").validate().resetForm();
    $("#formRequisicion :input").removeClass('error');
    $("#descripcionRequisicion").val("");
}

function cancelarNuevaRequisicion(tipo) {
    if (tipo === 1)
        resetValues();
    else
        resetValuesEspecial();
    
    mostrarOcultarNuevaRequisiscion(0, tipo);
}

function guardarRequisicion(observaciones) {
    $("#BtnGuardar").prop("disabled", true);
    //obteniendo datos
    var datosDetalleCompra = [];
    var formData = new FormData();
    var cont = 1;
    
    $('#matsEspecial').DataTable().rows().every( function () {
    	var d = this.data();
        var nuevo = {};
        nuevo["IdMaterial"] = d.IdMaterial;
        nuevo["Material"] = d.Nombre;
        nuevo["Cantidad"] = parseFloat(d.Cantidad.replace(/\,/g, ''));
		nuevo["Piezas"] = parseFloat(d.Piezas);
		nuevo["Unidad"] = d.Unidad;
        nuevo["IdObra"] = d.IdObra;
        nuevo["IdSolicita"] = d.IdSolicita;
        nuevo["FechaReq"] = d.FechaReq;
        datosDetalleCompra.push(nuevo);
        formData.append(cont +":_IdObra", d.IdObra);
        formData.append(cont +":_IdMaterial", d.IdMaterial);
        formData.append(cont +":_Material", d.Nombre);
        formData.append(cont +":_Cantidad", d.Cantidad.replace(/\,/g, ''));
		formData.append(cont +":_Piezas", d.Piezas);
		formData.append(cont +":_Unidad", d.Unidad);
        formData.append(cont +":_IdSolicita", d.IdSolicita);
        formData.append(cont +":_FechaReq", d.FechaReq);
        cont++;
    });
    
    if (datosDetalleCompra.length > 0) {
        formData.append("Observaciones", observaciones);
        formData.append("accion", "guardarRequisicionManual");

        $.ajax( {
            type: 'POST',
            url: './pages/requisiciones/datos.php',
            data: formData,
            success: function(result) {
                result = result.slice(1, result.length - 1);
                var resultado = result.split(",");
                var error = resultado[0].split(":")[1];
                var msj = resultado[1].split(":")[1];
                msj = msj.slice(1, msj.length - 1);
                
                switch (error) {
                    case "0":
                        $("#successModal .modal-body").text(msj);
                        $("#successModal").modal("show");
                        //reinicio de valores
                        resetValues();
                        mostrarOcultarNuevaRequisiscion(0, 1);
                        $('#requisicionesTable').DataTable().ajax.reload();
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
        $("#BtnGuardar").prop("disabled", false);
        $("#avisosModal .modal-title").text("REQUISICIÓN MANUAL");
        $("#avisosModal .modal-body").text("REQUISICIÓN VACÍA");
        $("#avisosModal").modal("show");
    }
}