<script src="pages/obras/listadoMateriales/listadoMaterialesScript.js" type="text/javascript"></script>

<style type="text/css">
    .ancho {
        width:100% !important;
    }
</style>

<div style="margin:10px 10px">
    <h4>Materiales</h4>
    <table id="materialesTable" class="table table-hover">
        <thead class="encabezadoTabla">
            <tr>
                <th>Cantidad</th>
                <th>Material</th>
                <th>Precio</th>
                <th>Folio OC</th>
                <th>Fecha</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        loadMaterialesDataTable();
    });
</script>