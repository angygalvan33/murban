<script src="pages/almacen/consultaconciliacion/consulta.js" type="text/javascript"></script>

<div class="well" style="display: block;">
    <form id="form_consulta" role="form" >
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Evento</label>
                            <br>
                            <select id="cevento" name="cevento" class="form-control cevento" required="" style="width:100% !important"></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="col-md-12 table-responsive">
    <table id="ajustesxEventoTabla" class="table table-hover">
        <thead>
            <tr>
                <th>Material</th>
                <th>Ajuste</th>
                <th>Nota</th>
                <th>Usuario</th>
                <th>Fecha</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready(function() {
        inicializaFechas();
        inicializaTablaAjustesxEvento(0);
        autoCompleteEventos();

        $("#cevento").change(function() {
            var dataP = $('#cevento').select2('data');
            if (dataP.length > 0) {
                evento = dataP[0].id;
                inicializaTablaAjustesxEvento(evento);
            }
        });

        function inicializaFechas() {
            $('#cfecha').datepicker( {
                "setDate": new Date(),
                "autoclose": true,
                "inmediateUpdates": true,
                "todayBtn": true,
                "todayHighlight": true,
            });
        }
    });
</script>