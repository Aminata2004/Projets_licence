 <?php
   class Liste_du_jour extends Model
   {
    
public function getDestinations($idDepart, $idCompagnie)
{
    $sql = "SELECT DISTINCT p.idDestination
            FROM programmer p
            WHERE p.idDepart = :idDepart
              AND p.id_compagnie = :idCompagnie
            ORDER BY p.idDestination";
    return $this->fetchAll($sql, [
        ':idDepart' => $idDepart,
        ':idCompagnie' => $idCompagnie
    ]);
}
   }
