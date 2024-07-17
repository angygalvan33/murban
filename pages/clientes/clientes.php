<script src="pages/clientes/clientesScript.js" type="text/javascript"></script>
<link href="pages/clientes/clientesStyles.css" rel="stylesheet" type="text/css"/>

<h3>CLIENTES</h3>
<div class="row">
    <div class="col-md-10"> </div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalCliente()"><i class="fa fa-plus"></i>&nbsp;Nuevo</button>
    </div>
    <div class="col-md-12 table-responsive">
        <table id="clienteTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Dirección</th>
                    <th>RFC</th>
                    <th>Tipo de persona</th>
                    <th>Días de crédito</th>
                    <th>Límite de crédito</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?php
    include 'nuevoCliente.php';
?>
<script type="text/javascript">
    $( document ).ready(function() {
        loadClientesDataTable();
        
        $('#clienteTable tbody').on('click', 'button', function () {
            var data = $("#clienteTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "editar":
                    loadEditarCliente(data);
                break;
                case "eliminar":
                    $("#idRegistro").val(data.IdCliente);
                    $("#warningModal").modal("show");
                break;
            }
        });
        //Add event listener for opening and closing details
        $('#clienteTable tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#clienteTable').DataTable().row(tr);

            if (row.child.isShown()) {
                //This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                //Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    /*Formatting function forl row details - modify as you need*/
    function format ( d ) {
        var html = "";
        var datos = $.parseJSON(d.Contactos);

        $.each(datos, function(i, val) {
            html += "<div class='row detalles'>";
            html += "<div class='col-md-4'><label class='control-label negritas'>Nombre</label>"+ val.Nombre +"</div>";
            html += "<div class='col-md-4'><label class='control-label negritas'>Email</label>"+ val.Email +"</div>";
            html += "<div class='col-md-4'><label class='control-label negritas'>Teléfono</label>"+ val.Telefono +"</div>";
            html += "</div>";
        });
        return html;
    }
</script>