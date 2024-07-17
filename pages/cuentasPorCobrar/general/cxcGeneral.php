<script src="pages/cuentasPorCobrar/general/cxcGeneralScript.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-12">
        <button id="descargacxcg" type="button" class="btn btn-success" >Exportar a Excel</button>
    </div>
</div>
<br>
<div class="col-md-12 table-responsive">
    <table id="cxcTable" class="table table-hover">
        <thead>
            <tr>
                <th>Proyecto</th>
                <th>Cliente</th>
                <th>Folio OC</th>
                <th>Folio Factura</th>
                <th>Fecha Factura</th>
                <th>Monto Factura(MXN)</th>
                <th>Dias crédito restante</th>
                <th>Restante</th>
				<th></th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        loadDataTableGeneral();

        $('#cxcTable').on('click', 'button', function () {
            var data = $("#cxcTable").DataTable().row($(this).parents('tr')).data();

            switch ($(this).attr("id")) {
                case "cobrar":
                    getAbonosAnticipo1(data.IdProyecto, data.FacturaValor, data.CobroRestante);
                    $("#IdProyecto").val(data.IdProyecto);
                    $("#pagarForm").validate().resetForm();
                    $("#pagarForm :input").removeClass('error');
                    $("input[type=radio][name=tpago][value='1']").prop("checked", true);
                    $('#fechaPago').val(moment().format("DD/MM/YYYY"));
                    $('#fechaPagoH').val(moment().format("DD/MM/YYYY"));
                    habilitaCantidad1("1");
                    $("#cantidad").val(data.CobroRestante);
                    $("#pagarModal").modal("show");
                break;
                case "facturar":
                    loadFacturacion(data,"facturar");
                    $('#facturarModal').modal('show');
                break;
                case "editar_factura":
                    loadFacturacion(data,"editar_factura");
                    $('#facturarModal').modal('show');
			    break;
            }
        });
		
		$("#descargacxcg").click( function() {
            Descargarcxcg();
        });
		
		function Descargarcxcg() {
			 window.location.href = "./excel/reportes/rcuentasporcobrar.php?tipo=1";
		}
    });
</script>