import { useState } from 'react';
import { useNavigate } from "react-router-dom";
import "./searchbar.css"

// const export Searchbar () => {}
function SearchBar() {
    const [lieu, setLieu] = useState('');
    const [dateDepart, setDateDepart] = useState('');
    const [dateArrivee, setDateArrivee] = useState('');
    const [chambres, setChambres] = useState(1); // Définissez une valeur par défaut
    const navigate = useNavigate();

    const handleSubmit = async (e) => {
        e.preventDefault();
        // Ici, vous pouvez ajouter votre logique pour traiter la recherche
        const url = `http://localhost:3630/logements`;

        try {
            const response = await fetch(url);
            const data = await response.json();
            console.log(data); // Affiche les données récupérées

            // Filtrage des données en fonction des critères de recherche
            const filteredData = data.filter(logement => {
                const isSecteurMatch = logement.secteur.toLowerCase().includes(lieu.toLowerCase());
                const isChambreMatch = parseInt(logement.chambre) >= chambres;
                const isDateMatch = logement.reservations.every(reservation => 
                    new Date(reservation.end_date) < new Date(dateDepart) || new Date(reservation.start_date) > new Date(dateArrivee)
                );
                // Assurez-vous qu'au moins une réservation correspond aux dates.
                // const isDateMatch = logement.reservations.some(reservation => 
                //     new Date(reservation.start_date) <= new Date(dateDepart) &&
                //     new Date(reservation.end_date) >= new Date(dateArrivee)
                // );
                return isSecteurMatch && isDateMatch && isChambreMatch;
            });
            console.log(filteredData);

            navigate('/resultat', {state : { resultat : filteredData}})
        } catch (error) {
            console.error('Erreur lors de la récupération des données:', error);
        }
    };
        
    return (
        <form className="Search-bar" onSubmit={handleSubmit}>
            <input className='where'
                type="text"
                placeholder="Où allez-vous ?"
                value={lieu}
                onChange={(e) => setLieu(e.target.value)}
            />
            <input className='date'
                type="date"
                placeholder="Départ"
                value={dateDepart}
                onChange={(e) => setDateDepart(e.target.value)}
            />
            <input className='date'
                type="date"
                placeholder="Arrivée"
                value={dateArrivee}
                onChange={(e) => setDateArrivee(e.target.value)}
            />
                <input className='nombres'
                    type="number"
                    placeholder="Nombre de chambres"
                    value={chambres}
                    onChange={(e) => setChambres(e.target.value)}
                    min="1"
                />
            <button type="submit" id='search'>Rechercher</button>
        </form>
    );
}

export default SearchBar;
