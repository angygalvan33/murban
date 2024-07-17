<?php
	set_include_path(get_include_path(). PATH_SEPARATOR .'../../phpseclib');
	include_once "Net/SSH2.php";
	include "../../config.php";
	include_once '../../clases/permisos.php';
	include_once '../../clases/usuario.php';
	$permisos = new Permisos();
	$usuario = new Usuario();
?>

<script src="pages/compras/ocompraScript_Especial.js" type="text/javascript"></script>
<script src="pages/compras/ocompraScript_Req.js" type="text/javascript"></script>
<link href="pages/compras/ocompraStyles.css" rel="stylesheet" type="text/css"/>

<h3>ÓRDENES DE COMPRA</h3>
<?php if ($permisos->acceso("8192", $usuario->obtenerPermisos($_SESSION['username']))): ?>
	<div class="row">
		<div class="col-md-10"></div>
		<div class="col-md-2" style="margin-bottom: 10px !important">
			<button type="button" class="btn bg-navy btn-flat btn-block" onclick="mostrarOcultarNuevaOC_Especial(1)"><i class="fa fa-plus"></i>&nbsp;Nueva OC</button>
		</div>
		<div class="col-md-12">
		<?php
			include_once 'nuevaOC_Especial.php';
			include_once 'nuevaOCReq.php';
		?>
		</div>
	</div>
<?php endif; ?>
<div class="panel-group" id="accordion">
	<div class="panel box box-default">
		<a data-toggle="collapse" data-parent="#accordion" href="#requisicionesProyPanel">
			<div class="box-header with-border headers">
				<h4 class="box-title tituloPanel">
					REQUISICIONES PIEZAS/KILOS
				</h4>
			</div>
		</a>
		<div id="requisicionesProyPanel" class="panel-collapse collapse">
			<div class="box-body">
				<div id="loadRequisicionesProy">
					<?php
						include 'requisproy/requisproy.php';
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="panel box box-primary">
		<a data-toggle="collapse" data-parent="#accordion" href="#requisicionesEspecialesPanel">
			<div class="box-header with-border headers">
				<h4 class="box-title tituloPanel">
					REQUISICIONES ESPECIALES
				</h4>
			</div>
		</a>
		<div id="requisicionesEspecialesPanel" class="panel-collapse collapse">
			<div class="box-body">
				<div id="loadRequisicionesEspeciales">
					<?php
						include 'requisesp/requisesp.php';
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="panel box box-success">
		<a data-toggle="collapse" data-parent="#accordion" href="#preOCPanel">
			<div class="box-header with-border headers">
				<h4 class="box-title tituloPanel">
					Pre O.C.
				</h4>
			</div>
		</a>
		<div id="preOCPanel" class="panel-collapse collapse">
			<div class="box-body">
				<div id="loadPreOC">
					<?php
						include 'requispreoc/requispreoc.php';
					?>
				</div>
			</div>
		</div>
	</div>
	<?php if ($permisos->acceso("16384", $usuario->obtenerPermisos($_SESSION['username']))): ?>
		<div class="panel box box-info">
			<a data-toggle="collapse" data-parent="#accordion" href="#autorizacionPanel">
				<div class="box-header with-border headers">
					<h4 class="box-title tituloPanel">
						EN ESPERA DE AUTORIZACIÓN
					</h4>
				</div>
			</a>
			<div id="autorizacionPanel" class="panel-collapse collapse">
				<div class="box-body">
					<div id="loadAutorizacion">
					<?php
						include 'sinAutorizacion/sinAutorizacion.php';
					?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($permisos->acceso("65536", $usuario->obtenerPermisos($_SESSION['username']))): ?>
		<div class="panel box box-warning">
			<a data-toggle="collapse" data-parent="#accordion" href="#recepcionPanel">
				<div class="box-header with-border headers">
					<h4 class="box-title tituloPanel">
						RECEPCIÓN Y CANCELACIÓN
					</h4>
				</div>
			</a>
			<div id="recepcionPanel" class="panel-collapse collapse">
				<div class="box-body">
					<div id="loadAutorizacion">
					<?php
						include 'emitidas/emitidas.php';
					?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($permisos->acceso("524288", $usuario->obtenerPermisos($_SESSION['username']))): ?>
		<div class="panel box box-danger">
			<a data-toggle="collapse" data-parent="#accordion" href="#esperaFacturacionPanel">
				<div class="box-header with-border headers">
					<h4 class="box-title tituloPanel">
						EN ESPERA DE FACTURACIÓN
					</h4>
				</div>
			</a>
			<div id="esperaFacturacionPanel" class="panel-collapse collapse">
				<div class="box-body">
					<div id="loadEsperaFacturacion">
						<?php
							include 'esperaFacturacion/esperaFacturacion.php';
						?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($permisos->acceso("1048576", $usuario->obtenerPermisos($_SESSION['username']))): ?>
		<div class="panel box box-default">
			<a data-toggle="collapse" data-parent="#accordion" href="#canceladasPanel">
				<div class="box-header with-border headers">
					<h4 class="box-title tituloPanel">
						CANCELADAS
					</h4>
				</div>
			</a>
			<div id="canceladasPanel" class="panel-collapse collapse">
				<div class="box-body">
					<div id="loadCanceladas">
					<?php
						include 'canceladas/canceladas.php';
					?>
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
<div id="cancelarModal" class="modal modal-warning fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Cancelación</h4>
			</div>
			<div class="modal-body">
				<p>¿Estás seguro que deseas el cancelar la Orden de Compra?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Abortar</button>
				<button id="cancelarOrden" type="button" class="btn btn-outline" data-dismiss="modal" onclick="cancelarOC($('#idOrdenCompra').val())">Cancelar Orden</button>
			</div>
		</div>
	</div>
</div>
<!--De Requisiciones-->
<div id="asignarComprarModal" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Material Requisición</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="tipoReqAsignar">
				<input type="hidden" id="tipoAsignar">
				<input type="hidden" id="existenciaStockAsignar">
				<input type="hidden" id="idReqDetalleAsignar">
				<input type="hidden" id="idMaterialAsignar">
				<input type="hidden" id="idProyectoAsignar">
				<input type="hidden" id="idProveedorAsignar">
				<form id="formAsignarMaterial" role="form">
					<label> Material:</label>
					<span id="materialAsignarActual"></span>
					<br>
					<label id="disponibleAsignar"></label>
					<span id="cantidadDisponible"></span>
					<br>
					<label id="unidadAsignar"></label>
					<span id="unidadRequerida"></span>
					<hr style="margin:0px 0px 10px 0px">
					<div class="input-group" style="width: 100%">
						<label>Cantidad</label>
						<input type="text" id="cantidadAsignar" name="cantidadAsignar" class="form-control" required>
						<label id="errorcantidadAsignar" style="display:none; color:red">La cantidad sobrepasa la existente.</label>
					</div>
					<div class="form-group">
						<label>Fecha del proveedor</label>
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
						<input data-date-format="yyyy-mm-dd" type="text" class="form-control pull-right" id="fechaProv" required=""/>
						<input type="hidden" id="fprov" value="-1">
					</div>
					<div class="input-group" style="width: 100%">
						<label>Proyecto</label>
						<input type="text" id="obraAsignar" name="obraAsignar" class="form-control" disabled="disabled">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="asignarMaterial($('#tipoReqAsignar').val(), $('#tipoAsignar').val(), $('#idReqDetalleAsignar').val(), $('#idMaterialAsignar').val(), $('#idProyectoAsignar').val(), $('#cantidadAsignar').val(), $('#idProveedorAsignar').val(), $('#fechaProv').val())">Aceptar</button>
			</div>
		</div>
	</div>
</div>
<?php
	include 'requisicionesxkilo/compramodalxkilo.php';
?>
<!--Opciones de Requisicion-->
<div id="RequisicionOpciones" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Opciones de Orden de Compra</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="octoprint">
				<input type="hidden" id="foliotoprint">
				<form id="formopcionesOc" role="form">
					<div class="input-group" style="width: 100%">
						<label>M&eacute;todo de Pago</label>
						<select id="metodopago" class="form-control">
							<option value="1">PUE:Pago en una sola exhibici&oacute;n</option>
							<option value="2">PPD:Pago parcial diferido</option>
						</select>
					</div>
					<div class="input-group" style="width: 100%">
						<label>Uso de CFDI</label>
						<select id="usocfdi" class="form-control">
							<option value="1">G01:Adquisici&oacute;n de Mercanc&iacute;as</option>
							<option value="2">G03:Gastos en General</option>
						</select>
					</div>
					<div class="input-group" style="width: 100%">
						<label>Forma de Pago</label>
						<select id="formapago" class="form-control">
							<option value="1">01:Efectivo</option>
							<option value="2">03:Transferencia Electr&oacute;nica</option>
							<option value="3">04:Tarjeta de Cr&eacute;dito</option>
							<option value="4">28:Tarjeta de D&eacute;bito</option>
							<option value="5">99:Por definir</option>
						</select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="gotoPrintOc($('#octoprint').val(), $('#foliotoprint').val(), $('#metodopago').val(), $('#usocfdi').val(), $('#formapago').val())">Descargar</button>
			</div>
		</div>
	</div>
</div>
<div id="cancelarReqModal" class="modal modal-warning fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Cancelar</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="idDetalleReq">
				<label>Motivo de cancelación:</label>
				<br>
				<textarea id="motivoCancelacionReq" style="resize: none; width:100%; color:black;" rows="3"></textarea>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-outline" data-dismiss="modal" onclick="cancelarReq($('#idDetalleReq').val(), $('#motivoCancelacionReq').val())">Aceptar</button>
			</div>
		</div>
	</div>
</div>
<div id="regresarReqModal" class="modal modal-warning fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Más tarde</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="idDetalleReq">
				<input type="hidden" id="idAtendida">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-outline" data-dismiss="modal" onclick="regresarReq($('#idDetalleReq').val(), $('#idAtendida').val())">Aceptar</button>
			</div>
		</div>
	</div>
</div>
<div id="ProveedorOpciones" class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Opciones de Proveedor</h4>
			</div>
			<div class="modal-body">
				<input type="hidden" id="idOccp">
				<form id="formopcionesProv" role="form">
					<div class="input-group" style="width: 100%">
						<label>Proveedor</label>
						<select id="provnvo" name="provnvo" class="form-control provnvo" required=""></select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary" onclick="cambiaProv($('#idOccp').val(), $('#provnvo').val())">Cambiar</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function gotoPrintOc(idref, folio, metodopago, usocfdi, formapago) {
		alink = 'html2pdf-master/reportes/reporteOC.php?id='+ idref +'&folio="'+ folio +'"&mpago='+ metodopago +'&ucfdi='+ usocfdi +'&fpago='+ formapago;
		window.open(alink, '_blank');
	}

	function showopciones(idref, folio) {
		$("#octoprint").val(idref);
		$("#foliotoprint").val(folio);
		$("#RequisicionOpciones").modal();
	}

	function cambiaProv(idOccp, provnvo) {
		cambiaNvoProv(idOccp, provnvo);
		$("#ProveedorOpciones").modal('hide');
	}
	
	function changeProv(idOC) {
		$("#idOccp").val(idOC);
		$("#ProveedorOpciones").modal();
	}
	
	$( document ).ready(function() {
		mostrarOcultarNuevaOC_Especial(0);
		mostrarOcultarNuevaOCReq(0);
		inicializaFechas();
		autoCompleteProveedoresCambio();

		$(".facturaEspecial").css("display", "none");

		$("#tipoOCEspecial").val(1);
		//reload datatables al abrir paneles
		$("#requisicionesPanel").on("show.bs.collapse", function() {
			//resetRequisiciones(0);
			$('#mostrarTodo').iCheck('check');
			$("#provSeleccionados").prop("disabled", true);
			$("#provSeleccionados").empty();
			$("#solicitaNuevaOC").prop("disabled", true);
			$('#requisicionesTable').DataTable().ajax.reload();
			$('#requisicionesTablexkilo').DataTable().ajax.reload();
			cancelarOCReq();
		});

		$("#requisicionesEspecialesPanel").on("show.bs.collapse", function() {
			//resetRequisiciones(1);
			$('#mostrarTodoEspecial').iCheck('check');
			$("#provSeleccionadosEspecial").prop("disabled", true);
			$("#provSeleccionadosEspecial").empty();
			$("#solicitaNuevaOCEspecial").prop("disabled", true);
			$('#requisicionesEspecialesTable').DataTable().ajax.reload();
			cancelar_Especial();
			idsDetalleReqEspecial = [];
		});

		$("#autorizacionPanel").on("show.bs.collapse", function() {
			$('#sinAutTable').DataTable().ajax.reload();
		});

		$("#recepcionPanel").on("show.bs.collapse", function() {
			$('#emitidasTable').DataTable().ajax.reload();
		});

		$("#esperaFacturacionPanel").on("show.bs.collapse", function() {
			$('#esperaFacturacionTable').DataTable().ajax.reload();
		});

		$("#canceladasPanel").on("show.bs.collapse", function() {
			$('#canceladasTable').DataTable().ajax.reload();
		});
		//al cerrar
		$("#requisicionesPanel").on("hidden.bs.collapse", function() {
			$('#requisicionesTable').DataTable().ajax.reload();
			$('#requisicionesTablexkilo').DataTable().ajax.reload();
		});

		$("#requisicionesEspecialesPanel").on("hidden.bs.collapse", function() {
			$('#requisicionesEspecialesTable').DataTable().ajax.reload();
		});

		$("#autorizacionPanel").on("hidden.bs.collapse", function() {
			$('#sinAutTable').DataTable().ajax.reload();
		});

		$("#autorizacionPanel").on("hide.bs.collapse", function() {
			$('#sinAutTable').DataTable().ajax.reload();
		});

		$("#recepcionPanel").on("hidden.bs.collapse", function() {
			$('#emitidasTable').DataTable().ajax.reload();
		});

		$("#recepcionPanel").on("hide.bs.collapse", function() {
			$('#emitidasTable').DataTable().ajax.reload();
		});

		$("#esperaFacturacionPanel").on("hiden.bs.collapse", function() {
			$('#esperaFacturacionTable').DataTable().ajax.reload();
		});

		$("#esperaFacturacionPanel").on("hide.bs.collapse", function() {
			$('#esperaFacturacionTable').DataTable().ajax.reload();
		});

		$("#canceladasPanel").on("hiden.bs.collapse", function() {
			$('#canceladasTable').DataTable().ajax.reload();
		});

		$("#canceladasPanel").on("hide.bs.collapse", function() {
			$('#canceladasTable').DataTable().ajax.reload();
		});

		function inicializaFechas() {
			$('#fechaProv').datepicker( {
				"setDate": new Date(),
				"autoclose": true,
				"inmediateUpdates": true,
				"todayBtn": true,
				"todayHighlight": true,
			}).datepicker("setStartDate", "0");
		}
	});
</script>