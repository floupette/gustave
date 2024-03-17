import "./connexion.css"; // Assurez-vous que le chemin est correct
import logoGustave from "../../assets/lesvacanceschezgustave.png"; // Assurez-vous que le chemin est correct
import { Link, useNavigate } from "react-router-dom";
import { useState } from "react";
import { useContext } from "react";
import UserContext from "../../Context/userContext";

const Connexion = () => {
    const navigate = useNavigate();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const { user, setUser } = useContext(UserContext); // Utilisez la destructuration pour obtenir directement l'utilisateur


    const handleSubmit = async (e) => {
        e.preventDefault(); // Empêche le rechargement de la page lors de la soumission du formulaire

        // Préparation des données de connexion à envoyer
        const loginData = {
            email: email,
            password: password,
        };

        try {
            // Envoi des données de connexion au serveur
            const response = await fetch('http://localhost:3630/auth/login', {
                credentials: 'include',
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(loginData),
            });

            if (response.ok) {
                const data = await response.json();
                console.log(data);
                setUser(data);
                // Si la connexion est réussie, naviguer vers '/home'
                navigate('/');
                // Vous pourriez également vouloir sauvegarder les informations de l'utilisateur ou le token de session ici
            } else {
                // Si la connexion échoue, afficher un message d'erreur
                // Cela dépend de la structure de votre réponse d'erreur
                alert(response.message || 'Une erreur est survenue lors de la connexion.');
            }
        } catch (error) {
            // En cas d'erreur dans le processus de fetch
            console.error('Erreur lors de la connexion : ', error);
            alert('Une erreur est survenue lors de la connexion.');
        }
    };


    return (

        <div className="login">
            <div className="cardLogin">
                <Link to={'/'}>
                    <img className="logoLogin" src={logoGustave} alt="logo" />
                </Link>
                <form className="loginForm" onSubmit={handleSubmit}>
                    <input type="email" value={email} placeholder="Email" onChange={(e) => setEmail(e.target.value)} required />
                    <input type="password" value={password} placeholder="Password" onChange={(e) => setPassword(e.target.value)} required />
                    <button type="submit" className="btnLogin">Se connecter</button>
                </form>
            </div>
        </div>
    );
};

export default Connexion;
