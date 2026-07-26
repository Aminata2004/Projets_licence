<!-- Modal : détail des mouvements (entrées/sorties) d'une caisse de gare -->
<div class="modal fade" id="modalMouvementsCaisse" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-list-ul me-1"></i> Mouvements de caisse
                    <small class="text-muted d-block" id="mvtCaisseInfos"></small>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3" id="mvtPeriodeSelecteur">
                    <button type="button" class="btn btn-sm rounded-pill mvt-periode-pill mvt-periode-active" data-periode="jour">Aujourd'hui</button>
                    <button type="button" class="btn btn-sm rounded-pill mvt-periode-pill" data-periode="mois">Ce mois</button>
                    <button type="button" class="btn btn-sm rounded-pill mvt-periode-pill" data-periode="tout">Tout l'historique</button>
                </div>

                <style>
                    .mvt-periode-pill { background: #fff; border: 1px solid #dee2e6; color: #6c757d; }
                    .mvt-periode-pill:hover { background: #f1f3f5; color: #495057; }
                    .mvt-periode-pill.mvt-periode-active { background: #e9ecef; border-color: #ced4da; color: #212529; font-weight: 600; }
                </style>

                <div id="mvtCaisseLoading" class="text-center text-muted py-4">Chargement...</div>
                <div id="mvtCaisseErreur" class="alert alert-danger d-none"></div>

                <div id="mvtCaisseContenu" class="d-none">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <div class="p-2 rounded bg-success bg-opacity-10 text-success text-center fw-bold">
                                Entrées : <span id="mvtTotalEntrees">0</span> FCFA
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded bg-danger bg-opacity-10 text-danger text-center fw-bold">
                                Sorties : <span id="mvtTotalSorties">0</span> FCFA
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold text-success"><i class="bx bx-down-arrow-circle"></i> Entrées</h6>
                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr><th>Type</th><th>Référence</th><th>Date</th><th class="text-end">Montant</th></tr>
                            </thead>
                            <tbody id="mvtCorpsEntrees"></tbody>
                        </table>
                    </div>

                    <h6 class="fw-bold text-danger"><i class="bx bx-up-arrow-circle"></i> Sorties</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="table-light">
                                <tr><th>Type</th><th>Référence</th><th>Date</th><th class="text-end">Montant</th></tr>
                            </thead>
                            <tbody id="mvtCorpsSorties"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var modalEl = document.getElementById('modalMouvementsCaisse');
    if (!modalEl) return;
    var modal = new bootstrap.Modal(modalEl);

    function formatMontant(n) {
        return new Intl.NumberFormat('fr-FR').format(Math.round(n));
    }

    function formatDate(d) {
        if (!d) return '-';
        var parts = d.split('-');
        return parts.length === 3 ? parts[2] + '/' + parts[1] + '/' + parts[0] : d;
    }

    function ligne(m) {
        return '<tr><td>' + m.type + '</td><td>' + (m.reference ?? '-') + '</td><td>' +
            formatDate(m.date) + '</td><td class="text-end">' + formatMontant(m.montant) + ' F</td></tr>';
    }

    var caisseCourante = null;
    var periodeCourante = 'jour';

    function chargerMouvements(id, periode) {
        document.getElementById('mvtCaisseLoading').classList.remove('d-none');
        document.getElementById('mvtCaisseErreur').classList.add('d-none');
        document.getElementById('mvtCaisseContenu').classList.add('d-none');

        fetch('<?= BASE_URL ?>/admin/Caisse/mouvements/' + id + '?periode=' + periode)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                document.getElementById('mvtCaisseLoading').classList.add('d-none');

                if (data.error) {
                    document.getElementById('mvtCaisseErreur').textContent = data.error;
                    document.getElementById('mvtCaisseErreur').classList.remove('d-none');
                    return;
                }

                document.getElementById('mvtCaisseInfos').textContent =
                    data.caisse.localite + ' (' + data.caisse.numeroGare + ') — du ' +
                    formatDate(data.caisse.debut) + ' au ' + formatDate(data.caisse.fin);

                document.getElementById('mvtTotalEntrees').textContent = formatMontant(data.total_entrees);
                document.getElementById('mvtTotalSorties').textContent = formatMontant(data.total_sorties);

                var corpsEntrees = document.getElementById('mvtCorpsEntrees');
                var corpsSorties = document.getElementById('mvtCorpsSorties');

                corpsEntrees.innerHTML = data.entrees.length
                    ? data.entrees.map(ligne).join('')
                    : '<tr><td colspan="4" class="text-center text-muted">Aucune entrée</td></tr>';

                corpsSorties.innerHTML = data.sorties.length
                    ? data.sorties.map(ligne).join('')
                    : '<tr><td colspan="4" class="text-center text-muted">Aucune sortie</td></tr>';

                document.getElementById('mvtCaisseContenu').classList.remove('d-none');
            })
            .catch(function () {
                document.getElementById('mvtCaisseLoading').classList.add('d-none');
                document.getElementById('mvtCaisseErreur').textContent = "Erreur lors du chargement des mouvements.";
                document.getElementById('mvtCaisseErreur').classList.remove('d-none');
            });
    }

    document.querySelectorAll('.btn-voir-mouvements').forEach(function (btn) {
        btn.addEventListener('click', function () {
            caisseCourante = btn.getAttribute('data-id');
            periodeCourante = 'jour';

            document.querySelectorAll('#mvtPeriodeSelecteur button').forEach(function (b) {
                b.classList.toggle('mvt-periode-active', b.getAttribute('data-periode') === 'jour');
            });
            document.getElementById('mvtCaisseInfos').textContent = '';

            modal.show();
            chargerMouvements(caisseCourante, periodeCourante);
        });
    });

    document.querySelectorAll('#mvtPeriodeSelecteur button').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!caisseCourante) return;
            periodeCourante = btn.getAttribute('data-periode');

            document.querySelectorAll('#mvtPeriodeSelecteur button').forEach(function (b) {
                b.classList.toggle('mvt-periode-active', b === btn);
            });

            chargerMouvements(caisseCourante, periodeCourante);
        });
    });
})();
</script>
