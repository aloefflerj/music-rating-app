import api from './axiosConfig'

const getAllStarredSongs = () =>
    api.get('stars/songs').then(res => {
        return res
    })

const getStarredSong = id =>
    api.get('stars/songs/' + id).then(res => {
        return res
    })

const starASong = data =>
    api.post('stars/songs', JSON.stringify(data)).then(res => {
        return res
    })

const updateSongStars = (id, data) =>
    api.put('stars/songs/' + id, JSON.stringify(data)).then(res => {
        return res
    })

// const deleteSong = id =>
//     api.delete('songs/' + id).then(res => {
//         return res
//     })

export { getAllStarredSongs, getStarredSong, starASong, updateSongStars }