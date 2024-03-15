import "./profil.css";
import { useContext } from "react";
import UserContext from "../../Context/userContext";

const Profil = () => {

    const { user, setUser } = useContext(UserContext); 
    console.log(user);

    return (
        <div className="profil">
            <div className="profil_card">
                <div className="description">
                    <h3>{user?.name}</h3>
                    <p>Email :{user?.email}</p>
                    <p>Tel : {user?.tel}</p>
                </div>
                <div className="reservation">
                    <h3>Mes Réservations</h3>
                    {user?.reservations.map((reservation) => (
                        <div key={reservation.id}>
                            <p>{reservation?.logement_name}</p>
                            <p>Du {reservation.start_date} au {reservation.end_date}</p>
                            {/* Vous pouvez ajouter d'autres détails de réservation ici */}
                        </div>
                    ))}
                </div>
                <div className="rating">
                    <h3>Mes Commentaires</h3>
                    {user?.ratings.map((rating) => (
                        <div key={rating.id}>
                            <p>{rating?.logement_name}</p>
                            <p>Notes : {rating.rated}/10</p>
                            <p>Commentaire : {rating.text}</p>
                            {/* Vous pouvez ajouter d'autres détails de notation ici */}
                        </div>
                    ))}
                </div>
            </div>
        </div>
    );
};

export default Profil;