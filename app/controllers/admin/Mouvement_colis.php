<?php

class Mouvement_colis extends Controller
{
    public function __construct()
    {
        $this->requirePermission('colis_mouvement');
    }

    public function index()
    {
        $mouvement_colis = new Mouvements_colis();

        // Traitement de la réception de colis (formulaire envoyé)
        if (isset($_POST['reception'])) {

            // Verrou PDG : cette action passe par index() (pas par une méthode nommée
            // add/modifier/... ), donc le garde-fou central App::isEcritureBloqueePourPDG()
            // ne la détecte pas comme une écriture. On bloque donc explicitement ici.
            if (($_SESSION['droit'] ?? null) === 'PDG') {
                $mouvement_colis->set_flash(
                    "Votre rôle est en lecture seule : vous ne pouvez pas réceptionner de colis.",
                    'danger'
                );
                header('Location: ' . BASE_URL . '/admin/mouvement_colis');
                exit;
            }

            if (empty($_POST['selected_colis'])) {
                $mouvement_colis->set_flash("Aucun colis sélectionné !", 'danger');
                header('Location: ' . BASE_URL . '/admin/mouvement_colis');
                exit;
            }

            $ids = array_map('intval', $_POST['selected_colis']);
            $model = new Mouvements_colis();

            // La notification au destinataire se fait maintenant par WhatsApp (bouton dans
            // l'onglet "Colis reçu"), plus par email : ici on marque juste les colis comme reçus.
            foreach ($ids as $id) {
                $model->marquerRecu($id);
            }

            $mouvement_colis->set_flash(
                "Colis marqués « reçu ». Utilisez le bouton WhatsApp dans l'onglet « Colis reçu » pour notifier chaque destinataire.",
                'success'
            );

            // ✅ Redirection après traitement POST
            header('Location: ' . BASE_URL . '/admin/mouvement_colis');
            exit;
        }

        // 💡 Ce bloc ne s'exécute que si ce n'est pas un POST
        $liste_colis = $mouvement_colis->FetchSelectColisEncours();
        // liste des colis recue
        $liste_colis_recue = $mouvement_colis->FetchSelectColisRecu();
        // liste des colis livre
        $liste_colis_livre = $mouvement_colis->FetchSelectColisLivre();
        $this->view('admin/mouvement_colis', ['liste_colis' => $liste_colis, 'liste_colis_recue' => $liste_colis_recue, 'liste_colis_livre' => $liste_colis_livre]);
    }
}
