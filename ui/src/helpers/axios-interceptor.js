import axios from 'axios'

export default function setup(token, logout = () => {}) {
    axios.interceptors.request.use((config) => {
        if(token) config.headers.Authorization = `Bearer ${token}`
        return config
    }, (err) => {
        let errors = [401, 403]
        if ((err?.response?.status && errors.includes(err?.response?.status)) || !token) {
            logout()
            return Promise.reject(err)
        }
        return Promise.resolve(err?.response || err)
    });
}