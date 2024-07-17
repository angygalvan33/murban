<script src="pages/cuentasPorPagar/pendientesPago/pendientesPagoScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="cxpPendientesPago" class="table table-hover">
        <thead>
            <tr>
                <th>Folio OC</th>
                <th>Proveedor</th>
                <th>Folio de factura</th>
                <th>Fecha de facturación</th>
                <th>Monto (MXN)</th>
                <th>Deuda (MXN)</th>
                <th>Días de crédito restantes</th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
        </tfoot>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        loadDataTablePendientesPago();
        
        $('#cxpPendientesPago').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#cxpPendientesPago').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#cxpPendientesPago').DataTable().row('.shown').length) {
                    $('.details-control', $('#cxpPendientesPago').DataTable().row('.shown').node()).click();
                }
                row.child(formatCP(row.data())).show();
                tr.addClass('shown');
            }
        });
        
        $('#cxpPendientesPago').on('click', 'button', function () {
            var data = $("#cxpPendientesPago").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "pPagar":
                    getAbonosAnticipo(data.IdOC, data.ValorFactura, data.Deuda);
                    $("#idOC").val(data.IdOC);
                    $("#pValorFactura").text(data.ValorFactura);
                    $("#pAnticipo").text(data.Anticipo);
                    $("#pDeuda").text(data.Deuda);
                    $("#pagarForm").validate().resetForm();
                    $("#pagarForm :input").removeClass('error');
                    $("input[type=radio][name=tpago][value='1']").prop("checked", true);
                    $('#fechaPago').val(moment().format("DD/MM/YYYY"));
                    $('#fechaPagoH').val(moment().format("DD/MM/YYYY"));
                    habilitaCantidad("1");
                    $("#metodoPago").val("");
                    $("#cantidad").val(data.Deuda);
                    $("#cantidadFact").val("");
                    $("#pagarModal").modal("show");
                break;
                case "pCancelarPago":
                    autorizar(data.IdOC, 0, data.IdProveedor, data.Deuda);
                    $('#cxpPendientesPago').DataTable().ajax.reload();
                break;
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatCP (rowData) {
        var divTipo = $('<div/>', { class:'tipo', id:"cuentasPorPagarPendientesPago" });
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdOC });
        divTipo.append(divDetalles);
        divDetalles.load("pages/compras/detalleOC/detalleOC.php");
        return divTipo;
    }
</script>