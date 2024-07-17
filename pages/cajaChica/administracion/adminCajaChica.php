<?php
    include_once 'administracion/nuevaCajaChica.php';
?>
<script src="pages/cajaChica/administracion/adminCajaChicaScript.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-10"></div>
    <?php if ($permisos->acceso("2147483648", $usuario->obtenerPermisos($_SESSION['username']))): ?>
        <div class="col-md-2" style="margin-bottom: 10px !important">
            <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalNC()"><i class="fa fa-plus"></i>&nbsp;Nueva</button>
        </div>
    <?php endif; ?>
</div>

<div class="col-md-12 table-responsive">
    <table id="adminCChTable" class="table table-hover">
        <thead>
            <tr>
                <th>Id</th>
                <th>Usuario</th>
                <th>Presupuesto disponible</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    var openRows = new Array();
    var openRowsDetalles = new Array();
    
    $( document ).ready(function() {
        loadDataTableAdministracion();

        $('#adminCChTable tbody').on('click', 'button', function () {
            var data = $("#adminCChTable").DataTable().row($(this).parents('tr')).data();

            switch ($(this).attr("id")) {
                case "editar":
                    loadEditarCajaChica(data);
                break;
                case "rembolsar":
                    $("#idRegistro").val(data.IdCajaChica);
                    $("#totalReembolsar").text(data.TotalReembolso);
                    loadReembolsar();
                break;
                case "edoAbrir":
                    $("#idRegistro").val(data.IdCajaChica);
                    $("#tipo").val(1);
                    $("#edoCajaChicaModal").modal("show");
                break;
                case "edoCerrar":
                    $("#idRegistro").val(data.IdCajaChica);
                    $("#tipo").val(0);
                    $("#edoCajaChicaModal").modal("show");
                break;
                case "rembolsarFacturas":
                    //abrir detalle de caja
                    loadFacturasReembolables($(this));
                break;
            }
        });
        //Add event listener for opening and closing details
        $('#adminCChTable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#adminCChTable').DataTable().row(tr);
            if (row.child.isShown()) {
                //This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                closeOpenedRows($('#adminCChTable').DataTable(), tr, openRowsDetalles);
                row.child(formatCortes(row.data())).show();
                tr.addClass('shown');
                // store current selection
                openRowsDetalles.push(tr);
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function formatCortes (rowData) {
        var div = $('<div/>', { class:'row detalles', id:rowData.IdCajaChica });
        div.load("pages/cajaChica/administracion/Reembolsos/reembolsos.php");
        return div;
    }
    
    function loadFacturasReembolables(btn) {
        var tr = btn.closest('tr');
        var row = $('#adminCChTable').DataTable().row(tr);
        if (row.child.isShown()) {
            //This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
            btn.find(".openFacturas").remove();
        }
        else {
            closeOpenedRows($('#adminCChTable').DataTable(),tr, openRows);
            row.child(rembolsoFacturasTable(row.data())).show();
            tr.addClass('shown');
            //store current selection
            openRows.push(tr);
            btn.append("<i class='fa fa-level-down openFacturas' aria-hidden='true'></i>");
        }
    }
    /*Formatting function forl row details - modify as you need*/
    function rembolsoFacturasTable (rowData) {
        var div = $('<div/>', { class:'row detalles', id:rowData.IdUsuario });
        div.load("pages/cajaChica/administracion/reembolsarFacturas/reembolsarFacturas.php");
        return div;
    }
    
    function closeOpenedRows(table, selectedRow, openRows) {
        $(".facturasDetail").html("<i class='fa fa-angle-double-left'></i>&nbsp;Reembolsar facturas");
        $.each(openRows, function (index, openRow) {
            //not the selected row!
            if ($.data(selectedRow) !== $.data(openRow)) {
                var rowToCollapse = table.row(openRow);
                rowToCollapse.child.hide();
                openRow.removeClass('shown');
                //remove from list
                var index = $.inArray(selectedRow, openRows);
                openRows.splice(index, 1);
            }
        });
    }
</script>