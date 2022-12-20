function NavBar (){
    return (
        <nav className="navbar bg-light">
            <div className="container-fluid">
                <a className="navbar-brand" href="#">
                    <img 
                    src="https://getbootstrap.com/docs/5.2/assets/brand/bootstrap-logo.svg" 
                    alt="Bootstrap" width="30" height="24" />
                </a>
                <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span className="navbar-toggler-icon"></span>
                </button>
                <div className="collapse navbar-collapse" id="navbarNav">
                    <ul className="navbar-nav">
                        <li className="nav-item"><a className="nav-link" href="/">Home</a></li>
                        <li className="nav-item"><a className="nav-link" href="/users">Usuários</a></li>
                        <li className="nav-item"><a className="nav-link" href="/vehicles">Catálogo de Veículos</a></li>
                        <li className="nav-item"><a className="nav-link" href="/login">Login</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    )
}

export default NavBar