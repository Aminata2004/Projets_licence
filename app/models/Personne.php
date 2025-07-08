<?php

    class Personne extends model{
        public function inscription($table=[]){
            if ($this->VerifyFields($table)) {
                # code...
                $this->e(extract($_POST));
                echo "$nom <br> $prenom <br> $contact <br> $email";exit();
                
            }
            
        }
    }