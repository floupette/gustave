import { Link, useLocation } from "react-router-dom";
import "./resultat.css";

const Resultat = () => {
    //state pour récuperer le state du resultat dataFiltered SearchBar ligne 39
    const { state } = useLocation();

    return (
        <>
            {state.resultat && state.resultat.length > 0 && (
                <div className="resultats">
                    {state.resultat.map(logement => (
                        <Link to={'/detaillogement'} state={{detaillogement : logement}} key={logement.id} className="logement">
                            <h2>{logement.name}</h2>
                            <p>{logement.description}</p>
                            <div className="logement-images">
                                {logement.images.map((image, index) => (
                                    <img key={index} src={image} alt={`Logement ${logement.name}`} />
                                ))}
                            </div>
                            <p>A partir de {logement.tarif_bas}€</p>
                            {/* Affichez d'autres détails du logement ici */}
                        </Link>
                    ))}
                </div>
            )}
        </>
    );
}

export default Resultat;
