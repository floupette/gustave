import SearchBar from "../../components/Searchbar/SearchBar";
import "./home.css"


const Home = () => {

    return (
        <>
            <div className="image-presentation">
                <div className="blur-background">
                    <div className="text-overlay">
                        <p> “Explorez au-delà des frontières,</p>
                        <p>là où chaque instant sculpte un souvenir,</p>
                        <p>et chaque lieu révèle une nouvelle perspective sur la vie.”</p>
                    </div>
                </div>

            </div>
            <div className="Search-bar">
                <SearchBar />
            </div>
            <div className="slider">
                
            </div>
        </>
    );
};
export default Home;