<?php
class Liste_de_demains extends  Controller
{
  public function __construct()
  {
    $this->requireLogin(); // L'utilisateur doit être connecté pour accéder à n'importe quelle méthode
  }

  public  function  index()
  {
    $id_compagnie = $_SESSION['id_compagnie'];
    $idDepart = $_SESSION['ville'];
    $model = new Liste_du_jour();
     $liste_horaires = $model->SelectAllData(
      '*',
      'horaire',
      'id_compagnie = :id_compagnie',
      ['id_compagnie' => $id_compagnie]
    );
  $destinations = $model->getDestinations($idDepart, $id_compagnie);
    
    $resultats = $model->FetchSelectWheres(
        '*',
        'billets inner join client on billets.id_client = client.idClient',
        'billets.id_compagnie = :id_compagnie',
        ['id_compagnie' => $id_compagnie]
    );

    $this->view('admin/liste_de_demain',['liste_demain'=>$resultats,
  'liste_horaires' => $liste_horaires,
      'destinations' => $destinations]);
  }
}