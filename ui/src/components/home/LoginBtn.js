import {useNavigate} from 'react-router-dom'

function LoginBtn (props) {
    const navigate = useNavigate()
    if(!props.access_token) {
        return <button onClick={() => navigate('/login')} >Entrar</button>
    }
    return <button onClick={props.logout}>Sair</button>
}

export default LoginBtn