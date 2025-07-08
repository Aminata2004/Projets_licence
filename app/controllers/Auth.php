<?php
class Auth extends Controller
{
    public function logout()
    {
        session_unset();      
        session_destroy();    
        // Redirection vers la page de login
    header('Location: ' . BASE_URL . '/Loguins');
    exit;
    }
}
