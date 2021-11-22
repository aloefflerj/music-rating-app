import api from './axiosConfig'

const getSongs = () =>
    api.get('songs').then(res => {
        return res
    })

const getSong = id =>
    api.get('songs/' + id).then(res => {
        return res
    })

const newSong = data =>
    api.post('songs', JSON.stringify(data)).then(res => {
        return res
    })

const updateSong = (id, data) =>
    api.put('songs/' + id, JSON.stringify(data)).then(res => {
        return res
    })

const deleteSong = id =>
    api.delete('songs/' + id).then(res => {
        return res
    })

export { getSongs, getSong, newSong, updateSong, deleteSong }