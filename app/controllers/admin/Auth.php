<?php
class Auth extends Controller
{
    public function logout()
    {
        session_unset();      
        session_destroy();    
header('Location: ' . BASE_URL . '/admin/Loguins');
exit();

    }
}
