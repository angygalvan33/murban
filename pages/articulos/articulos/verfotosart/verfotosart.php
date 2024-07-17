<script src="pages/articulos/articulos/verfotosart/verfotosScriptArt.js" type="text/javascript"></script>
<div class="row">
    <div class="col-md-5">
        <label>Imagen</label>
        <br>
        <img id="verfoto_articulo" src="images/fotoparte.png" alt="img_parte" height="160px" width="200px">
		<input type="file" accept="image/png, image/jpeg" id="verartFoto"/>
		<input type="hidden" id="verartFotoNombre"/>
		<label style="display:none" id="verfotoerror">Ha ocurrido un error cuando al intentar subir la foto</label>
		<br>
		<button class="btn btn-primary" onclick="vercambiarFotoArt()">Subir foto</button>
    </div>
    <div class="col-md-2">
        <br>
        <button type="button" onclick="veragregarFotoArticulo()"><i class="fa fa-plus"></i></button>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-12 table-responsive materialesT">
        <input type="hidden" id="vermainfoto">
		<table class="table table-hover" id="vertablafotosArt">
            <thead>
                <tr>
				    <th></th>
                    <th>Foto</th>
					<th>Principal</th>
                    <th></th>
					<th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script type="text/javascript">
    $( document ).ready( function() {
        inicializaTablaverFotosArt();

        $('#vertablafotosArt tbody').on('click', 'button', function () {
			var data = $("#vertablafotosArt").DataTable().row($(this).parents('tr')).data();
			
            switch ($(this).attr("id")) {
                case "eliminarverFotoArt":
                    vereliminarFoto_Art(data.IdArticuloFoto);
                break;
                case "principalFotoArt":
                    setFotoPrincipal(data.Foto, data.IdArticulo);
                break;
            }
        });
		
		$('#vertablafotosArt tbody').on('click', '.verprincipal', function () {
            $('.verprincipal').not(this).prop('checked', false);
			$('.verprincipal').not(this).val('0');
			$(this).val('1');
        });
    });
	
	function veragregarFotoArticulo() {
		var Id = $(".fotos").attr("id");
        console.log(Id);
		var aFoto = $("#verartFotoNombre").val();
		veraddFotoToArticulo(aFoto, Id);
	}
</script>