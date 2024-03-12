<?php 

namespace App\Models;

use CodeIgniter\Model;

class ReservationModel extends Model
{
    // Nom de la table dans la base de données
    protected $table = 'reservations';

    // Clé primaire de la table
    protected $primaryKey = 'id';

    // Activer la suppression douce pour pouvoir supprimer une RESA, mais garder la RESA présente dans l'historique LOGEMENT & USER 
    protected $useSoftDeletes = true;     
    
    // Champs autorisés à être assignés lors de l'utilisation des méthodes insert ou update
    protected $allowedFields = ['start_date', 'end_date', 'chef_cuisine', 'visite', 'logement_id', 'user_id'];

    // Activer l'utilisation des timestamps pour suivre la création et la mise à jour des enregistrements.
    protected $useTimestamps = true;

    // Nom de la colonne enregistrant la date et l'heure de création des enregistrements.
    protected $createdField = 'created_at';

    // Nom de la colonne enregistrant la date et l'heure de la dernière mise à jour des enregistrements.
    protected $updatedField = 'updated_at';

    // Nom de la colonne pour la suppression douce, enregistrant la date et l'heure de "suppression".
    protected $deletedField = 'deleted_at';

    public function getRating(int $reservationId) : array
    {
        return $this->db->table('ratings')
            ->select('id, rated, text, logement_id, reservation_id, user_id')
            ->where('reservation_id', $reservationId)
            ->get()
            ->getResultArray();
    }
    
    public function getUser($userId)
    {
        return $this->db->table('users')
                        ->select('id, name, email, tel')
                        ->where('id', $userId)
                        ->get()
                        ->getRowArray();
    }

    public function calculateReservationPrice($start_date, $end_date, $logement_id) {
        // Récupérer les tarifs du logement
        $logementModel = new LogementModel();
        $logement = $logementModel->find($logement_id);
    
        if (!$logement) {
            return false; // Logement non trouvé
        }
    
        // Récupérer les tarifs bas, moyens et hauts du logement
        $tarif_bas = $logement['tarif_bas'] / 7; // Tarif par nuitée
        $tarif_moyen = $logement['tarif_moyen'] / 7; // Tarif par nuitée
        $tarif_haut = $logement['tarif_haut'] / 7; // Tarif par nuitée
    
        // Convertir les dates en objets DateTime pour faciliter la comparaison
        $start_date = new \DateTime($start_date);
        $end_date = new \DateTime($end_date);
    
        // Déterminer la période de réservation
        $periodes_basse = [['01-03', '04-15'], ['10-16', '12-22']]; // Exemple : du 3 Janvier au 15 Avril et du 16 Octobre au 22 Décembre considéré comme basse saison
        $periodes_moyenne = [['04-16', '06-30'], ['09-01', '10-15']]; // Exemple : du 16 Avril au 30 Juin et du 1er Septembre au 15 Octobre considéré comme moyenne saison

    
        $start_month_day = $start_date->format('m-d');
        $end_month_day = $end_date->format('m-d');
    
        $is_basse_saison = false;
        foreach ($periodes_basse as $periode) {
            if ($start_month_day >= $periode[0] && $end_month_day <= $periode[1]) {
                $is_basse_saison = true;
                break;
            }
        }
    
        if ($is_basse_saison) {
            $tarif = $tarif_bas; // Basse saison
        } else {
            $is_moyenne_saison = false;
            foreach ($periodes_moyenne as $periode) {
                if ($start_month_day >= $periode[0] && $end_month_day <= $periode[1]) {
                    $is_moyenne_saison = true;
                    break;
                }
            }
    
            $tarif = $is_moyenne_saison ? $tarif_moyen : $tarif_haut; // Moyenne saison ou haute saison par défaut
        }
    
        // Calculer la durée de la réservation en jours
        $interval = $start_date->diff($end_date);
        $days_difference = $interval->days;

        // Calculer le tarif total en fonction du nombre de nuits, en arrondissant vers le haut
        $tarif_total = ceil($tarif * $days_difference);
    
        return $tarif_total;
    }
}