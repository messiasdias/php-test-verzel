import Axios from 'axios';
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';

import Header from './../components/home/Header'
import Footer from './../components/home/Footer'

export default function Login(props) {
    const [email, setEmail] = useState("")
    const [password, setPassword] = useState("")
    const [remember, setRemember] = useState(false)

    const submitLogin = (e) => {
        e.preventDefault()

        Axios.post(`${process.env.REACT_APP_API}/auth/login`, {
            email: email,
            password: password
        })
        .then(({data}) => props.login(data))
        .catch(() => props.logout())
    }

    return (
        <>
            <Header 
                access_token={props.access_token} 
                login={props.login} 
                logout={props.logout}
            />

            <section className="login" id="login">
                <div className="content row">
                    <div className="col col-md-6 offset-md-3">
                        <div className="title-wrapper-catalog">
                            <p>Área de Membros</p>
                            <h3>Entrar</h3>
                        </div>

                        <form onSubmit={submitLogin}>
                            <div className="mb-3">
                                <label htmlFor="inputEmail" className="form-label">Endereço de Email</label>
                                <input 
                                    type="email" 
                                    className="form-control" 
                                    id="inputEmail" 
                                    aria-describedby="helpEmail" 
                                    value={email}
                                    onChange={(e) => setEmail(e.target.value)}
                                />
                                <div id="helpEmail" className="form-text">Nunca compartilharemos seu e-mail com mais ninguém.</div>
                            </div>
                            <div className="mb-3">
                                <label htmlFor="inputPwd" className="form-label">Senha</label>
                                <input 
                                    type="password" 
                                    className="form-control" 
                                    id="inputPwd" 
                                    value={password}
                                    onChange={(e) => setPassword(e.target.value)}
                                />
                            </div>
                            <div className="mb-3  form-check">
                                <input 
                                    type="checkbox" 
                                    className="form-check-input" 
                                    id="inputCheckRemember" 
                                    value={remember}
                                    onChange={(e) => setRemember(e.target.checked)}
                                />
                                <label className="form-check-label" htmlFor="inputCheckRemember">Lembrar de mim</label>
                            </div>
                            <button type="submit" className="mt-3 btn btn-primary">Entrar</button>
                        </form>
                    </div>
                </div>
            </section>

            <Footer />
        </>
    );
}