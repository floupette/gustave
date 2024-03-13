import SearchBar from "../../components/Searchbar/SearchBar";
import "./home.css"


const Home = () => {

    return (
        <>
            <div className="image-presentation">
                <div className="blur-background">
                <div className="text-overlay">
                “Explorez au-delà des frontières, là où chaque instant sculpte un souvenir, et chaque lieu révèle une nouvelle perspective sur la vie”
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