import { useState, useEffect } from 'react';
import Footer from "../../components/Footer/Footer";
import SearchBar from "../../components/Searchbar/SearchBar";
import { Link } from 'react-router-dom';
import "./home.css";

const Home = () => {
    const [logements, setLogements] = useState([]);

    // Fonction pour mélanger et prendre trois logements de manière aléatoire
    const getRandomLogements = (logements) => {
        return logements.sort(() => 0.5 - Math.random()).slice(0, 3);
    };

    useEffect(() => {
        fetch('http://localhost:3630/logements', {credentials: 'include'})
            .then(response => response.json())
            .then(data => {
                setLogements(data);
            })
            .catch(error => {
                console.log('Erreur lors de la récupération des logements:', error);
                // Gère l'erreur comme nécessaire
            });
    }, []);

    // Choisis aléatoirement trois logements une fois les logements chargés
    const randomLogements = getRandomLogements(logements);

    return (
        <>
            <div className="image-presentation">
                <div className="blur-background">
                    <div className="text-overlay">
                        <p>“Explorez au-delà des frontières,</p>
                        <p>là où chaque instant sculpte un souvenir,</p>
                        <p>et chaque lieu révèle une nouvelle perspective sur la vie.”</p>
                    </div>
                </div>
            </div>
            <div className="Search-bar">
                <SearchBar />
            </div>
            <div className="coups-de-coeur">
                {randomLogements.map((logement, index) => (
                    logement.images.map((image, idx) => (
                        idx === 0 && (
                            <Link to={`/detaillogement`} state={{detaillogement : logement}} key={logement}>
                                <img src={image} alt={`Coup de coeur ${index}`} className="coup-de-coeur-image" />
                            </Link>
                        )
                    ))
                ))}
            </div>
        </>
    );
};

export default Home;
