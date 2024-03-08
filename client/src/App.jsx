import { useEffect, useState } from "react"
import { Route, Routes } from "react-router-dom"
import Navbar from "./components/Navbar/Navbar/Navbar.jsx"
import UserContext from "./Context/userContext.js"
import Connexion from "./pages/Home/Connexion/Connexion.jsx"

const NavRouter = () => {

    const [user, setUser]= useState()
    
    useEffect(()=>{
      
      fetch ('http://localhost:3630/auth/info',{credentials: 'include'})
      .then(response=>response.json)
      .then(data =>
        setUser(data))
        .catch(error=>
          console.log(error))
    },[]);

    return (
      <UserContext.Provider value={{user, setUser}}>
          <Navbar />
            <Routes>
              
              <Route path='/connexion' element={<Connexion/>} />
              <Route path='/nous' element={<h1>Nous</h1>} />
              
            </Routes>
          </UserContext.Provider>
    )
}

export default NavRouter;