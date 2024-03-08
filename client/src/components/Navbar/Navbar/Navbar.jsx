import "./navbar.css";
import logoGustave from "../../../assets/lesvacanceschezgustave.png";
import { Link, useNavigate } from "react-router-dom";
import { useState, useEffect, useContext } from "react";
import UserContext from "../../../Context/userContext";

const Navbar = () => {
    
    const navigate = useNavigate();
    const user = useContext(UserContext)
    console.log(user)

    useEffect(()=>{

    },[]);

    const handleClick = (path) => {
       navigate(path)
     };

    return (
        <>
            <nav className="ma-navbar">
                {console.log()}
                <section className="left-navbar">
                    <Link to={'/home'}>
                    <img src={logoGustave} alt="logo-vacances-chez-gustave" />
                    </Link>
                </section>

                <div className="right-navbar">
                   { !user.user &&  <button onClick={handleClick('/connexion')}>
                        Se connecter
                    </button>}
                    <button onClick={handleClick('/nous')}>
                        Qui sommes-nous?
                    </button>
                    {user.user && <button onClick={handleClick('/connexion')}>
                        Déconnexion
                    </button>}
                    {user.user && <button onClick={handleClick('/profil')}>
                    <img src={`https://ui-avatars.com/api/?name=${user?.name}&background=random&color=fff&bold=true&name`} className="logoInitial" alt="Avatar User"/>
                    </button>}
                </div>
                
            </nav>
        </>
    );
};
export default Navbar;