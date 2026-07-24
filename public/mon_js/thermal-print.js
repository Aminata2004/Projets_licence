// Impression thermique ESC/POS : le site (hébergé sur LWS) ne peut pas atteindre
// l'imprimante du comptoir (réseau local ou USB). On récupère donc les données du
// document (billet ou colis) en JSON depuis le site, puis on les transmet au pont
// d'impression local (local-print-bridge/), qui tourne sur le poste du comptoir et
// parle directement à l'imprimante. Si le pont est injoignable, on ouvre le PDF (repli
// fiable, imprimable via le pilote Windows déjà installé) plutôt que de ne rien faire.
(function () {
    var BRIDGE_URL = 'http://127.0.0.1:9200/print';

    function base() {
        return window.PWA_BASE_URL || '';
    }

    // auto = true pour un déclenchement automatique (ex: juste après l'enregistrement
    // d'un billet) : pas de message de confirmation bruyant en cas de succès, juste en
    // cas d'échec — et le repli PDF s'ouvre sans que l'agent ait à recliquer.
    function imprimerDocumentThermique(urlDonnees, urlPdf, options) {
        options = options || {};
        var auto = options.auto === true;

        function ouvrirPdf() {
            window.open(urlPdf, '_blank');
        }

        $.getJSON(urlDonnees)
            .done(function (donnees) {
                if (donnees.error) {
                    if (auto) { ouvrirPdf(); return; }
                    Swal.fire('Erreur', donnees.error, 'error');
                    return;
                }

                fetch(BRIDGE_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(donnees)
                })
                    .then(function (r) { return r.json(); })
                    .then(function (resultat) {
                        if (resultat.success) {
                            if (!auto) {
                                Swal.fire('Ticket imprimé', resultat.message, 'success');
                            }
                            return;
                        }
                        if (auto) { ouvrirPdf(); return; }
                        Swal.fire('Erreur d\'impression', resultat.message, 'error');
                    })
                    .catch(function () {
                        // Pont non lancé sur ce poste : on n'empêche pas la remise du document au
                        // client pour autant, on retombe sur le PDF (imprimable via le pilote).
                        if (auto) { ouvrirPdf(); return; }
                        Swal.fire(
                            'Pont d\'impression injoignable',
                            'Vérifiez que le logiciel d\'impression locale est bien lancé sur ce poste (voir local-print-bridge/).',
                            'error'
                        );
                    });
            })
            .fail(function () {
                if (auto) { ouvrirPdf(); return; }
                Swal.fire('Erreur', 'Impossible de récupérer les données.', 'error');
            });
    }

    window.imprimerBilletThermique = function (idBillets, options) {
        imprimerDocumentThermique(
            base() + '/admin/Liste_du_jours/donneesTicketThermique/' + idBillets,
            base() + '/admin/Liste_du_jours/recu/' + idBillets,
            options
        );
    };

    window.imprimerColisThermique = function (idColis, options) {
        imprimerDocumentThermique(
            base() + '/admin/Colis_prise_en_charges/donneesRecuThermique/' + idColis,
            base() + '/admin/Colis_prise_en_charges/imprimer_recu/' + idColis,
            options
        );
    };

    $(document).on('click', '.thermal-print-btn', function (e) {
        e.preventDefault();
        window.imprimerBilletThermique($(this).data('id'));
    });

    $(document).on('click', '.thermal-print-colis-btn', function (e) {
        e.preventDefault();
        window.imprimerColisThermique($(this).data('id'));
    });
})();
