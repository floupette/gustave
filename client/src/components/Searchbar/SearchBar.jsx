import React, { useState } from 'react';
import "./searchbar.css"

function SearchBar() {
    const [lieu, setLieu] = useState('');
    const [dateDepart, setDateDepart] = useState('');
    const [dateArrivee, setDateArrivee] = useState('');
    const [adultes, setAdultes] = useState(1); // Définissez une valeur par défaut
    const [enfants, setEnfants] = useState(0); // Définissez une valeur par défaut
    const [chambres, setChambres] = useState(1); // Définissez une valeur par défaut

    const handleSubmit = (e) => {
        e.preventDefault();
        // Ici, vous pouvez ajouter votre logique pour traiter la recherche
        console.log({ lieu, dateDepart, dateArrivee, adultes, enfants, chambres });
        // Par exemple, vous pouvez appeler une API pour récupérer des données basées sur ces valeurs
    };

    return (
        <form className="search-bar" onSubmit={handleSubmit}>
            <input
                type="text"
                placeholder="Où allez-vous ?"
                value={lieu}
                onChange={(e) => setLieu(e.target.value)}
            />
            <input
                type="date"
                placeholder="Départ"
                value={dateDepart}
                onChange={(e) => setDateDepart(e.target.value)}
            />
            <input
                type="date"
                placeholder="Arrivée"
                value={dateArrivee}
                onChange={(e) => setDateArrivee(e.target.value)}
            />
            <div className='nombres'>
            <input 
                type="number"
                placeholder="Nombre Adultes"
                value={adultes}
                onChange={(e) => setAdultes(e.target.value)}
                min="1"
            />
            <input
                type="number"
                placeholder="Nombre d'Enfants"
                value={enfants}
                onChange={(e) => setEnfants(e.target.value)}
                min="0"
            />
            <input
                type="number"
                placeholder="Nombre de chambres"
                value={chambres}
                onChange={(e) => setChambres(e.target.value)}
                min="1"
            />
            </div>
            <button type="submit" id='search'>Rechercher</button>
        </form>
    );
}

export default SearchBar;
