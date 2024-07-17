function autoCompleteProveedoresOCReq3() {
    $('#proveedorOCReq3').select2( {
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

function autoCompleteObrasEspecial3() {
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

function autoCompleteUsuariosEspecial3() {
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

function setComboUnidades3(unidadMaterial, Repetido, medida, peso) {
	var unidad = unidadMaterial;
	
	if (Repetido != 0) {
		$("#unidadMat3 option").each(function() {
          	var unidadref = $(this).val();
		  	
		  	if (unidadref == unidad)
			  	$(this).css("display", "");
		  	else
			  	$(this).css("display", "none");
		});
	}
	else if (medida == 4 && unidadMaterial == 2) {
		$("#unidadMat3 option").each( function() {
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
		$("#unidadMat3 option").each( function() {
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
		$("#unidadMat3 option").each( function() {
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
		$("#unidadMat3 option").each( function() {
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

/*function getMaterialByIdEspecial3(idMaterial) {
	var datos = { "accion":'getMaterialById', "idMaterial":idMaterial };
    
    $.post("./pages/requisiciones/datos.php", datos, function(result) {
		var unidad = result["result"][0]["Unidad"];
		var medida = result["result"][0]["IdMedida"];
        var largo = result["result"][0]["Largo"];
		var ancho = result["result"][0]["Ancho"];
		var alto = result["result"][0]["Alto"];
		var peso = result["result"][0]["Peso"];
		var dataO = $('#obraEspecial3').select2('data');
        var dataS = $('#solicitaEspecial3').select2('data');
		var cantidadRepetido = MaterialRepetido3(dataO[0].id, idMaterial, dataS[0].id);
		
		if (cantidadRepetido["Cantidad"] !== 0) {
			unidad = cantidadRepetido["Unidad"];
		}
        
		setComboUnidades3(unidad, cantidadRepetido["Cantidad"], medida, peso);

		if (unidad == 1 && medida == 4)
			$("#unidadMat3").val(9);
		else if (unidad == 4 && medida == 4)
			$("#unidadMat3").val(10);
		else
			$("#unidadMat3").val(unidad);

		$("#unidadMatEspecial3").val(unidad);
		$("#largoMatEspecial3").val(largo);
		$("#anchoMatEspecial3").val(ancho);
		$("#altoMatEspecial3").val(alto);
		$("#pesoMatEspecial3").val(peso);
		$("#medidaEspecial3").val(medida);
    }, "json");
}*/

function autoCompleteMaterialesEspecial3(idProveedor) {
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

function inicializaMaterialesByProveedorEspecial3() {
    $('#matsEspecial3').DataTable( {
        'lengthMenu': [ [10,15, 25, -1], [10,15, 25, "Todos"] ],
        'data': [],
        'columns': [
            { 'data': "Cantidad", sortable: false, width: "10%", orderable: false },
            { 'data': "Unidad", sortable: false, width: "20%", orderable: true },
            { 'data': "Nombre", sortable: false, width: "20%", orderable: true },
            { 'data': "Piezas", sortable: false, width: "10%", orderable: true },
            { 'data': "Obra", sortable: false, width: "15%", orderable: true },
            { 'data': "Solicita", sortable: false, width: "15%", orderable: true },
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

function getCantidadReal3(Cant, UnitMat, UnitSelected, Medida, Factor) {
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
		aCant = Math.ceil(aux / Factor);
        CantFinal["Cantidad"] = aux;
	    CantFinal["Piezas"] = Cant;
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

function getCantidadConverted3(Cant, UnitFrom, UnitTo) {
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

function agregaDetalleOC_Especial3() {
    if ($("#formRequisicion3").valid()) {
        var dataM = $('#materialEspecial3').select2('data');
        var dataO = $('#obraEspecial3').select2('data');
        var dataS = $('#solicitaEspecial3').select2('data');
		var acant = $("#cantidadEspecial3").val().replace(/\,/g, '');
		var amedida = $('#medidaEspecial3').val();
		var aunidad = $('#unidadMatEspecial3').val();
		var alargo = $('#largoMatEspecial3').val();
		var aancho = $('#anchoMatEspecial3').val();
		var aunidadselected = $('#unidadMat3').val();
		var aunidadtext = $('#unidadMat3 option:selected').text();
		var apeso = $('#pesoMatEspecial3').val();
        var afactor = alargo;

		if (amedida == 4) {
			afactor = alargo * aancho;
		}
		else if (amedida == 3) {
			afactor = apeso;
		}
		if (aunidadselected == 6)
			afactor = apeso;
		
		var cantfinal = getCantidadReal3(acant, aunidad, aunidadselected, amedida, afactor);
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
        /**checar cuando actualiza el mismo renglon**/
        var cantidadRepetido = actualizarMaterialPorObraRepetido(nuevo["IdObra"], nuevo["IdMaterial"], nuevo["IdSolicita"]);
        
        if (cantidadRepetido["Cantidad"] !== 0) {
			var _cantRepetido = cantidadRepetido["Cantidad"];
			nuevo["Cantidad"] = (parseFloat(nuevo["Cantidad"]) + parseFloat(_cantRepetido)).toString();
			cantfinal = getCantidadReal(nuevo["Cantidad"], aunidad, aunidadselected, amedida, afactor);
			nuevo["Piezas"] = cantfinal["Piezas"];
		}
        
		$('#matsEspecial3').DataTable().row.add(nuevo).draw();
        $("#materialEspecial3").empty();
        $("#cantidadEspecial3").val("");
    }
}

function MaterialRepetido3(idObra, idMaterial, idSolicita) {
    var existe = false;
    var actualRow = null;
	var cantidadActual = {};
	cantidadActual["Cantidad"] = 0;
	cantidadActual["Unidad"] = 0;
	cantidadActual["Piezas"] = 0;
    
    $('#matsEspecial3').DataTable().rows().every(function (rowIdx, tableLoop, rowLoop) {
        d = this.data();
        existe = false;
        //si es un material de catálogo de materiales
        if (idMaterial !== -1 && d.IdObra === idObra && d.IdMaterial === idMaterial && d.IdSolicita === idSolicita)
        	existe = true;
        
        if (existe) {
            cantidadActual["Cantidad"] = d.Cantidad;
			cantidadActual["Unidad"] = d.UnitMatSelected;
			cantidadActual["Piezas"] = d.Piezas;
            actualRow = $('#matsEspecial3').DataTable().row(rowIdx);
        }
    });

    return cantidadActual;
}
//si ya existe un material de una obra con el mismo usuario, actualizar la cantidad
function actualizarMaterialPorObraRepetido3(idObra, idMaterial, idSolicita) {
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
    
    if (actualRow !== null)
        actualRow.remove().draw();
    
    return cantidadActual;
}

function eliminarOCEspecial3(actualRowEspecial) {
    actualRowEspecial.remove().draw();
    $('#matsEspecial3').DataTable().data().count();
}

function resetValues3() {
    $('#matsEspecial3').DataTable().clear().draw();
    $("#materialEspecial3").empty();
    $("#obraEspecial3").empty();
    $("#cantidadEspecial3").val("");
    $("#formRequisicion3").validate().resetForm();
    $("#formRequisicion3 :input").removeClass('error');
    $("#descripcionRequisicion3").val("");
}

function cancelarNuevaRequisicion3(tipo) {
    if (tipo === 3 || tipo === 4)
        resetValues3();
    mostrarOcultarNuevaRequisiscion(0, tipo);
}

function guardarRequisicion3(observaciones, idRequisicion) {
    $("#BtnGuardar").prop("disabled", true);
    //obteniendo datos
    var datosDetalleCompra = [];
    var formData = new FormData();
    var cont = 1;
    
    $('#matsEspecial3').DataTable().rows().every(function () {
        var d = this.data();
        var nuevo = {};
        nuevo["IdMaterial"] = d.IdMaterial;
        nuevo["Material"] = d.Nombre;
        nuevo["Cantidad"] = parseFloat(d.Cantidad.replace(/\,/g, ''));
		nuevo["Piezas"] = parseFloat(d.Piezas);
		nuevo["Unidad"] = d.Unidad;
        nuevo["IdObra"] = d.IdObra;
        nuevo["IdSolicita"] = d.IdSolicita;
        datosDetalleCompra.push(nuevo);
        formData.append(cont +":_IdObra", d.IdObra);
        formData.append(cont +":_IdMaterial", d.IdMaterial);
        formData.append(cont +":_Material", d.Nombre);
        formData.append(cont +":_Cantidad", d.Cantidad.replace(/\,/g, ''));
		formData.append(cont +":_Piezas", d.Piezas);
		formData.append(cont +":_Unidad", d.Unidad);
        formData.append(cont +":_IdSolicita", d.IdSolicita);
        cont++;
    });
    
    if (datosDetalleCompra.length > 0) {
        formData.append("Observaciones", observaciones);
        formData.append("IdRequisicion", idRequisicion);
        formData.append("accion", "actualizarRequisicionManual");
        
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
                        resetValues3();
                        mostrarOcultarNuevaRequisiscion(0, 3);
                        $('#requisicionesRevisionTable').DataTable().ajax.reload();
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

function cancelarNuevaRequisicion4(tipo) {
    if (tipo === 4)
        resetValues4();
    mostrarOcultarNuevaRequisiscion(0, tipo);
}

function resetValues4() {
    $("#materialEspecial4").empty();
    $("#obraEspecial4").empty();
    $("#cantidadEspecial4").val("");
    $("#unidadMat4").val("");
    $("#formRequisicion4").validate().resetForm();
    $("#formRequisicion4 :input").removeClass('error');
    $("#descripcionRequisicion4").val("");
}

function getMaterialByIdEspecial4(idMaterial) {
	var datos ={ "accion":'getMaterialById', "idMaterial":idMaterial };
    
    $.post("./pages/requisiciones/datos.php", datos, function(result) {
		var unidad = result["result"][0]["Unidad"];
		var medida = result["result"][0]["IdMedida"];
        var largo = result["result"][0]["Largo"];
		var ancho = result["result"][0]["Ancho"];
		var alto = result["result"][0]["Alto"];
		var peso = result["result"][0]["Peso"];
		var dataO = $('#obraEspecial4').select2('data');
        var dataS = $('#solicitaEspecial4').select2('data');
		var cantidadRepetido = 0;
        
		setComboUnidades4(unidad, cantidadRepetido, medida, peso);

		if (unidad == 1 && medida == 4)
			$("#unidadMat4").val(9);
		else if (unidad == 4 && medida == 4)
			$("#unidadMat4").val(10);
		else
			$("#unidadMat4").val(unidad);

		$("#unidadMatEspecial4").val(unidad);
		$("#largoMatEspecial4").val(largo);
		$("#anchoMatEspecial4").val(ancho);
		$("#altoMatEspecial4").val(alto);
		$("#pesoMatEspecial4").val(peso);
		$("#medidaEspecial4").val(medida);
    }, "json");
}

function setComboUnidades4(unidadMaterial, Repetido, medida, peso) {
	var unidad = unidadMaterial;
	
	if (Repetido != 0) {
		$("#unidadMat4 option").each( function() {
          	var unidadref = $(this).val();
		  	
		  	if(unidadref == unidad)
			  	$(this).css("display", "");
		  	else
			  	$(this).css("display", "none");
		});
	}
	else if (medida == 4 && unidadMaterial == 2) {
		$("#unidadMat4 option").each( function() {
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
		$("#unidadMat4 option").each( function() {
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
		$("#unidadMat4 option").each( function() {
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
		$("#unidadMat4 option").each( function() {
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

function MaterialRepetido4() {
	var cantidadActual = {};
	cantidadActual["Cantidad"] = 0;
	cantidadActual["Unidad"] = 0;
	cantidadActual["Piezas"] = 0;
    return cantidadActual;
}

function autoCompleteObrasEspecial4() {
    $('#obraEspecial4').select2( {
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

function autoCompleteUsuariosEspecial4() {
    $('#solicitaEspecial4').select2( {
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

function autoCompleteMaterialesEspecial4(idProveedor) {
    $('#materialEspecial4').select2( {
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

function llenarDetalleRequisicionEditar(idRequisicionDetalle) {
	var datos = { "accion":'getMaterialByIdDetalle', "IdRequisicionDetalle":idRequisicionDetalle };
    
    $.post("./pages/requisiciones/datos.php", datos, function(result) {
		var idproyecto = result[0]["IdObra"];
		var proyecto = result[0]["Proyecto"];
        var idmaterial = result[0]["IdMaterial"];
		var material = result[0]["Material"];
        var cantidad = result[0]["CantidadSolicitada"];
		var unidad = result[0]["Unidad"];
		var idusuariosolicita = result[0]["IdUsuarioSolicita"];
		var solicita = result[0]["Solicita"];

		$("#obraEspecial4").append('<option selected value="'+ idproyecto +'">'+ proyecto +'</option>');
		$("#materialEspecial4").append('<option selected value="'+ idmaterial +'">'+ material +'</option>');
		$("#solicitaEspecial4").append('<option selected value="'+ idusuariosolicita +'">'+ solicita +'</option>');
		$("#cantidadEspecial4").val(cantidad);
		$("#unidadMat4 option").each( function () {
	        if ($(this).html() == unidad) {
	            $(this).attr("selected", "selected");
	        return;
	        }
		});
    }, "json");
}

function guardarRequisicion4(idRequisicionDetalle) {
	$("#BtnGuardar").prop("disabled", true);
    
    var formData = new FormData();
    var dataO = $('#obraEspecial4').select2('data');
    var dataM = $('#materialEspecial4').select2('data');
    var dataC = $('#cantidadEspecial4').val().replace(/\,/g, '');;
    var dataU = $('#unidadMat4 option:selected').text();
    formData.append("IdRequisicionDetalle",idRequisicionDetalle);
    formData.append("IdProyecto",dataO[0].id);
    formData.append("Proyecto",dataO[0].text);
    formData.append("IdMaterial",dataM[0].id);
    formData.append("Material",dataM[0].text);
    formData.append("Cantidad",dataC);
	formData.append("Unidad",dataU);
    formData.append("accion","guardarDetalleRequisicion");
    
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
                    resetValues4();
                    mostrarOcultarNuevaRequisiscion(0, 4);
                    $('#detalleReqTable').DataTable().ajax.reload();
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