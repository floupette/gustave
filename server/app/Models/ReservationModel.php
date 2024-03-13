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
    
        // Ajouter un jour à la date de fin pour inclure la nuitée de départ
        $end_date->modify('-1 day');
    
        // Déterminer la période de réservation
        $periodes_basses = [['01-03', '04-15'], ['10-16', '12-22']]; // Exemple : du 3 Janvier au 15 Avril et du 16 Octobre au 22 Décembre considéré comme basse saison
        $periodes_hautes = [['07-01', '08-31'], ['12-23', '01-02']]; // Exemple : du 1er Juillet au 31 Août et du 23 Décembre au 2 Janvier considéré comme moyenne saison
    
        // Initialiser les compteurs pour chaque saison
        $basse_saison_nights = 0;
        $moyenne_saison_nights = 0;
        $haute_saison_nights = 0;
    
        // Parcourir chaque jour de la réservation
        $current_date = clone $start_date;
        while ($current_date <= $end_date) {
            $current_month_day = $current_date->format('m-d');
            $is_basse_saison = false;
            foreach ($periodes_basses as $periode) {
                if ($current_month_day >= $periode[0] && $current_month_day <= $periode[1]) {
                    $is_basse_saison = true;
                    break;
                }
            }
            if ($is_basse_saison) {
                $basse_saison_nights++;
            } else {
                $is_haute_saison = false;
                foreach ($periodes_hautes as $periode) {
                    if ($current_month_day >= $periode[0] && $current_month_day <= $periode[1]) {
                        $is_haute_saison = true;
                        break;
                    }
                }
                if ($is_haute_saison) {
                    $haute_saison_nights++;
                } else {
                    $moyenne_saison_nights++;
                }
            }
            $current_date->modify('+1 day');
        }
    
        // Calculer le tarif total en fonction du nombre de nuits dans chaque saison
        $tarif_total = ($basse_saison_nights * $tarif_bas) + ($moyenne_saison_nights * $tarif_moyen) + ($haute_saison_nights * $tarif_haut);
    
        // Arrondir le tarif total à l'unité
        $tarif_total = ceil($tarif_total);
    
        return $tarif_total;
    }    
    
}