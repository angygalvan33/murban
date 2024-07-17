<script src="pages/proveedores/listadoMaterialesKg/listadoMaterialesScriptKg.js" type="text/javascript"></script>
<link href="pages/proveedores/listadoMaterialesKg/listadoMaterialesStylesKg.css" rel="stylesheet" type="text/css"/>
<style type="text/css">
    .ancho {
        width:100% !important;
    }
</style>

<div style="margin:10px 10px">
    <fieldset class="scheduler-border" style="margin-bottom: 0px !important;">
        <legend class="scheduler-border">Precio</legend>
        <form id="formMbPKg">
            <div class="row">
                <div class="col-md-4">
                    <input type="hidden" id="idProveedorPMKg">
					<div class="form-group ancho">
                        <label>PrecioxKilo:</label>
                        <select class="form-control ancho nprecioxkilo" id="nprecioxkilo" name="nprecioxkilo" required></select>
                    </div>
                </div>
                <div class="col-md-1">
                    <br>
                    <div class="form-group">
                        <button type="button" class="btn btn-bitbucket btn-sm" onclick="shownuevoPrecioxKilo(0)"><i class='fa fa-plus'></i>&nbsp;Nuevo</button>
                    </div>
                </div>
				<div class="col-md-1">
                    <br>
                    <div class="form-group">
                        <button type="button" class="btn btn-bitbucket btn-sm" style="background-color:green" onclick="shownuevoPrecioxKilo(1)"><i class='fa fa-edit'></i>&nbsp;Editar</button>
                    </div>
                </div>
            </div>
        </form>
    </fieldset>
    <br>
	<fieldset class="scheduler-border" style="margin-bottom: 0px !important;">
        <legend class="scheduler-border">Material de Proveedor</legend>
        <form id="formMbPKg">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group ancho">
                        <label>Material:</label>
                        <select class="form-control ancho nmaterial" id="nmaterialKg" name="nmaterialKg" required></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <br>
                    <div class="form-group">
                        <button type="button" class="btn btn-bitbucket btn-sm" onclick="guardaNuevoPrecioxKilo()"><i class='fa fa-plus'></i>&nbsp;Agregar</button>
                    </div>
                </div>
            </div>
        </form>
    </fieldset>
    <br>
    <table id="listadoMaterialesTableKg" class="table table-hover">
        <thead class="encabezadoTablaKg">
            <tr>
                <th>Material</th>
                <th>Medida</th>
                <th>Precio</th>
                <th>Atendió</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<!--eliminar material de proveedor modal-->
<div id="eliminarMatModalKg" class="modal modal-warning fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Eliminación</h4>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro que deseas eliminar el registro?</p>
                <input type="hidden" id="idProveedorMKg">
                <input type="hidden" id="idMaterialMKg">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cerrar</button>
                <button id="eliminarRegistroKg" type="button" class="btn btn-outline" data-dismiss="modal" onclick="eliminarMaterialKg($('#idProveedorMKg').val(), $('#idMaterialMKg').val())">Eliminar</button>
            </div>
        </div>
    </div>
</div>
<?php
    include './precioMaterialModalKg.php';
	include './editprecioMaterialModalKg.php';
?>
<script type="text/javascript">
    $( document ).ready( function() {
        getValorDolar();
        loadListadoMaterialesDataTableKg();

        $('.nmaterial').select2( {
            ajax: {
                url: './pages/proveedores/listadoMaterialesKg/autocompleteMaterialesKg.php',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
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
		
		$('.nprecioxkilo').select2( {
            ajax: {
                url: './pages/proveedores/listadoMaterialesKg/autocompletepreciosKg.php',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, //search term
				        IdProveedor: $(".detalles").attr("id")
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                }
            }
        });
		
        $('.ncotizadorKg').select2( {
            tags: true,
            ajax: {
                url: './pages/proveedores/listadoMaterialesKg/autocompleteCotizadoresKg.php',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        searchTerm: params.term, //search term
                        IdProveedor: $(".detalles").attr("id")
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                }
            }
        });

        $('#listadoMaterialesTableKg tbody').on('click', 'button', function () {
            var data = $("#listadoMaterialesTableKg").DataTable().row( $(this).parents('tr') ).data();
            
            switch ($(this).attr("id")) {
                case "editarPrecioKg":
                    $("#precioMaterialModalKg #idProveedorKg").val($(".detalles").attr("id"));
                    $("#precioMaterialModalKg #idMaterialKg").val(data.IdMaterial);
                    openPrecioMaterialModalKg(data);
                break;
                case "historicoKg":
                    $("#historicoMaterialesModalKg #idProveedorKg").val($(".detalles").attr("id"));
                    $("#historicoMaterialesModalKg #idMaterialKg").val(data.IdMaterial);
                    $("#historicoMaterialesModalKg #nombreMaterialKg").html("<p><strong>Material:&nbsp;</strong>"+ data.Material +"</p>");
                    $("#historicoMaterialesModalKg #nombreProveedorKg").html("<p><strong>Proveedor:&nbsp;</strong>"+ data.Proveedor +"</p>");
                    historicoReloadKg();
                    $('#historicoMaterialesModalKg').modal('show');
                break;
                case "eliminarPreciosKg":
                    $("#idProveedorMKg").val(data.IdProveedor);
                    $("#idMaterialMKg").val(data.IdMaterial);
                    $("#eliminarMatModalKg").modal("show");
                break;
            }
        });
    });
    
	function shownuevoPrecioxKilo(editar) {
		if (editar != 1)
			openPrecioMaterialModalKg(null);
	    else {
		    var idprov = $('.detalles').attr('id');
            var dataPrecio= $('.nprecioxkilo').select2('data');
            var datos = { "accion":'getPrecioById', "idPrecioxkilo":dataPrecio[0].id, "IdProveedor":idprov };
            var precio = 0;
            var preciodolares = 0;
            var moneda = "";
            var iva = 0;
            
            $.post("./pages/proveedores/listadoMaterialesKg/datosKg.php", datos, function(result) {
			    if (result["error"] == 0) {
                    precio = result["result"][0].Precio;
				    moneda = result["result"][0].Moneda;
				    iva = result["result"][0].Iva;
				    if (precio == 0 && preciodolares == 0)
			            alert('Ha occurrido un error intertno por favor intente de nuevo');
		            else
			            openEditPrecioMaterialModalKg(dataPrecio[0].id, idprov, precio, iva, moneda);
                }
            }, "json");
		}
	}
	
    function guardaNuevoPrecioxKilo() {
        var dataMateriales = $('.nmaterial').select2('data');
		var dataPrecio = $('.nprecioxkilo').select2('data');
        guardarPrecioMaterialKg(dataMateriales[0].id, $('.detalles').attr('id'), dataPrecio[0].id);
    }
    
    function historicoReloadKg() {
        $('#historicoTableKg').DataTable().ajax.reload();
    }
</script>