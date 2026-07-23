// Impression thermique ESC/POS : le site (hébergé sur LWS) ne peut pas atteindre
// l'imprimante du comptoir (réseau local ou USB). On récupère donc les données du
// billet en JSON depuis le site, puis on les transmet au pont d'impression local
// (local-print-bridge/), qui tourne sur le poste du comptoir et parle directement
// à l'imprimante.
(function () {
    var BRIDGE_URL = 'http://127.0.0.1:9200/print';

    $(document).on('click', '.thermal-print-btn', function (e) {
        e.preventDefault();
        var idBillets = $(this).data('id');
        var url = window.PWA_BASE_URL
            ? window.PWA_BASE_URL + '/admin/Liste_du_jours/donneesTicketThermique/' + idBillets
            : '/admin/Liste_du_jours/donneesTicketThermique/' + idBillets;

        $.getJSON(url)
            .done(function (billet) {
                if (billet.error) {
                    Swal.fire('Erreur', billet.error, 'error');
                    return;
                }

                fetch(BRIDGE_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(billet)
                })
                    .then(function (r) { return r.json(); })
                    .then(function (resultat) {
                        Swal.fire(
                            resultat.success ? 'Ticket imprimé' : 'Erreur d\'impression',
                            resultat.message,
                            resultat.success ? 'success' : 'error'
                        );
                    })
                    .catch(function () {
                        Swal.fire(
                            'Pont d\'impression injoignable',
                            'Vérifiez que le logiciel d\'impression locale est bien lancé sur ce poste (voir local-print-bridge/).',
                            'error'
                        );
                    });
            })
            .fail(function () {
                Swal.fire('Erreur', 'Impossible de récupérer les données du billet.', 'error');
            });
    });
})();
