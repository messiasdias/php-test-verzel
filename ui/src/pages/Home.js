import Header from './../components/home/Header'
import Catalog from './../components/home/Catalog'
import About from './../components/home/About'
import Features from './../components/home/Features'
import Footer from './../components/home/Footer'

export default function Home(props) {
    return (
        <>
            <Header 
                access_token={props.access_token} 
                login={props.login} 
                logout={props.logout}
            />
            <Catalog />
            <About/>
            <Features/>
            <Footer/>
        </>
    );
}