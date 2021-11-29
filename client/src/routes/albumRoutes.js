import api from './axiosConfig'

const getAlbums = () =>
    api.get('albums').then(res => {
        return res
    })

const getAlbum = id =>
    api.get('albums/' + id).then(res => {
        return res
    })

const newAlbum = data =>
    api.post('albums', JSON.stringify(data)).then(res => {
        return res
    })

const updateAlbum = (id, data) =>
    api.put('albums/' + id, JSON.stringify(data)).then(res => {
        return res
    })

const deleteAlbum = id =>
    api.delete('albums/' + id).then(res => {
        return res
    })

export { getAlbums, getAlbum, newAlbum, updateAlbum, deleteAlbum }