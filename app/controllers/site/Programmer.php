<?php

class Programmer extends Controller {
  
    public function show($encoded_id) {
    $id = base64_decode($encoded_id);

    if ($id === false || !is_numeric($id)) {
        http_response_code(404);
        die("ID invalide.");
    }

    $compagnieModel = new Compagnie();
    $programmeModel = new Programme();

    $compagnie = $compagnieModel->getById((int)$id);
    if (!$compagnie) {
        http_response_code(404);
        die("Compagnie non trouvée.");
    }

    $programmes = $programmeModel->getByCompagnie((int)$id);

    $this->view('site/programmer', [
        'compagnie' => $compagnie,
        'programmes' => $programmes
    ]);
}

public function Page_reservation(){
$this->view('site/page_alert');
}
}
