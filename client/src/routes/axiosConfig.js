import axios from 'axios'

axios.defaults.withCredentials = true;

const api = axios.create({
    baseURL: 'http://localhost:8000/v1/',
    headers: { 'Content-Type': 'application/json', },
    withcredentials: true
})

export default api