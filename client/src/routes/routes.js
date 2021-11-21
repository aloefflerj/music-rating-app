import axios from 'axios'

const api = axios.create({
    baseURL: 'http://localhost:8000/v1/users',
    headers: { 'Content-Type': 'application/json' },
})

const getUsers = () =>
    api.get('').then(res => {
        return res
    })

const newUser = data =>
    api.post('', JSON.stringify(data)).then(res => {
        return res
    })

const updateUser = (id, data) =>
    api.put('/' + id, JSON.stringify(data)).then(res => {
        return res
    })

const deleteUser = id =>
    api.delete('/' + id).then(res => {
        // console.log(req)
        return res
    })

export { getUsers, newUser, updateUser, deleteUser }
