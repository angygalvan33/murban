<script src="pages/articulos/articulos/fotosart/fotosScriptArt.js" type="text/javascript"></script>
<div class="row">
    <div class="col-md-5">
        <label>Imagen</label>
        <br>
        <img id="foto_articulo" src="images/fotoparte.png" alt="img_parte" height="160px" width="200px">
		<input type="file" accept="image/png, image/jpeg" id="artFoto"/>
		<input type="hidden"  id="artFotoNombre"/>
		<label style="display:none" id="fotoerror">Ha ocurrido un error cuando al intentar subir la foto</label>
		<br>
		<button class="btn btn-primary" onclick="cambiarFotoArt()">Subir foto</button>
    </div>
    <div class="col-md-2">
        <br>
        <button type="button" onclick="agregarFotoArticulo()"><i class="fa fa-plus"></i></button>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12 table-responsive materialesT">
        <input type="hidden" id="mainfoto">
		<table class="table table-hover" id="tablafotosArt">
            <thead>
                <tr>
                    <th>Foto</th>
					<th>Principal</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaTablaFotosArt();

        $('#tablafotosArt tbody').on('click', 'button', function () {
            if ($(this).attr("id") == "eliminarFotoArt") {
                actualRow = $("#tablafotosArt").DataTable().row($(this).parents('tr'));
                eliminarFoto_Art(actualRow);
            }
        });
		
		$('#tablafotosArt tbody').on('click', '.principal', function () {
            $('.principal').not(this).prop('checked', false);
			$('.principal').not(this).val('0');
			$(this).val('1');
			actualRow = $("#tablafotosArt").DataTable().row($(this).parents('tr'));
			$('#mainfoto').val(actualRow.data().Foto);
        });
    });
    
    function agregarFotoArticulo() {
		var rowCount = $('#tablafotosArt tr').length;
		if (rowCount <= 5) {
            addFotoToArticulo($("#artFotoNombre").val());
		}
    }
</script>