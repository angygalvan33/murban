function autoCompleteUsuarios(usuarios, tieneCaja) {
    usuarios.select2( {
        placeholder: "Selecciona una opción",
        ajax: {
            url: './pages/cajaChica/administracion/autocompleteCCh.php',
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    searchTerm: params.term,
                    conCaja: tieneCaja
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