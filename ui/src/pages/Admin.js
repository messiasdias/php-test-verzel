import { useState, useEffect } from 'react';
import Axios from 'axios'

import './../assets/font-awesome-4.7.0/css/font-awesome.min.css'

export default function Admin(props) {
    const [dashboard, setDashboard] = useState({
        users: 0,
        vehicles: 0
    })

    const getDashboard = () => {
        Axios.get(`${process.env.REACT_APP_API}/dashboard`)
        .then(({data}) => setDashboard(data))
        .catch(() => setDashboard({users: 0, vehicles: 0}))
    }

    useEffect(getDashboard, [])

    return (
        <div data-sidebar="dark" className="auth-body-bg">
            {/* @if (session('status')) <script> localStorage.setItem('sessionStatus', "{{ session('status') }}"); </script> @endif */}

            <div id="layout-wrapper" className="container-fluid row">
                <div className="card text-center col-5 offset-1 col-md-4 offse-md-2">
                    <i className="card-img-top fa fa-user fa-5x"></i>
                    <div className="card-body">
                        <h2 >Veiculos</h2>
                        <h2 className="card-text">{dashboard.vehicles}</h2>
                    </div>
                    { props.user.permissions.vehicles ? <a href="/vehicles" className="btn btn-primary mb-3">Gerenciar</a> : ''}
                </div>

                 <div className="card text-center col-5 offset-1 col-md-4 offse-md-2">
                    <i className="card-img-top fa fa-car fa-5x"></i>
                    <div className="card-body">
                        <h2>Usuários</h2>
                        <h2 className="card-text">{dashboard.users}</h2>
                    </div>
                    { props.user.permissions.users ? <a href="/users" className="btn btn-primary mb-3">Gerenciar</a> : ''}
                </div>

            </div>

            {/* @include('cms.includes.footer') */}
        </div>
    );
}