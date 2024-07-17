<script src="pages/materialesEnPrestamo/materialEnResguardo/materialEnResguardoScript.js" type="text/javascript"></script>

<div class="col-md-12 table-responsive">
    <table id="PersonalResguardoTabla" class="table table-hover">
        <thead>
            <tr>
                <th>Personal</th>
            </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaPersonalResguardoTable();
        
        $('#PersonalResguardoTabla').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = $('#PersonalResguardoTabla').DataTable().row(tr);
            
            if (row.child.isShown()) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                if ($('#PersonalResguardoTabla').DataTable().row('.shown').length) {
                    $('.details-control', $('#PersonalResguardoTabla').DataTable().row('.shown').node()).click();
                }
                row.child(formatPersonalPrestamo(row.data())).show();
                tr.addClass('shown');
            }
        });
    });
    
    function formatPersonalPrestamo (rowData) {
        var divDetalles = $('<div/>', { class:'row detalles', id:rowData.IdPersonal });
        divDetalles.load("pages/materialesEnPrestamo/materialEnResguardo/detalleMaterialResguardo/detalleMaterialResguardo.php");
        return divDetalles;
    }
</script>