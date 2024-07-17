<script src="./pages/compras/concentrado/gastosxcat/consultasGastosxCat.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-4">
        <label for="categoriasCtrl">Categorias</label>
        <br>
        <select class="form-control multis" id="categoriasCtrl" name="categoriasCtrl" style="width:100% !important">
        </select>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Fecha:</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="fechasFiltro">
            </div>
        </div>
        <input type="hidden" id="fIni" value="-1">
        <input type="hidden" id="fFin" value="-1">
        <br>
    </div>
    <div class="col-md-2">
        <div id="dTotal"></div>
    </div>
    <div class="col-md-2">
        <br>
        <button id="descargagxc" type="button" class="btn btn-success" disabled="disabled">Exportar a Excel</button>
    </div>
</div>
<div class="col-md-12 table-responsive">
    <table id="gastoxcatConsultaTable" class="table table-hover">
        <thead>
            <tr>
                <th>Folio OC</th>
                <th>Proyecto</th>
                <th>Material</th>
                <th>Cantidad</th>
                <th>SubTotal</th>
                <th>Fecha</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    var tipo = 0;
    var idCat = 0;

    $(document).ready(function() {
        inicializaFechas();
        llenaCategorias();
        loadDataTableGastosxCat(tipo, 0, null, null);

        $('.multis').on("change", function (e) {
            if ($("#categoriasCtrl").val().length === 0)
                $("#descargagxc").prop("disabled", true);
            else
                $("#descargagxc").prop("disabled", false);
        });

        $("#categoriasCtrl").change(function() {
            var dataP = $('#categoriasCtrl').select2('data');
            tipo = 1;
            if (dataP.length > 0) {
                idCat = dataP[0].id;
                inicializaFechas();
                loadDataTableGastosxCat(tipo, idCat, null, null);
            }
            else {
                idCat = 0;
                inicializaFechas();
                loadDataTableGastosxCat(tipo, idCat, null, null);
            }
        });
    });

    function inicializaFechas() {
        $('#fechasFiltro').daterangepicker( {
            opens: 'left',
            "locale": {
                "applyLabel": "Aplicar",
                "cancelLabel": "Cancelar",
                "fromLabel": "DE",
                "toLabel": "HASTA",
                "customRangeLabel": "Custom",
                "daysOfWeek": [
                    "Dom",
                    "Lun",
                    "Mar",
                    "Mié",
                    "Jue",
                    "Vie",
                    "Sáb"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
                "firstDay": 1
            }
        },
          
        function (start, end, label) {
            var fIni = start.format('YYYY-MM-DD');
            var fFin = end.format('YYYY-MM-DD');

            $("#fIni").val(fIni);
            $("#fFin").val(fFin);

            loadDataTableGastosxCat(tipo, idCat, fIni, fFin);
        });

        $('#fechasFiltro').val('');
    }
    
    function llenaCategorias() {
        $('.multis').select2( {
            placeholder: "Selecciona una opción",
            ajax: {
                url: './pages/compras/concentrado/gastosxcat/datosGastosxCat.php',
                type: "post",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        accion: 'autocompleteCategorias',
                        searchTerm: params.term //search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                }
            }
        });
    }

    $( "#descargagxc" ).click(function() {
        Descargargxc();
    });
    
    function Descargargxc() {
        var caT = $("#categoriasCtrl").val();
        var fIni = $("#fIni").val();
        var fFin = $("#fFin").val();
        
        window.location.href = "./excel/reportes/reporteGastosxCategoria.php?categoria="+ caT +"&fIni="+ fIni +"&fFin="+ fFin;
    }
</script>