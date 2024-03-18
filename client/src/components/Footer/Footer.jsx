import { Link } from 'react-router-dom';
import logoChezGus from '../../assets/lesvacanceschezgustave-logo.png'
import './footer.css'; // Ajoute cette ligne pour inclure le style CSS

const Footer = () => {
    return (
        <footer className="footer">
            <img src={logoChezGus} alt="logo Gustave" id='logo-footer' />
            <div className="container">
                <ul className="footer-links">
                    <li>
                        <Link to="/mentions-legales">Mentions légales</Link>
                    </li>
                    <li>
                        <Link to="/contact">Nous contacter</Link>
                    </li>
                    <li>
                        <Link to="/plan-du-site">Plan du site</Link>
                    </li>
                </ul>
            </div>
        </footer>
    );
};

export default Footer;
