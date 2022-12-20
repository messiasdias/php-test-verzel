import './assets/css/styles.css'
import { useEffect, useState } from 'react'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'

import Home from './pages/Home'
import Login from './pages/Login'
import Admin from './pages/Admin'
import User from './pages/User'
import Users from './pages/Users'
import Vehicle from './pages/Vehicle'
import Vehicles from './pages/Vehicles'

import AxiosInterceptor from './helpers/axios-interceptor'

function App() {
    const [user, setUser] = useState(JSON.parse(localStorage.getItem('user')) || null);
    const [access_token, setAccessToken] = useState(localStorage.getItem('access_token') || false);
    const [remember, setRemember] = useState(localStorage.getItem('remember') || false);

    const requireAuth = (nextState, replace, next) => {
        if (!access_token) {
            replace({
                pathname: "/login",
                state: { nextPathname: nextState.location.pathname }
            });
        }
        return next();
    }

    const noRequireAuth = (nextState, replace, next) => {
        if (access_token) {
            return replace({
                pathname: "/",
                state: { nextPathname: nextState.location.pathname }
            });
        }
        return next();
    }

    const login = (data) => {
        data.user.permissions = JSON.parse(data.user.permissions)
        
        localStorage.setItem("access_token", data.access_token)
        localStorage.setItem("user", JSON.stringify(data.user))
        localStorage.setItem("remember", remember)

        setAccessToken(data.access_token)
        setUser(data.user)
        setRemember(remember)

        window.location.href = '/admin'
    }

    const logout  = () => {
        setAccessToken(false)
        localStorage.removeItem('access_token')

        if(!remember) {
            setUser(null)
            localStorage.removeItem('user')
            setRemember(false)
            localStorage.removeItem('remember')
        }
        window.location.href = '/'
    }


    useEffect(() => AxiosInterceptor(access_token, logout), [])

    return (
        <Router>
            <Routes>
                <Route path="/" element={<Home access_token={access_token} login={login} logout={logout} />} />
                <Route path="/login" element={<Login  access_token={access_token} login={login} logout={logout} />} onEnter={noRequireAuth} />
                <Route path="/users" element={<Users />} onEnter={requireAuth} />
                <Route path="/users/:id" element={<User />} onEnter={requireAuth} />
                <Route path="/vehicles" element={<Vehicles />} onEnter={requireAuth} />
                <Route path="/vehicles/:id" element={<Vehicle />} onEnter={requireAuth} />
                <Route path="/admin" element={<Admin user={user} />} onEnter={requireAuth} />
                <Route path="*" element={<Home access_token={access_token} login={login} logout={logout} />} />
            </Routes>
        </Router>
    );
}

export default App;
