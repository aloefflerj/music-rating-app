import api from './axiosConfig'

const register = data =>
    api.post('auth/register', JSON.stringify(data)).then(res => {
        return res
    })

const login = data =>
    api.post('auth/login', JSON.stringify(data)).then(res => {
        return res
    })


const logout = data =>
    api.post('auth/logout', JSON.stringify(data)).then(res => {
        return res
    })

const logged = () =>
    api.get('auth/logged').then(res => {
        return res
    })

export { register, login, logout, logged }