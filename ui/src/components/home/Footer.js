import Instagram from './../../assets/images/instagram.png'
import Facebook from './../../assets/images/facebook.png'

function Footer(){
    return (
        <footer>
            <div className="main">
                <div className="content footer-links">
                <div className="footer-company">
                    <h4>A empresa</h4>
                    <h6>Sobre</h6>
                    <h6>Contatos</h6>
                </div>
                <div className="footer-rental">
                    <h4>Locar</h4>
                    <h6>Autocondução</h6>
                    <h6>Com motorista</h6>
                    <h6>Ajuda</h6>
                </div>
                <div className="footer-social">
                    <h4>Permaneça conectado</h4>
                    <div className="social-icons">
                        <img src={Instagram} alt="Instagram" />
                        <img src={Facebook} alt="Facebook" />
                    </div>
                </div>
                <div className="footer-contact">
                    <h4>Contate Nos</h4>
                    <h6>+55 81 983538086</h6>
                    <h6>messiasdias.ti@gmail.com</h6>
                    <h6>R. Poços de Caldas 263, Capibaribe, São Lourenço da Mata - PE</h6>
                </div>
                </div>
            </div>
            <div className="last">Messias Dias 2022 - Use como quiser</div>
        </footer>
    )
}

export default Footer