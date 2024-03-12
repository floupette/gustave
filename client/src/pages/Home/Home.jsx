
import SearchBar from "../../components/Searchbar/SearchBar";
import "./home.css"



const Home = () => {

    return (
        <>
            <div className="image-presentation">
                <div className="blur-background">
                    <div className="text-overlay">
                        Je peux écrire par-dessus effet de flou !
                    </div>
                </div>
            </div>
            <div className="Search-bar">
                <SearchBar />
            </div>
        </>
    );
};

export default Home;