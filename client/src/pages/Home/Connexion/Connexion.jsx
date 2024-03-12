import "./connexion.css"; // Assurez-vous que le chemin est correct
import logoGustave from "../../../assets/lesvacanceschezgustave.png"; // Assurez-vous que le chemin est correct
import { useNavigate } from "react-router-dom";
import { useState } from "react";

const Connexion = () => {
    const navigate = useNavigate();
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');


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
                // Si la connexion est réussie, naviguer vers '/home'
                navigate('/home');
                // Vous pourriez également vouloir sauvegarder les informations de l'utilisateur ou le token de session ici
            } else {
                // Si la connexion échoue, afficher un message d'erreur
                // Cela dépend de la structure de votre réponse d'erreur
                alert(data.message || 'Une erreur est survenue lors de la connexion.');
            }
        } catch (error) {
            // En cas d'erreur dans le processus de fetch
            console.error('Erreur lors de la connexion : ', error);
            alert('Une erreur est survenue lors de la connexion.');
        }
    };


    return (
        <>
            <div>
                <img src={logoGustave} alt="logo" />
            </div>
            <form className="LoginForm column" onSubmit={handleSubmit}>
                <label className="flex">
                    Email:
                    <input type="email" value={email} onChange={(e) => setEmail(e.target.value)} required />
                </label>
                <label className="flex">
                    Mot de passe:
                    <input type="password" value={password} onChange={(e) => setPassword(e.target.value)} required />
                </label>
                <button type="submit">Se connecter</button>
            </form>
        </>
    );
};

export default Connexion;
