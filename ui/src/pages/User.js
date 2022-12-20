import Axios from 'axios'
import { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom'

import Swal from 'sweetalert2/dist/sweetalert2.js'
import 'sweetalert2/dist/sweetalert2.all'

export default function User(props){
    const [user, setUser] = useState({
        active: 1,
        avatar: null,
        email: null,
        email_verified_at: null,
        id: null,
        name: null,
        updated_at: null,
        created_at : null,
        created_by: null,
        permissions: {users:false, vehicles:true},
    })
    const [file, setFile] = useState()
    const [errors, setErros] = useState()
    const [permissions, setPermissions] = useState([])

    const navigate = useNavigate()

    let { id } = useParams();

    const getUser = () => {
        Axios.get(`${process.env.REACT_APP_API}/users/${id}`)
        .then(({data}) => {
            data.user.permissions = JSON.parse(data.user.permissions)
            setUser(data.user)
        })
        .catch(() => setUser(null))
    }

    const saveUser = (e) => {
        e.preventDefault()

        const formData = new FormData();
        if(user.id) formData.append("id", user.id)
        formData.append("name", user.name)
        formData.append("password", user.password)
        formData.append("email", user.email)
        formData.append("active", user.active)
        formData.append("permissions", user.permissions)
        formData.append("avatar", file);

        Axios.post(`${process.env.REACT_APP_API}/users`, formData)
        .then(({data}) => {
            data.user.permissions = JSON.parse(data.user.permissions)
            setUser(data.user)

            Swal.fire({
                title: 'Sucesso!',
                text: 'Usuário salvo com Sucesso!',
                icon: 'success',
                confirmButtonText: 'OK'
            })

            navigate('/users')
        })
        .catch((err) => {
           setErros(err.response.errors) 
        })
    }

    const getPermissions = () => {
        Axios.get(`${process.env.REACT_APP_API}/users/permissions`)
        .then(({data}) => setPermissions(data))
    }


    useEffect(() => getPermissions, [])
    useEffect(() => getUser, [])

    return (
        <form onSubmit={saveUser} className="row g-3">
            <div className="col col-md-6">
                <label for="inputName" className="form-label">Nome</label>
                <input 
                    type="text" 
                    className="form-control" 
                    id="inputName" 
                    value={user.name}
                    onChange={(e) => setUser({...user, name: e.target.value})}
                />
            </div>
            <div className="col col-md-6">
                <label for="inputEmail" className="form-label">Email</label>
                <input 
                    type="email" 
                    className="form-control" 
                    id="inputEmail" 
                    value={user.email}
                    onChange={(e) => setUser({...user, email: e.target.value})}
                />
            </div>
            <div className="col col-md-6">
                <label for="inputPassword" className="form-label">Password</label>
                <input 
                    type="password" 
                    className="form-control" 
                    id="inputPassword" 
                    value={user.password}
                    onChange={(e) => setUser({...user, password: e.target.value})}
                />
            </div>
            <div className="col col-md-6">
                <label for="inputAvatar" className="form-label">Avatar</label>
                <input 
                    type="file" 
                    className="form-control" 
                    id="inputAvatar"
                    value={user.avatar}
                    onChange={(e) => {
                        setFile(e.target.files[0])
                        // setUser({...user, avatar: e.target.files[0]})
                    }}
                />
            </div>
            <div className="col offset-md-6 col-md-6">
                <label for="inputPermissions" className="form-label">Permissões</label>
                <br />

                {Object.keys(permissions).map((key) => (
                    <>
                        <span for={key} className="form-labe">{permissions[key]}</span> &nbsp;
                        <input 
                            type="checkbox" 
                            value={key} 
                            name={key} 
                            checked={user.permissions[key]}
                            onChange={(e) => {
                                let permissions = Object.assign({}, user.permissions)
                                permissions[key] = e.target.checked
                                setUser({...user, permissions: {...user.permissions, ...permissions}})
                            }}
                        />
                        &nbsp;&nbsp;
                    </>
                ))}

            </div>
            <div className="col-12">
                <button type="submit" className="btn btn-primary">Salvar</button>
            </div>
        </form>
    )
}