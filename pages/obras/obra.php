<?php
    set_include_path(get_include_path() . PATH_SEPARATOR .'../../phpseclib');
    include_once "Net/SSH2.php";
    include "../../config.php";
    include_once '../../clases/permisos.php';
    include_once '../../clases/usuario.php';
    $permisos = new Permisos();
    $usuario = new Usuario();
?>
<script src="pages/obras/obraScript.js" type="text/javascript"></script>
<link href="pages/obras/obraStyles.css" rel="stylesheet" type="text/css"/>

<h3>PROYECTOS</h3>
<div class="row">
    <div class="col-md-5">
    </div>
    <div class="col-md-5" align="right">
        <div style="margin-top: 10px">
            <label style="margin-right: 10px">Fecha:</label>
            <i class="fa fa-calendar"></i>
            <input type="text" id="fechasFiltroP">
        </div>
        <input type="hidden" id="fIniP" value="2023-01-01">
        <input type="hidden" id="fFinP" value="2024-12-31">
    </div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <?php if($permisos->acceso("2048", $usuario->obtenerPermisos($_SESSION['username']))): ?>
            <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalObra()"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
        <?php endif; ?>
    </div>
    <div class="col-md-12 table-responsive">
        <table id="obTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Terminado</th>
                    <th>Tipo de Proyecto</th>
                    <th>Cliente</th>
                    <th>F. Entrega</th>
                    <th>Dias Restantes</th>
                    <th class="alinearIzquierda">Presupuesto</th>
                    <th class="alinearIzquierda">Pendiente</th>
                    <th class="alinearIzquierda">Gastado</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?php
    include 'nuevaObra.php';
?>
<script type="text/javascript">
    var openRows = new Array();
    
    $( document ).ready( function() {
        var permisoAdministrar = <?php echo json_encode($permisos->acceso("2048", $usuario->obtenerPermisos($_SESSION['username']))); ?>;
        var permisoPresupuestos = <?php echo json_encode($permisos->acceso("2097152", $usuario->obtenerPermisos($_SESSION['username']))); ?>;

        inicializaFechasP(permisoAdministrar, permisoPresupuestos);
        loadDataTable(permisoAdministrar, permisoPresupuestos, $("#fIniP").val(), $("#fFinP").val());
        
        $('#obTable tbody').on('click', 'button', function () {
            var data = $("#obTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "editar":
                    loadEditarObra(data);
                break;
                case "eliminar":
                    $("#idRegistro").val(data.IdObra);
                    $("#warningModal").modal("show");
                break;
                case "gastos":
                    loadGastosObra($(this));
                break;
                case "materiales":
                    loadMateriales($(this));
                break;
                case "productos":
                    loadProductos($(this));
                break;
            }
        });
        
        $('#obTable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#obTable').DataTable().row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });

        function format ( d ) {
            // `d` is the original data object for the row
            var datos = "<div class='row detalles'>";
            datos += "<div class='col-md-3'><label class='control-label negritas'>Cliente</label><p>"+ d.Cliente +"</p></div>";
            datos += "<div class='col-md-3'><label class='control-label negritas'>Dirección</label><p>"+ d.Domicilio +"</p></div>";
            datos += "<div class='col-md-3'><label class='control-label negritas'>Descripción</label><p>"+ d.Descripcion +"</p></div>";
            datos += "</div>";
            return datos;
        }

        function loadGastosObra (btn) {
            var tr = btn.closest('tr');
            var row = $('#obTable').DataTable().row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                btn.find(".gastos").remove();
                btn.find(".materiales").remove();
                btn.find(".productos").remove();
            }
            else {
                closeOpenedRows($('#obTable').DataTable(), tr);
                row.child(verGastos(row.data())).show();
                tr.addClass('shown');
                // store current selection
                openRows.push(tr);
                btn.append("<i class='fa fa-level-down gastos' aria-hidden='true'></i>");
            }
        }

        function verGastos(rowData) {
            var div = $('<div/>', { class:'row detalles', id:rowData.IdObra });
            div.load("pages/obras/listadoGastos/listadoGastos.php");
            return div;
        }

        function closeOpenedRows(table, selectedRow) {
            $.each(openRows, function (index, openRow) {
                // not the selected row!
                if ($.data(selectedRow) !== $.data(openRow)) {
                    var rowToCollapse = table.row(openRow);
                    rowToCollapse.child.hide();
                    openRow.removeClass('shown');
                    // remove from list
                    var index = $.inArray(selectedRow, openRows);
                    openRows.splice(index, 1);
                }
            });
        }

        function loadMateriales(btn) {
            var tr = btn.closest('tr');
            var row = $('#obTable').DataTable().row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                btn.find(".materiales").remove();
                btn.find(".gastos").remove();
                btn.find(".productos").remove();
            }
            else {
                closeOpenedRows($('#obTable').DataTable(), tr);
                row.child( verMateriales(row.data())).show();
                tr.addClass('shown');
                // store current selection
                openRows.push(tr);
                btn.append("<i class='fa fa-level-down materiales' aria-hidden='true'></i>");
            }
        }

        function verMateriales(rowData) {
            var div = $('<div/>', { class:'row detalles', id:rowData.IdObra });
            div.load("pages/obras/listadoMateriales/listadoMateriales.php");
            return div;
        }

        $('#obTable tbody').on('click', 'input.terminar', function(event, state) {
            var data = $("#obTable").DataTable().row($(this).parents('tr')).data();
            
            var pregunta = "";

            if ($(this).prop('checked')) {
                pregunta = "¿Estás seguro de que deseas TERMINAR el proyecto?";
                terminarObra(data.IdObra, 1);
            }
            else {
                pregunta = "¿Estás seguro de que deseas ACTIVAR el proyecto?";
                terminarObra(data.IdObra, 0);
            }
        });

        function loadProductos(btn) {
            var tr = btn.closest('tr');
            var row = $('#obTable').DataTable().row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                btn.find(".gastos").remove();
                btn.find(".materiales").remove();
                btn.find(".productos").remove();
            }
            else {
                closeOpenedRows($('#obTable').DataTable(), tr);
                row.child( verProductos(row.data())).show();
                tr.addClass('shown');
                // store current selection
                openRows.push(tr);
                btn.append("<i class='fa fa-level-down productos' aria-hidden='true'></i>");
            }
        }

        function verProductos(rowData){
            var div = $('<div/>',{class: 'row detalles', id:rowData.IdObra});
            div.load("pages/obras/listadoProductos/listadoProductos.php");
            return div;
        }
    });

    function inicializaFechasP(permisoAdministrar, permisoPresupuestos) {
        $('#fechasFiltroP').daterangepicker( {
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

        function(start, end, label) {
            fIni = start.format('YYYY-MM-DD');
            fFin = end.format('YYYY-MM-DD');

            $("#fIniP").val(fIni);
            $("#fFinP").val(fFin);

            loadDataTable(permisoAdministrar, permisoPresupuestos, fIni, fFin);
        });

        $('#fechasFiltroP').val('');
    }
</script>