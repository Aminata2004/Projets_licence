<?php
class Model extends Database
{
    protected $table = "";

    public function VerifyFields($fields = [])
    {
        if (count($fields) > 0) {
            foreach ($fields as $field) {
                if (empty($_POST[$field]) || trim($_POST[$field]) === '') {
                    return false;
                }
            }
            return true;
        }
    }
    protected function VerifyField($fields = [])
    {

        if (count($fields) != 0) {

            foreach ($fields as $field) {

                if (empty($_POST[$field])) {
                    return false;
                }
            }
            return true;
        }
    }

    //    html verify data
    public function e($value)
    {

        if ($value) {
            $value = htmlspecialchars($value);
            $value = htmlentities($value);
            $value = strip_tags($value);
            return $value;
        }
    }

    //   data cript data
    public  function bcript_hash_password($value, $options = array())

    {
        $cost = isset($options['rounds']) ? $options['rounds'] : 10;
        $hash = password_hash($value, PASSWORD_BCRYPT, array('cost' => $cost));
        if ($hash === false) {
            throw new Exception("Bcript hashing n'est pas suporte.");
        }
        return $hash;
    }
    //    mot de pass verification
    public function bcript_verify_password($value, $hashedValue)
    {
        return password_verify($value, $hashedValue);
    }
    // selection des utilisateur
    /* private function utilisateur($field,$value){

         $que = $this->db->prepare("SELECT idUt
             FROM agent JOIN utilisateur ON agent.numAgent=utilisateur.numAgent

             WHERE $field=?");

         $que->execute([$value]);
         $count = $que->rowCount();
         $que->closeCursor();
         return $count;
     }
 */


    // public function chekErrorsInt($value,$entier,$message){

    //     if(strlen($value)<(int)$entier || strlen($value) > (int)$entier){
    //         return  array_push($this->errors,$message);
    //     }

    // }

    // public function chekErrorsString($value,$message){


    //     if(!is_numeric($value)){
    //         return array_push($this->errors,$message);
    //     }

    // }

    // edn des utilisateur
    // Échappe les caractères spéciaux dans une chaîne pour la rendre sûre pour l'affichage HTML

    // Définit un message flash pour les notifications
    public function set_flash($message, $type = 'danger')
    {
        $_SESSION['notification']['message'] = $message;
        $_SESSION['notification']['type'] = $type;
        $_SESSION['notification']['class'] = $this->get_alert_class($type); // ici code couleur
        $_SESSION['notification']['icon'] = $this->get_alert_icon($type);
    }

    private function get_alert_class($type)
    {
        switch ($type) {
            case 'success':
                return '#10b981'; // Vert moderne (Emerald)
            case 'danger':
                return '#e11d48'; // Rouge moderne (Rose)
            case 'warning':
                return '#f59e0b'; // Orange clair TransGest
            case 'primary':
            default:
                return '#0f3b5e'; // Bleu foncé TransGest
        }
    }

    private function get_alert_icon($type)
    {
        switch ($type) {
            case 'success':
                return 'bx bx-check-circle';
            case 'danger':
                return 'bx bx-error';
            case 'warning':
                return 'bx bx-warning';
            default:
                return 'bx bx-info-circle';
        }
    }

    //   save input (enregistrement des contenue)
    public function save_input_data()
    {
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'password') == false) {
                $_SESSION['input'][$key] = $value;
            }
        }
    }
    // get input key  (contenut)
    public function get_input($key)
    {
        return !empty($_SESSION['input'][$key])
            ? $this->e($_SESSION['input'][$key])
            : null;
    }
    // the redirection script

    public  function redirect($page)
    {

        header("Location:" . ROOT . "/" . trim($page, "/"));
        exit();
    }



    //    partie clear input date fields
    public  function clear_input_data()
    {
        if (isset($_SESSION['input'])) {
            $_SESSION['input'] = [];
        }
    }

    //    public function findAll($table){
    //            $query="select * from $table ";
    //            return $this->queryAll($query,"object");
    //    }
    //
    //    public function findAllCounter($table){
    //            $query="select * from $table ";
    //            return $this->queryAllCounter($query,"object");
    //    }
    //
    //    public function find($selsct,$table,$values,$data=[],$ordser=null){
    //        $query="select $selsct from $table where $values=? $ordser";
    //        return $this->query($query,$data,'object');
    //    }
    //
    //    public function findCounter($selsct,$table,$values,$data=[],$ordser=null){
    //        $query="select $selsct from $table where $values=? $ordser";
    //        return $this->queryCounter($query,$data,'object');
    //    }
    //
    //    public function find_fetch_table($table,$values=[]){
    //        $query="select * from $table ";
    //        return $this->query($query,$values,'object');
    //    }

    public function select_data_table_join_where($select, $excute_data = [])
    {
        $bdd = $this->connect();
        $stm = $bdd->prepare($select);
        $stm->execute($excute_data);
        $data = $stm->fetchAll(PDO::FETCH_OBJ);
        return $data;
    }


    // public function FetchSelectColis()
    // {
    //     $id_compagnie = $_SESSION['id_compagnie'] ?? null;

    //     $sql = "SELECT 
    //                 colis.*, 
    //                 a.localite AS destination, -- on récupère la localité de l’agence liée au colis
    //                 expediteurs.expediteur, expediteurs.numero_exp, expediteurs.whatsapp_exp, expediteurs.email_exp, 
    //                 destinataires.destinataire, destinataires.numero_dest, destinataires.whatsapp_dest, destinataires.email_dest 
    //             FROM colis
    //             JOIN expediteurs ON colis.id_expediteur = expediteurs.id_expediteur
    //             JOIN destinataires ON colis.id_destinataire = destinataires.id_destinataire
    //             JOIN agence a ON colis.id_agence = a.idAgence
    //             WHERE colis.id_compagnie = :id_compagnie
    //             ORDER BY colis.id_colis DESC";

    //     $stmt = $this->connect()->prepare($sql);
    //     $stmt->execute([':id_compagnie' => $id_compagnie]);

    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function FetchSelectColis()
    {
        $droit = $_SESSION['droit'] ?? null;
        $id_compagnie = $_SESSION['id_compagnie'] ?? null;
        $ville = $_SESSION['ville'] ?? null;
        $numero_gare = $_SESSION['numero_gare'] ?? null;

        $sql = "SELECT 
                colis.*, 
                a.localite AS destination, 
                expediteurs.expediteur, expediteurs.numero_exp, expediteurs.whatsapp_exp,
                destinataires.destinataire, destinataires.numero_dest, destinataires.whatsapp_dest
            FROM colis
            JOIN expediteurs ON colis.id_expediteur = expediteurs.id_expediteur
            JOIN destinataires ON colis.id_destinataire = destinataires.id_destinataire
            JOIN agence a ON colis.id_agence = a.idAgence
            
            WHERE colis.id_compagnie = :id_compagnie";

        $params = [':id_compagnie' => $id_compagnie];

        if ($droit === 'chef_d_escale') {
            $sql .= " AND colis.provient_de = :ville";
            $params[':ville'] = $ville;
        } elseif ($droit === 'Utilisateur') {
            $sql .= " AND colis.provient_de = :ville AND colis.num_gare = :numero_gare";
            $params[':ville'] = $ville;
            $params[':numero_gare'] = $numero_gare;
        }

        $sql .= " ORDER BY colis.id_colis DESC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function query(string $sql, array $params = [], bool $single = false): ?array
    {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);

        return $single ? $stmt->fetch() ?: null : $stmt->fetchAll();
    }


    public function selectWhereCount($select, $fields, $whereValue, $value = [])
    {
        $bdd = $this->connect();
        $que = $bdd->prepare("SELECT $select
                FROM $fields
                WHERE $whereValue=?");
        $que->execute([$value]);
        $count = $que->rowCount();
        $que->closeCursor();
        return $count;
    }
    /*Partie slect count data
     * */
    public function selectCount($select, $fields)
    {
        $bdd = $this->connect();
        $que = $bdd->prepare("SELECT $select
                FROM $fields");
        $que->execute();
        $count = $que->rowCount();
        $que->closeCursor();
        return $count;
    }
    /*
     * partie select end data
     *
     * */

    /* parite select find all avec where*/
    public function FetchAllSelectWhere($select, $fields, $whereValue, $value = [])
    {
        $bdd = $this->connect();
        $que = $bdd->prepare("SELECT $select
                FROM $fields
                WHERE $whereValue");
        $que->execute($value);
        $count = $que->fetchAll(PDO::FETCH_OBJ);
        $que->closeCursor();
        return $count;
    }

    public function FetchSelectAllWhere($select, $fields, $whereValue, $value = [])
    {
        $bdd = $this->bdd();
        $que = $bdd->prepare("SELECT $select FROM $fields WHERE $whereValue");
        $que->execute($value);
        $results = $que->fetchAll(PDO::FETCH_OBJ);  // Récupère toutes les lignes
        $que->closeCursor();
        return $results;
    }
    /* end find  find all avec where*/

    /* parite select find avec where*/
    public function FetchSelectWhere($select, $fields, $whereValue, $value = [])
    {
        $bdd = $this->connect();
        $que = $bdd->prepare("SELECT $select
                FROM $fields
                WHERE $whereValue");
        $que->execute($value);
        $count = $que->fetch(PDO::FETCH_OBJ);
        $que->closeCursor();
        return $count;
    }

    public function FetchSelectWhere2($select, $fields, $whereValue, $value = [])
    {
        $bdd = $this->connect();
        $que = $bdd->prepare("SELECT $select
                FROM $fields
                WHERE $whereValue");
        $que->execute($value);
        $count = $que->fetchAll(PDO::FETCH_OBJ);
        $que->closeCursor();
        return $count;
    }
    //    public function getColisByCodeAndCompagnie($code, $idCompagnie) {
    //     $sql = "SELECT * FROM colis WHERE code_colis = :code AND id_compagnie = :id_compagnie LIMIT 1";
    //     $stmt = $this->connect()->prepare($sql);
    //     $stmt->execute([
    //         ':code' => $code,
    //         ':id_compagnie' => $idCompagnie
    //     ]);
    //     return $stmt->fetch(PDO::FETCH_OBJ); // Retourne un seul objet ou false si non trouvé
    // }
    public function getColisByCodeAndCompagnie($sql, $params = [])
    {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_OBJ); // ou FETCH_ASSOC selon ton système
    }


    public function FetchSelectWhere1($select, $table, $where, $params)
    {
        $sql = "SELECT $select FROM $table WHERE $where";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // 👈 retourne TOUJOURS un tableau
    }


    public function FetchSelectWheres($select, $fields, $whereValue, $value = [])
    {
        $bdd = $this->connect();
        $que = $bdd->prepare("SELECT $select
                FROM $fields
                WHERE $whereValue");
        $que->execute($value);
        $count = $que->fetchAll(PDO::FETCH_OBJ);
        $que->closeCursor();
        return $count;
    }


    public function FetchWheresJoin($select, $from, $where, $params)
    {
        $sql = "SELECT $select FROM $from WHERE $where";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*partie  find all dataavec where */
    public function SelectData($select, $fields)
    {
        $bdd = $this->connect();
        $que = $bdd->prepare("SELECT $select
                FROM $fields");
        $que->execute();
        $count = $que->fetch(PDO::FETCH_OBJ);
        $que->closeCursor();
        return $count;
    }
    /* end partie all data */
    /*partie  find  data avec where */

    public function SelectAllData($select, $fields)
    {
        $bdd = $this->connect();
        $que = $bdd->prepare("SELECT $select
                FROM $fields");
        $que->execute();
        $count = $que->fetchAll(PDO::FETCH_OBJ);
        $que->closeCursor();
        return $count;
    }

    public function SelectAllDatas($select, $fields, $params = [])
    {
        $bdd = $this->connect();
        $que = $bdd->prepare("SELECT $select FROM $fields");
        $que->execute($params);  // <-- passer les paramètres ici !
        $count = $que->fetchAll(PDO::FETCH_OBJ);
        $que->closeCursor();
        return $count;
    }




    public function INSER($select, $fields)
    {
        $bdd = $this->connect();
        $que = $bdd->prepare("SELECT $select
                FROM $fields");
        $que->execute();
        $count = $que->fetchAll(PDO::FETCH_OBJ);
        $que->closeCursor();
        return $count;
    }
    /* end find data avec where */
    /* PARTIE CODE INSERTION
     * */
    public function insertion_update_simples($insert, $insert_data = [])
    {
        $bdd = $this->connect();
        $q = $bdd->prepare($insert);
        $q->execute($insert_data);
        return $q;
    }
    public function insertion_update_simples_insert_id($insert, $insert_data = [])
    {
        $bdd = $this->connect();
        $q = $bdd->prepare($insert);
        $q->execute($insert_data);
        $data = ["q" => $q, 'lastInsertId' => $bdd->lastInsertId()];
        return $data;
    }


    public function insertion_update_simple($insert, $insert_data = [])
    {
        $bdd = $this->connect();
        $q = $bdd->prepare($insert);
        $q->execute($insert_data);

        // ✅ Retourne l'ID inséré
        return $bdd->lastInsertId();
    }

    /*
     * Fin de la partie insert
     * */


    public function existe_deja($field, $value, $table)
    {
        $bdd = $this->bdd();
        $recup = $bdd->prepare("SELECT * FROM $table WHERE $field=?");
        $recup->execute([$value]);
        $count = $recup->rowCount();
        $recup->closeCursor();
        return $count;
    }
    public function existe($table, $champ, $valeur)
    {
        $sql = "SELECT COUNT(*) FROM $table WHERE $champ = :valeur";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([':valeur' => $valeur]);
        return $stmt->fetchColumn() > 0;
    }



    function set_swal(string $title, string $text, string $icon = 'info', string $confirmColor = '#0d6efd', ?string $redirectUrl = null): void
    {

        echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4/animate.min.css">';
        echo '<script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: ' . json_encode($title) . ',
                text: ' . json_encode($text) . ',
                icon: ' . json_encode($icon) . ',
                confirmButtonColor: ' . json_encode($confirmColor) . ',
                background: "#f0f8ff",
                showClass: {
                    popup: "animate__animated animate__zoomIn"
                },
                hideClass: {
                    popup: "animate__animated animate__fadeOut"
                }
            })' . ($redirectUrl ? '.then(() => { window.location.href = ' . json_encode($redirectUrl) . '; })' : '') . ';
        });
    </script>';
    }

    public function set_toast_top(string $title, string $text, ?string $bgColor = null, ?string $redirectUrl = null, ?int $timer = null)
    {
        $_SESSION['toast'] = [
            'title' => $title,
            'text'  => $text,
            'bg'    => $bgColor,
            'url'   => $redirectUrl,
            'timer' => $timer
        ];
    }





    // public function infoCompagnie($id_compagnie)
    // {
    //     $sql = "SELECT * FROM compagnie WHERE id_compagnie = :id_compagnie";
    //     $stmt = $this->connect()->prepare($sql);
    //     $stmt->execute(['id_compagnie' => $id_compagnie]);
    //     return $stmt->fetch();
    // }

    public function fetchAll($sql, $params = [])
    {
        try {
            // Remplace ceci par ta manière d'accéder à PDO
            $pdo = $this->connect(); // OU global $pdo;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Tu peux logger l'erreur ou l'afficher en debug
            die("Erreur dans fetchAll() : " . $e->getMessage());
        }
    }

    public function fetchOne(string $sql, array $params = [])
    {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
    }




    public function customQuery($sql, $params = [])
    {
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
