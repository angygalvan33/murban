<script src="pages/requisiciones/detalleReqAtendidas/detalleReqScript.js" type="text/javascript"></script>

<div class="col-md-12">
    <table id="detalleReqTable" class="table table-hover reqsDetalle">
        <thead class="encabezadoTabla">
            <tr>
                <th>Cantidad</th>
                <th>Unidad</th>
				<th>Piezas</th>
				<th>Cantidad Atendida</th>
                <th>Material</th>
                <th>Proyecto</th>
                <th>Solicita</th>
                <th>Estado</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaDetalleReqTable();
    });
</script>