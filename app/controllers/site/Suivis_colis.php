<?php

class Suivis_colis extends Controller
{
    public function index()
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);

        $Compagnie = new Compagnie();
        $compagnies = $Compagnie->SelectAllData('*', "compagnie");

        $colis = null;
        $messageErreur = null;

        if (isset($_GET['code_colis'], $_GET['id_compagnie']) && !empty($_GET['code_colis']) && !empty($_GET['id_compagnie'])) {
            $codeColis = trim($_GET['code_colis']);
            $idCompagnie = intval($_GET['id_compagnie']);

            // Vérifier si le colis appartient à la compagnie
            $resultat = $Compagnie->getColisByCodeAndCompagnie(
                "SELECT colis.*
     FROM colis
     JOIN expediteurs ON colis.id_expediteur = expediteurs.id_expediteur
     JOIN destinataires ON colis.id_destinataire = destinataires.id_destinataire
     JOIN agence a ON colis.id_agence = a.idAgence
     WHERE colis.code_colis = :code AND colis.id_compagnie = :id_compagnie",
                [
                    ":code" => $codeColis,
                    ":id_compagnie" => $idCompagnie
                ]
            );


            if ($resultat) {
                // Rediriger avec uniquement un paramètre pour éviter que le champ garde la valeur
                header("Location: " . BASE_URL . "/site/Suivis_colis?show_code=" . urlencode($codeColis));
                exit();
            } else {
                $messageErreur = "❌ Ce colis n'appartient pas à la compagnie sélectionnée.";
            }
        }

        // Si show_code est dans l'URL, récupérer les infos du colis sans garder tous les paramètres
        if (isset($_GET['show_code'])) {
            $codeColis = $_GET['show_code'];
            $colis = $Compagnie->getColisByCodeAndCompagnie(
                "SELECT colis.*, expediteurs.*, destinataires.*, a.*
         FROM colis
         JOIN expediteurs ON colis.id_expediteur = expediteurs.id_expediteur
         JOIN destinataires ON colis.id_destinataire = destinataires.id_destinataire
         JOIN agence a ON colis.id_agence = a.idAgence
         WHERE colis.code_colis = :code",
                [":code" => $codeColis]
            );
        }


        $this->view('site/suivis_colis', [
            'compagnies' => $compagnies,
            'colis' => $colis,
            'erreur' => $messageErreur
        ]);
    }
}
