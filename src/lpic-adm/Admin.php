<?php

class Admin extends Controller
{
  public function hook()
  {
    // Check if current user can access to administration section
    if (!$this->isAdmin())
      $this->action = 'forbidden';
      //$this->forbidden();
  }
  
  public function home()
  {
    $this->title = "Accueil";
    if (isset($_POST['query']))
    {
      $rslt = Query::send($_POST['query']);
      $this->data['res'] = $rslt;
    }
  }
}
