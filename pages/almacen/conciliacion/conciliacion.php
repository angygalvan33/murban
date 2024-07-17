<script src="pages/almacen/conciliacion/conciliacion.js" type="text/javascript"></script>

<div class="well" style="display:block;">
    <form id="form_conciliacion" role="form">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Ubicación</label>
                            <br>
                            <select id="ubicacionm" name="ubicacionm" class="form-control ubicacionm" required="" style="width:100% !important"></select>
                        </div>
                        <div class="col-md-2">
                            <br>
                            <button id="descargaconc" type="button" class="btn btn-success" disabled="disabled">PDF</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="col-md-12 table-responsive">
    <table id="concilicacionTabla" class="table table-hover">
        <thead>
            <tr>
                <th>Material</th>
                <th>Proyecto</th>
                <th>Cantidad</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        inicializaTablaConciliacion(0);
        autoCompleteUbicacionMaterial();

        $("#ubicacionm").change(function() {
            var dataP = $('#ubicacionm').select2('data');
            if (dataP.length > 0) {
                idUbicacion = dataP[0].id;
                inicializaTablaConciliacion(idUbicacion);
                $("#descargaconc").prop("disabled", false);
            }
            else
                $("#descargaconc").prop("disabled", true);
        });
    });

    $( "#descargaconc" ).click(function() {
        Descargarconc();
    });
    
    function Descargarconc() {
        var ubicacion = $("#ubicacionm").val();
        window.open('html2pdf-master/reportes/reporteConcInventario.php?ubicacion='+ ubicacion, '_blank');
    }
</script>