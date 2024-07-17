<script src="pages/compras/concentrado/historicoOC/historicoOCScript.js" type="text/javascript"></script>

<style type="text/css">
    .detalles {
        padding: 5px !important;
    }
</style>

<div class="row">
    <div class="col-md-12 table-responsive">
        <table id="historicooc_Table" class="table table-hover">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Fecha</th>
                    <th>Proveedor</th>
                    <th>Total OC</th>
                    <th>Descripción</th>
                    <th>Genera</th>
                    <th>Autoriza</th>
                    <th>PDF</th>
                    <th>Tipo</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
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
	
    $( document ).ready( function() {
        loadDataTableHOCs();
        
        $('#historicooc_Table').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#historicooc_Table').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#historicooc_Table').DataTable().row('.shown').length) {
                    $('.details-control', $('#historicooc_Table').DataTable().row('.shown').node()).click();
                }
                row.child(formatOCs(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    /* Formatting function forl row details - modify as you need */
    function formatOCs(rowData) {
        var divTipo = $('<div/>', {class:'tipo', id:"Ocs"});
        var divDetalles = $('<div/>', {class:'row detalles', id:rowData.IdOrdenCompra});
        divTipo.append(divDetalles);
        divDetalles.load("pages/compras/detalleOC/detalleOC.php");
        return divTipo;
    }
</script>