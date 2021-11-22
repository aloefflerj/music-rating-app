import api from './axiosConfig'

const getUsers = () =>
    api.get('users').then(res => {
        return res
    })

const getUser = id =>
    api.get('users/' + id).then(res => {
        return res
    })

const newUser = data =>
    api.post('users', JSON.stringify(data)).then(res => {
        return res
    })

const updateUser = (id, data) =>
    api.put('users/' + id, JSON.stringify(data)).then(res => {
        return res
    })

const deleteUser = id =>
    api.delete('users/' + id).then(res => {
        return res
    })

export { getUsers, getUser, newUser, updateUser, deleteUser }