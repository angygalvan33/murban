<script src="pages/compras/requispreoc/detallePreOc/listadoMaterialesScript.js" type="text/javascript"></script>

<div class="col-md-12">
    <div class="table table-responsive">
        <table id="materialesPreOCTable" class="table table-hover">
            <thead class="encabezadoTabla">
                <tr>
                    <th>Cantidad PreOC</th>
					<th>Material</th>
                    <th>Precio Unitario</th>
                    <th>Fecha de cotización</th>
                    <th>SubTotal</th>
                    <th>Selección</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        loadDataTableMaterialesPreOC();

        $('#materialesPreOCTable').on('click', 'button', function () {
            var data = $("#materialesPreOCTable").DataTable().row($(this).parents('tr')).data();

            switch ($(this).attr("id")) {
                case "req_regresar":
                    $("#idDetalleReq").val(data.IdRequisicionDetalle);
                    $("#idAtendida").val(data.IdRequisicionAtendida);
                    $("#regresarReqModal").modal("show");
                break;
            }
        });

        $('#materialesPreOCTable tbody').on('click', 'input.seleccionada', function(event, state) {
            var data = $("#materialesPreOCTable").DataTable().row($(this).parents('tr')).data();
            var valor = 0;
            //console.log($(this).is(":checked"));
            if ($(this).is(":checked"))
                valor = 1;
            else
                valor = 0;
            seleccionarMaterial(data.IdRequisicionAtendida, valor);
        });
    });
</script>