// import './../assets/admin/css/bootstrap.min.css'
// import './../assets/admin/css/icons.min.css'
import './../assets/font-awesome-4.7.0/css/font-awesome.min.css'

import Axios from 'axios';
import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

import ModalDelete from './../components/Modal'
import ModalPermissions from './../components/Modal'
import ModalPermissionsContent from './../components/admin/ModalUserPermissions'
import Pagination from '../components/Pagination';

import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/dist/sweetalert2.all'

export default  function Users() {
    const [search, setSearch] = useState([])
    const [users, setUsers] = useState([])
    const [meta, setMeta] = useState({current_page: 1, last_page: 1})
    const [permissions, setPermissions] = useState([])
    const navigate = useNavigate()
    let modal = null
    
    const getUsers = (e = null, page = null) => {
        e?.preventDefault()

        let url = `${process.env.REACT_APP_API}/users`
        if (search?.length) url += `/search?search=${search}&`
        else url += '?'
        url += `page=${page || meta.current_page}`

        Axios.get(url)
        .then(({data}) => {
            setUsers(data.data)
            setMeta({
                current_page: data.current_page,
                last_page: data.last_page
            })
        })
        .catch((error) => {
            setUsers([])
            setMeta({
                current_page: 1,
                last_page: 1
            })
        })
    }

    const navegateTo = (e, to = '/') => {
        e.preventDefault()
        navigate(to)
    }

    const deleteUser = (e, id) => {
        e.preventDefault()

        Axios.delete(`${process.env.REACT_APP_API}/users`, {params: {id: id}})
        .then(() => {
            Swal.fire({
                title: 'Sucesso!',
                text: 'Usuário excluido com Sucesso!',
                icon: 'success',
                confirmButtonText: 'OK'
            })
            getUsers()
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

    const getPermissions = () => {
        Axios.get(`${process.env.REACT_APP_API}/users/permissions`)
        .then(({data}) => setPermissions(data))
    }

    const setUserPermissions = (id, permissions) => {
        Axios.post(`${process.env.REACT_APP_API}/users/permissions`, {id: id, permissions: permissions})
        .then(() => getUsers())
    }

    useEffect(() => getUsers, [])
    useEffect(() => getPermissions, [])

    return (
        <div data-sidebar="dark" className="auth-body-bg">
            {/* @if (session('status')) <script> localStorage.setItem('sessionStatus', "{{ session('status') }}"); </script> @endif */}

            <div id="layout-wrapper" className="container-fluid row">
       
                <Pagination meta={meta} onClick={getUsers} />

                <table className="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Email</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        {users.map((user, i) => (
                            <tr key={i} >
                                <th scope="row">{user.id}</th>
                                <td>
                                    <a href="#" onClick={(e) => navegateTo(e, `/users/${user.id}`)}>{user.name}</a>
                                </td>
                                <td>
                                    <a href="#" onClick={(e) => navegateTo(e, `/users/${user.id}`)}>{user.email}</a>
                                </td>
                                <td className="row">
                                    <div className="d-flex row">
                                        <a href="#" onClick={(e) => navegateTo(e, `/users/${user.id}`)}>
                                            <i className="card-img-top fa fa-edit text-info"></i>
                                        </a>
                                        <a 
                                            href="#"
                                            data-bs-toggle="modal" 
                                            data-bs-target={`#modalPermissionsUser${user.id}`}
                                            onClick={() => modal = `#modalPermissionsUser${user.id}` }
                                        >
                                            <i className="card-img-top fa fa-lock text-primary"></i>
                                        </a>
                                        <a 
                                            href="#"
                                            data-bs-toggle="modal" 
                                            data-bs-target={`#modalDeleteUser${user.id}`}
                                            onClick={() => modal = `#modalPermissionsUser${user.id}` }
                                        >
                                            <i className="card-img-top fa fa-trash text-danger"></i>
                                        </a>
                                    </div>
                                </td>

                                <ModalPermissions 
                                    id={`modalPermissionsUser${user.id}`}
                                    content={() => {
                                        return (<ModalPermissionsContent permissions={permissions} user={user} />)
                                    }}
                                    title={`Editar permissões do usuário`}
                                    handlerSubmit={(permissions) => {setUserPermissions(user.id, permissions)}}
                                />

                                <ModalDelete 
                                    id={`modalDeleteUser${user.id}`}
                                    title={`Excluir o usuário`}
                                    text={`Deseja realmente excluir o usuário ${user.name}?`}
                                    handlerSubmit={(e) => deleteUser(e, user.id)}
                                />
                            </tr>
                        ))}
                    </tbody>
                </table>

                <Pagination meta={meta} onClick={getUsers} />

            </div>

            {/* @include('cms.includes.footer') */}
        </div>
    );
}