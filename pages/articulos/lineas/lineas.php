<link href="bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css"/>
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
<script src="pages/articulos/lineas/lineasScript.js" type="text/javascript"></script>

<div class="row">
    <div class="col-md-10"></div>
    <div class="col-md-2" style="margin-bottom: 10px !important">
        <button type="button" class="btn bg-navy btn-flat btn-block" onclick="openModalLin()"><i class="fa fa-plus"></i>&nbsp;Nueva</button>
    </div>
    <div class="col-md-12 table-responsive">
        <table id="linTable" class="table table-hover">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<?php
    include 'nuevaLinea.php';
?>
<script type="text/javascript">
    $( document ).ready(function() {
        loadDataTable();
        
        $('#linTable tbody').on('click', 'button', function () {
            var data = $("#linTable").DataTable().row($(this).parents('tr')).data();
            
            switch ($(this).attr("id")) {
                case "editar":
                    loadEditarLinea(data);
                break;
                case "eliminar":
                    $("#idRegistro").val(data.IdLinea);
                    $("#tipo").val(0);
                    $("#warningModal").modal("show");
                break;
            }
        });
    });
</script>