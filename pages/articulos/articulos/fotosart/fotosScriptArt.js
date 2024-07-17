function inicializaTablaFotosArt() {
    $('#tablafotosArt').DataTable( {
        'destroy': true,
        'lengthMenu': [ [10,15, 25, -1], [10,15, 25, "Todos"] ],
        'data': [],
        'columns': [
			{ orderable: false, width: "20%",
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
					if (row.Principal == 0)
                        var buttons = "<input type='checkbox' class='principal' value='0'>";
				    else
						var buttons = "<input type='checkbox' checked class='principal' value='1'>";
                    return buttons;
                }
            },
            { width: "30%", sortable: false,
                mRender: function (data, type, row) {
                    var buttons = "<button type='button' id='eliminarFotoArt' style='margin-right:5px' class='btn btn-danger btn-sm'><i class='fa fa-trash'></i>&nbsp;Eliminar</button>";
                    return buttons;
                }
            }
        ],
        'language': {
            "url": "bower_components/datatables.net-bs/Spanish.json"
        },
        'footerCallback': function (row, data, start, end, display) { }
    });
}

function addFotoToArticulo(foto) {
    var nuevo = {};
    nuevo["Foto"] = foto;
    nuevo["Principal"] = 0;
    $('#tablafotosArt').DataTable().row.add(nuevo).draw();
    $("#foto_articulo").attr("src","images/fotoparte.png");
	$("#artFotoNombre").val('');
}

function eliminarFoto_Art(actualRow) {
    actualRow.remove().draw();
}

function cambiarFotoArt() {
    var archivos = $("#artFoto")[0].files;
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
                    $("#errorModal .modal-body").text("Error al cargar la foto. Asegúrese de seleccionar un archivo PNG/JPG");
                    $("#errorModal").modal("show");
                }
                else {
                    $("#foto_articulo").attr("src", "./images/articulos/"+ result);
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