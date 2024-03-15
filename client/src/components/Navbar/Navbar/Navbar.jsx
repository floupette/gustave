import "./navbar.css";
import logoGustave from "../../../assets/lesvacanceschezgustave.png";
import { Link, useNavigate, useLocation} from "react-router-dom";
import { useContext } from "react";
import UserContext from "../../../Context/userContext";

const Navbar = () => {
    const navigate = useNavigate();
    const { user, setUser } = useContext(UserContext); // Utilisez la destructuration pour obtenir directement l'utilisateur
    const location = useLocation();


    const handleClick = (path) => {
        navigate(path);
        
    };


    const handleLogout = () => {

        fetch('http://localhost:3630/auth/logout', { credentials: 'include', }) //inclus les cookies lors de la requete
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Déconnexion échouée - ${response.status}`);
                }
                setUser(null);
                navigate('/home');
            })
            .catch(error => {
                console.error('Erreur lors de la déconnexion :', error.message);
                // Gérer l'erreur, par exemple, afficher un message à l'utilisateur
            });
    };

    if (location.pathname === '/connexion') {
        return null; // Ne rend rien si le chemin est /connexion
    }


    return (
        <>
            <nav className="ma-navbar">
                <section className="left-navbar">
                    <Link to={'/'}>
                        <img src={logoGustave} alt="logo-vacances-chez-gustave" id="image-navbar" />
                    </Link>
                </section>

                <div className="right-navbar">
                    {!user?.id && <>
                        <button onClick={() => handleClick('/connexion')}>
                            Se connecter
                        </button>
                        <button onClick={() => handleClick('/nous')}>
                            Qui sommes-nous?
                        </button>
                    </>}
                    {user?.id && <>
                        <button onClick={() => handleClick('/nous')}>
                            Qui sommes-nous?
                        </button>
                        <button onClick={handleLogout}>
                            Déconnexion
                        </button>
                        <Link to={'/profil'}>
                            <img src={`https://ui-avatars.com/api/?name=${user?.name}&background=random&color=fff`} className="logoInitial" alt="Avatar User" />
                        </Link>
                    </>}
                </div>
            </nav>
        </>
    );
};
export default Navbar;

