import './../../assets/css/styles.css'
import CarHeader from './../../assets/images/car-card.png'
import LoginBtn from './LoginBtn'

export default function Header(props){
    return (
        <header>
            <div className="content">
                <nav className="navbar container-fluid">
                    <p className="brand">car<strong>Talog</strong></p>
                    <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span className="navbar-toggler-icon"></span>
                    </button>
                    
                    <div className="collapse navbar-collapse" id="navbarNav">
                        <ul className="navbar-nav">
                            <li><a href="/">Início</a></li>
                            <li><a href="/#catalog">Catálogo</a></li>
                            <li><a href="#about">Sobre</a></li>
                            <li><a href="#features">Recursos</a></li>
                            <LoginBtn access_token={props.access_token} logout={props.logout} />
                        </ul>
                    </div>
                </nav>
                <div className="header-block">
                <div className="text">
                    <h2>O carro ideal para você</h2>
                    <p>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Officia,
                    asperiores?
                    </p>
                </div>
                <img src={CarHeader} alt="Car" />
                </div>
            </div>
        </header>
    )
}