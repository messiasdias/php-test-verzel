import Axios from 'axios';
import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

import ModalDelete from './../components/Modal'
import Pagination from './../components/Pagination'

import './../assets/font-awesome-4.7.0/css/font-awesome.min.css'
import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/dist/sweetalert2.all'

export default  function Vehicles() {
    const [search, setSearch] = useState([])
    const [vehicles, setVehicles] = useState([])
    const [meta, setMeta] = useState({current_page: 1, last_page: 1})
    const [permissions, setPermissions] = useState([])
    const navigate = useNavigate()
    let modal = null

    const navegateTo = (e, to = '/') => {
        e.preventDefault()
        navigate(to)
    }

    const getVehicles = (e = null, page = null) => {
        e?.preventDefault()

        let url = `${process.env.REACT_APP_API}/vehicles`
        if (search?.length) url += `/search?search=${search}&`
        else url += '?'
        url += `page=${page || meta.current_page}`

        Axios.get(url)
        .then(({data}) => {
            setVehicles(data.data)
            setMeta({
                current_page: data.current_page,
                last_page: data.last_page
            })
        })
        .catch(() => {
            setVehicles([])
            setMeta({
                current_page: 1,
                last_page: 1
            })
        })
    }

    const deleteVehicle = (e, id) => {
        e.preventDefault()
        
        Axios.delete(`${process.env.REACT_APP_API}/vehicles`, {params: {id: id}})
        .then(() => {
            Swal.fire({
                title: 'Sucesso!',
                text: 'Veículo excluido com Sucesso!',
                icon: 'success',
                confirmButtonText: 'OK'
            })
            getVehicles()
        })
        .catch((err) => {
            let errors = err?.response?.data.errors
            if(errors) {
                Swal.fire({
                    title: 'Erro!',
                    text: errors?.id,
                    icon: 'error',
                    confirmButtonText: 'Fechar'
                })
            }
        })
    }

    useEffect(() => getVehicles, [])

    return (
        <div data-sidebar="dark" className="auth-body-bg">
            {/* @if (session('status')) <script> localStorage.setItem('sessionStatus', "{{ session('status') }}"); </script> @endif */}

            <div id="layout-wrapper" className="container-fluid row">
       
                <Pagination meta={meta} onClick={getVehicles} />
                <table className="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Modelo</th>
                            <th scope="col">Preço</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        {vehicles.map((vehicle, i) => (
                            <tr key={i} >
                                <th scope="row">{vehicle.id}</th>
                                <td>
                                    <a href="#" onClick={(e) => navegateTo(e, `/vehicles/${vehicle.id}`)}>{vehicle.name}</a>
                                </td>
                                <td>{vehicle.brand}</td>
                                <td>{vehicle.model}</td>
                                <td>R$ {vehicle.price.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) }</td>
                                <td className="row">
                                    <div className="d-flex row">
                                        <a href="#" onClick={(e) => navegateTo(e, `/vehicles/${vehicle.id}`) }>
                                            <i className="card-img-top fa fa-edit text-info"></i>
                                        </a>
                                        <a 
                                            href="#"
                                            data-bs-toggle="modal" 
                                            data-bs-target={`#modalDeleteVehicle${vehicle.id}`}
                                            onClick={() => modal = `#modalPermissionsVehicle${vehicle.id}` }
                                        >
                                            <i className="card-img-top fa fa-trash text-danger"></i>
                                        </a>
                                        
                                    </div>
                                </td>

                                <ModalDelete 
                                    id={`modalDeleteVehicle${vehicle.id}`}
                                    title={`Excluir o veículo`}
                                    text={`Deseja realmente excluir o veículo ${vehicle.name}?`}
                                    handlerSubmit={(e) => deleteVehicle(e, vehicle.id)}
                                />
                            </tr>
                        ))}
                    </tbody>
                </table>

                <Pagination meta={meta} onClick={getVehicles} />

            </div>

            {/* @include('cms.includes.footer') */}
        </div>
    );
}