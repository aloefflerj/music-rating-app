import axios from 'axios'

const api = axios.create({
    baseURL: 'http://localhost:8000/v1/',
    headers: { 'Content-Type': 'application/json' },
})

const getUsers = () =>
    api.get('users').then(res => {
        return res
    })

const newUser = data =>
    api.post('users', JSON.stringify(data)).then(res => {
        return res
    })

const updateUser = (id, data) =>
    api.put('user/' + id, JSON.stringify(data)).then(res => {
        return res
    })

const deleteUser = id =>
    api.delete('user/' + id).then(res => {
        return res
    })

export { getUsers, newUser, updateUser, deleteUser }
// export { getUsers }