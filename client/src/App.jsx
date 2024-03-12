import Navbar from "./components/Navbar/Navbar/Navbar.jsx"
import UserContext from "./Context/userContext.js"
import Connexion from "./pages/Home/Connexion/Connexion.jsx"
import Home from "./pages/Home/Home.jsx"
import { useEffect, useState } from "react"
import { Route, Routes, useNavigate } from "react-router-dom"

const App = () => {
    // State pour gérer la data du fetch sur l'Utilisateur
    const [user, setUser] = useState()

    // UseNavigate pour renvoyer l'utilisateur vers login s'il n'est pas connecté
    const navigation = useNavigate()

    /*  Fetch pour obtenir toutes les infos de l'utilisateur
        Credentials permet d'envoyer le cookie SID (identification de session), 
        ça évite un conflit avec le Back pour les CORS
    */
    useEffect(() =>{
        fetch(`http://localhost:3630/auth/info`, {credentials: 'include'})
            .then(Resultat => Resultat.json())
            .then(Data => {
                setUser(Data);
            })
            .catch(Error => {
                /* Permet de gérer l'erreur.
                  S'il y a une erreur d'authentification alors on redirige vers Login
                */
                navigation('/connexion'); 
            })
    },[])

    return (
      <UserContext.Provider value={{user, setUser}}>
          <Navbar />
            <Routes>
              
              <Route path='/connexion' element={<Connexion/>} />
              <Route path='/nous' element={<h1>Nous</h1>} />
              <Route path='/home' element={<Home/>} />

            </Routes>
          </UserContext.Provider>
    )
}

export default App;
