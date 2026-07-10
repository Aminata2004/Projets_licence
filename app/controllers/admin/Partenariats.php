<?php

class Partenariats extends Controller
{
    public function __construct()
    {
        $this->requireLogin();

        // Seul le super_admin gère les demandes de partenariat (nouvelles compagnies).
        if ($_SESSION['droit'] !== 'super_admin') {
            header("Location: " . BASE_URL . "/admin/Homes");
            exit;
        }
    }

    public function index()
    {
        $model = new Partenaire();
        $partenaires = $model->getTousAvecApercu();

        $this->view('admin/partenariats', [
            'partenaires' => $partenaires
        ]);
    }

    public function discussion($id_partenaire)
    {
        $partenaireModel = new Partenaire();
        $messageModel = new Partenaire_message();

        $partenaire = $partenaireModel->getById($id_partenaire);
        if (!$partenaire) {
            header("Location: " . BASE_URL . "/admin/Partenariats");
            exit;
        }

        if (isset($_POST['envoyer_message'])) {
            $texte = trim($_POST['message'] ?? '');
            if ($texte !== '') {
                $messageModel->envoyer($id_partenaire, 'admin', $texte);
            }
            header("Location: " . BASE_URL . "/admin/Partenariats/discussion/" . $id_partenaire);
            exit;
        }

        $messages = $messageModel->getByPartenaire($id_partenaire);

        $this->view('admin/partenariat_discussion', [
            'partenaire' => $partenaire,
            'messages' => $messages
        ]);
    }
}
