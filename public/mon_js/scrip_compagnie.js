$(document).ready(function() {
    $('.edit-btn').click(function(e) {
        e.preventDefault();

        var idCompagnie = $(this).data('id_compagnie');
        var nomCompagnie = $(this).data('nom_compagnie');
        var libele = $(this).data('libele');
        var slogant = $(this).data('slogant');

        $('#inputidCompagnie').val(idCompagnie);
        $('#inputnomCompagnie').val(nomCompagnie);
        $('#inputlibele').val(libele);
        $('#inputslogant').val(slogant);

        $('#exampleDangerModal1').modal('show');
    });
});
