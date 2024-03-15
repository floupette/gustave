import { useState, useEffect } from 'react';
import { useNavigate } from "react-router-dom";
import "./searchbar.css"

function SearchBar() {
    const [dateDepart, setDateDepart] = useState('');
    const [dateArrivee, setDateArrivee] = useState('');
    const [chambres, setChambres] = useState(1); // Définissez une valeur par défaut
    const [categories, setCategories] = useState([]);
    const [selectedCategory, setSelectedCategory] = useState('');
    const navigate = useNavigate();

    useEffect(() => {
        // Récupérer toutes les catégories disponibles du JSON
        const fetchCategories = async () => {
            try {
                const response = await fetch('http://localhost:3630/logements');
                const data = await response.json();
                const allCategories = [...new Set(data.map(logement => logement.categorie))];
                setCategories(allCategories);
            } catch (error) {
                console.error('Erreur lors de la récupération des catégories:', error);
            }
        };

        fetchCategories();
    }, []);

    const handleSubmit = async (e) => {
        e.preventDefault();
        const url = `http://localhost:3630/logements`;

        try {
            const response = await fetch(url);
            const data = await response.json();

            const filteredData = data.filter(logement => {
                const isChambreMatch = parseInt(logement.chambre) >= chambres;
                const isDateMatch = logement.reservations.every(reservation =>
                    new Date(reservation.end_date) < new Date(dateDepart) || new Date(reservation.start_date) > new Date(dateArrivee)
                );
                return isDateMatch && isChambreMatch && (selectedCategory === '' || logement.categorie === selectedCategory);
            });

            navigate('/resultat', { state: { resultat: filteredData }});
        } catch (error) {
            console.error('Erreur lors de la récupération des données:', error);
        }
    };
        
    return (
        <form className="Search-bar" onSubmit={handleSubmit}>
            <select className='where'
                value={selectedCategory}
                onChange={(e) => setSelectedCategory(e.target.value)}
            >
                <option value="">Ou Allez ?</option>
                {categories.map((category, index) => (
                    <option key={index} value={category}>{category}</option>
                ))}
            </select>
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
