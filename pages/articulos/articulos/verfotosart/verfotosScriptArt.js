function inicializaTablaverFotosArt() {
    $('#vertablafotosArt').DataTable({
        'processing': true,
        'serverSide': true,
        'ajax': {
            url: "pages/articulos/articulos/verfotosart/fotosData.php", //json datasource
            type: "post", //method, by default get
            error: function(code) { //error handling
                $(".vertablafotosArt-error").html("");
                $("#vertablafotosArt").append('<tbody class="vertablafotosArt-error"><tr><th colspan="3">No hay datos para mostrar</th></tr></tbody>');
                $("#vertablafotosArt_processing").css("display", "none");
            },
            data: function(d) {
                d.IdArticulo = $(".fotos").attr("id");
            }
        },
        'columns': [
            { 'data': "IdArticuloFoto", sortable: false, width: "10%", orderable: false, visible: false },
			{ orderable: false,  width: "20%",
			    mRender: function (data, type, row) {
                    var spath = '';
					if (row.Foto != null)
						spath = 'images/articulos/'+ row.Foto;
					else
						spath = 'images/fotoparte.png';
			        return "<img src='"+ spath +"' style='width:70px;height:70px'/>";
                }
			},
            { width: "30%", sortable: false,
                mRender: function (data, type, row) {
					if (row.Foto == row.FotoPrincipal)
                        var chk = "<input type='checkbox' checked class='verprincipal' value='1'>";
				    else
						var chk = "<input type='checkbox' class='verprincipal' value='0'>";
                    return chk;
                 }
            },
            { width: "30%", sortable: false,
                mRender: function (data, type, row) {
					var buttons = "<button type='button' id='principalFotoArt' style='margin-right:5px' class='btn btn-success btn-sm'><i class='fa fa-edit'></i>&nbsp;Principal</button>";
                    return buttons;
                 }
            },
			{ width: "30%", sortable: false,
                mRender: function (data, type, row) {
                    var buttonsdel = "<button type='button' id='eliminarverFotoArt' style='margin-right:5px' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return buttonsdel;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        }
    });
}

function resetValuesFotos() {
	$("#verfoto_articulo").attr("src", "images/fotoparte.png");
	$("#verartFotoNombre").val('');
}

function veraddFotoToArticulo(foto, IdArticulo) {
	$("#loading").modal("show");
    var datos = {};
	datos["accion"] = 'nuevafoto';
    datos["Foto"] = foto;
    datos["Principal"] = 0;
	datos["Id"] = IdArticulo;
    //console.log(IdArticulo);
    var rowCount = $('#vertablafotosArt tr').length;
	if (rowCount == 0)
		datos["Principal"] = 1;

	$.post("./pages/articulos/articulos/verfotosart/fotosdatos.php", datos, function(result) {
        $("#loading").modal("hide");

        switch (result["error"]) {
		    case 0:
		        $("#successModal .modal-body").text(result["result"]);
			    $("#successModal").modal("show");
                resetValuesFotos();
			    $('#vertablafotosArt').DataTable().ajax.reload();
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

function vereliminarFoto_Art(IdArticuloFoto) {
    var datos = { "accion":'eliminarfoto', "IdFoto":IdArticuloFoto };
    
    $.post("./pages/articulos/articulos/verfotosart/fotosdatos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#vertablafotosArt').DataTable().ajax.reload();
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

function setFotoPrincipal(Foto, IdArticulo) {
    var datos = { "accion":'principal', "Foto":Foto, "Id":IdArticulo };
    
    $.post("./pages/articulos/articulos/verfotosart/fotosdatos.php", datos, function(result) {
        switch (result["error"]) {
            case 0:
                $("#successModal .modal-body").text(result["result"]);
                $("#successModal").modal("show");
                $('#vertablafotosArt').DataTable().ajax.reload();
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

function vercambiarFotoArt() {
    var archivos = $("#verartFoto")[0].files;
    var formData = new FormData();
    
    if (archivos.length > 0) {
        formData.append("accion", "subeFoto");
        formData.append("artFoto", archivos[0]);
        
        $.ajax({
            type: 'POST',
            url: './pages/articulos/articulos/datos.php',
            data: formData,
            success: function(result) {
                if (result == false) {
                    $("#errorModal .modal-body").text("Error al cargar la foto. Asegúrese de seleccionar un archivo PNG / JPG");
                    $("#errorModal").modal("show");
                }
                else {
                    $("#verfoto_articulo").attr("src", "./images/articulos/"+ result);
					$("#verartFotoNombre").val(result);
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