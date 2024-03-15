import { useEffect, useState } from "react";
import { useLocation } from "react-router-dom";

const Detail = () => {
    //state pour récuperer le state du resultat dataFiltered SearchBar ligne 39
    const { state } = useLocation();
    const [logement,setLogement]=useState([]);

    useEffect(() =>{
        fetch(`http://localhost:3630/logements/${state.detaillogement.id}`, {credentials: 'include'})
            .then(Resultat => Resultat.json())
            .then(Data => {
                setLogement(Data);
            })
            .catch(Error => {
                /* Permet de gérer l'erreur.
                  S'il y a une erreur d'authentification alors on redirige vers Login
                */
            })
    },[])

    return (
        <>
            {/* {logement && ( */}
                <div className="description">
                    <h3>{logement.name}</h3>
                    <h4>{logement.type}</h4>
                    <p>Ville : {logement.secteur}</p>
                    <p>Nombre de chambres : {logement.chambre}</p>
                    <div className="left-images">
                        <div className="logement-images">
                            {logement.images && logement.images.map((image, index) => (
                                <img key={index} src={image} alt={`Logement ${logement.name}`} />
                            ))}
                        </div>
                    </div>
                </div>
            {/* )} */}
        </>
    );
};

export default Detail;
