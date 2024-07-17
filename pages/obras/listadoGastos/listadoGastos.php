<script src="pages/obras/listadoGastos/listadoGastosScript.js" type="text/javascript"></script>
<style type="text/css">
    .ancho {
        width:100% !important;
    }
</style>

<div style="margin:10px 10px">
    <h4>Gastos de Proyecto</h4>
    <table id="gastosObraTable" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Material</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Estado</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        loadGastosObraDataTable();
    });
</script>