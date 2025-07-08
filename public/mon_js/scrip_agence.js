$(document).ready(function() {
    $('.edit-btn').click(function(e) {
        e.preventDefault();

        var idAgence = $(this).data('idAgence');
        var numero = $(this).data('numero');
        var localite = $(this).data('localite');
        var code = $(this).data('code');
        var tel = $(this).data('tel');

        $('#inputidAgence').val(idAgence);
        $('#inputnumero').val(numero);
        $('#inputlocalite').val(localite);
        $('#inputcode').val(code);
          $('#tel').val(tel);

        $('#exampleDangerModal1').modal('show');
    });
});
