<?php
class Add_gares extends  Controller
{
  public function __construct()
  {
    $this->requirePermission('Configuration_gestion_gare');
  }

  public  function  index()
  {
    $this->view('admin/add_gare');
  }
}
