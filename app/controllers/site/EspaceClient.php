<?php
class EspaceClient extends Controller
{
    private function requireClientLogin()
    {
        if (empty($_SESSION['client_id'])) {
            header('Location: ' . BASE_URL . '/site/EspaceClient');
            exit();
        }
    }

    public function index()
    {
        $error = null;

        if (isset($_POST['login_client'])) {
            $numeroBillets = trim($_POST['numeroBillets'] ?? '');
            $numeroClient  = trim($_POST['numeroClient'] ?? '');

            if ($numeroBillets && $numeroClient) {
                $model  = new EspaceClient();
                $client = $model->login($numeroBillets, $numeroClient);

                if ($client) {
                    $_SESSION['client_id']      = $client->idClient;
                    $_SESSION['client_nom']     = $client->Client;
                    $_SESSION['client_tel']     = $client->numeroClient;
                    $_SESSION['client_email']   = $client->emailClient ?? '';
                    $_SESSION['client_compagnie'] = $client->id_compagnie;

                    header('Location: ' . BASE_URL . '/site/EspaceClient/dashboard');
                    exit();
                } else {
                    $error = "Numéro de billet ou téléphone incorrect.";
                }
            } else {
                $error = "Veuillez remplir tous les champs.";
            }
        }

        $this->view('site/espace_client_login', ['error' => $error]);
    }

    public function dashboard()
    {
        $this->requireClientLogin();
        $model    = new EspaceClient();
        $commandes = $model->getMesCommandes($_SESSION['client_id']);
        $this->view('site/espace_client_dashboard', ['commandes' => $commandes]);
    }

    public function show($idBillets)
    {
        $this->requireClientLogin();
        $model   = new EspaceClient();
        $commande = $model->getCommandeDetail((int)$idBillets, (int)$_SESSION['client_id']);

        if (!$commande) {
            header('Location: ' . BASE_URL . '/site/EspaceClient/dashboard');
            exit();
        }

        $this->view('site/commande_show', ['commande' => $commande]);
    }

    public function valider()
    {
        $this->requireClientLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/site/EspaceClient/dashboard');
            exit();
        }

        $idBillets = (int)($_POST['idBillets'] ?? 0);
        $model     = new EspaceClient();
        $ok        = $model->validerCommande($idBillets, (int)$_SESSION['client_id']);

        $msg = $ok ? 'success' : 'error';
        header('Location: ' . BASE_URL . '/site/EspaceClient/show/' . $idBillets . '?status=' . $msg);
        exit();
    }

    public function monEpargne()
    {
        $this->requireClientLogin();
        $model   = new EspaceClient();
        $epargne = $model->getEpargne((int)$_SESSION['client_id']);
        $solde   = $model->getSoldeEpargne((int)$_SESSION['client_id']);
        $this->view('site/mon_epargne', ['epargne' => $epargne, 'solde' => $solde]);
    }

    public function mesPaiements()
    {
        $this->requireClientLogin();
        $model    = new EspaceClient();
        $paiements = $model->getMesPaiements((int)$_SESSION['client_id']);
        $this->view('site/mes_paiements', ['paiements' => $paiements]);
    }

    public function logout()
    {
        unset(
            $_SESSION['client_id'],
            $_SESSION['client_nom'],
            $_SESSION['client_tel'],
            $_SESSION['client_email'],
            $_SESSION['client_compagnie']
        );
        header('Location: ' . BASE_URL . '/site/EspaceClient');
        exit();
    }
}
