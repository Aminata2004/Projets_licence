<?php $this->view('admin/partials/headers') ?>

<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start top header-->
        <?php $this->view('admin/partials/navbar') ?>
        <!--end top header-->

        <!--start sidebar -->
        <?php $this->view('admin/partials/sidebar') ?>
        <!--end sidebar -->
        <!--start content-->
        <main class="page-content ">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-flex flex-wrap align-items-center mb-3">
                <div class="breadcrumb-title pe-3">Colis </div>
                <div class="ps-3 d-none d-sm-block">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li class="breadcrumb-item active text-primary" aria-current="page">Historique des colis enregistre</li>
                        </ol>
                    </nav>
                </div>
               <div class="ms-sm-auto mt-2 mt-sm-0">
                    <a href="<?= BASE_URL ?>/admin/Colis_prise_en_charges/ajouter_colis" class="btn btn-sm btn-primary rounded-pill shadow-sm me-2">
                         + Ajouter
                    </a>
                    <a href="javascript:history.back()" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm">
                        <i class="bx bx-left-arrow-alt me-1"></i> Retour
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-body border-top border-primary border-1">
                    <ul class="nav nav-tabs nav-primary" role="tablist">


                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" href="<?= BASE_URL ?>/admin/Historiques/historique_colis_enregistrer" role="tab" aria-selected="true">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-calendar-event font-19"></i> <!-- Icône calendrier -->
                                    </div>
                                    <div class="tab-title">Historique du colis enregistre</div>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item" role="presentation">
                            <a class="nav-link" href="<?= BASE_URL ?>/admin/Historiques/historique_colis_livre" role="tab" aria-selected="false">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="fadeIn animated bx bx-time-five font-19"></i> <!-- Icône horloge -->
                                    </div>
                                    <div class="tab-title">Historique du colis livre</div>
                                </div>
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card shadow-sm border-0 rounded">
                        <div class="card-header bg-primary text-white d-flex align-items-center">
                            <i class="bx bx-filter-alt me-2"></i>
                            <span class="fw-bold">Filtrage</span>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Date de début</label>
                                    <input type="date" class="form-control shadow-sm" id="date" name="Date_debut">
                                </div>
                                <div class="col-md-6">
                                    <label for="jour" class="form-label">Date de fin</label>
                                    <input type="date" class="form-control shadow-sm" id="jour" name="Date_fin">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php $this->view("admin/set_flash") ?>
            <div class="card">
                <div class="card-body">

                    <div class="tab-content py-3 table-responsive">
                        <table id="example" class="table table-striped table-bordered mobile-card-table" style="width:100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Expediteur</th>
                                    <th>Destinateur</th>
                                    <th>Nom colis</th>
                                    <th>Valeur</th>
                                    <th>Fraix de transaction</th>
                                    <th>Code colis</th>
                                    <th>Status</th>

                                </tr>
                            </thead>
                            <tbody id="tablecolis">

                            </tbody>
                        </table>


                    </div>
                </div>
            </div>

    <!--end row-->
    </main>
    <!--end page main-->

    <!--start overlay-->
    <div class="overlay nav-toggle-icon"></div>
    <!--end overlay-->

    <!--Start Back To Top Button-->
    <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
    <!--End Back To Top Button-->

    </div>
    <!--end wrapper-->
    <!-- Button trigger modal -->

    <!-- Modal -->

    <?php $this->view('admin/partials/foot') ?>
    <script>
        $(document).ready(function() {
            $('#date, #jour').change(function() {
                const Date_debut = $('#date').val();
                const Date_fin = $('#jour').val();

                if (Date_debut && Date_fin) {
                    $.ajax({
                        url: '<?= BASE_URL ?>/admin/Historiques/AjaxFiltreListecolis',
                        type: 'POST',
                        data: {
                            Date_debut,
                            Date_fin
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.error) {
                                Swal.fire('Erreur', response.error, 'error');
                            } else {
                                // Met à jour le tbody du tableau
                                $('#tablecolis').html(response.tbody);
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Erreur AJAX', xhr.responseText, 'error');
                        }
                    });
                }
            });
        });
    </script>

</body>

</html>