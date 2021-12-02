import React, { Component } from 'react'
import './App.css'
import { getUsers } from './routes/userRoutes'
import { Routes, Route, Navigate } from 'react-router-dom'
import Ooops from './pages/Ooops'
import Login from './pages/Login'
import Home from './pages/Home'
import { register, login, logout, logged } from './routes/authRoutes'
import Register from './pages/Register'
import Songs from './pages/Songs/Songs'
import Song from './pages/Songs/Song'
import Albums from './pages/Albums/Albums'
import Album from './pages/Albums/Album'

class App extends Component {
    constructor(props) {
        super(props)
        this.state = {
            users: [],
            logged: false
        }

        this.handleLoginSubmit = this.handleLoginSubmit.bind(this)
        this.handleLogout = this.handleLogout.bind(this)
    }
    
    async componentDidMount() {
        await this.init()
    }

    init = async () => {
        const res = getUsers()
        const loggedRes = logged()
        const usersResponse = (await res).data
        const loggedResponse = (await loggedRes).data
        this.setState({
            users: usersResponse,
            logged: loggedResponse
        })
    }

    handleRegisterSubmit = async data => {
        const res = register(data)
        const registerResponse = (await res).data
        if(registerResponse.success == false) {
            alert(registerResponse.msg)
            return
        }
        window.location.replace('http://localhost/login')
    }

    handleLoginSubmit = async data => {
        const res = login(data)
        const loginResponse = (await res).data
        if(loginResponse.success == false) {
            alert(loginResponse.msg)
            return
        }
        window.location.replace('http://localhost/')
    }
    
    handleLogout = async () => {
        const res = logout()
        const logoutResponse = (await res).data
        window.location.replace('http://localhost/login')
    }

    PrivateRoute({children}) {
        return logged ? children : <Navigate replace to='login' />
    }

    render() {

        return (
            <div className={this.state.logged ? 'App' : 'AppLogin'}>
                <Routes>
                        <Route path='/login' element={<Login loginSubmit={this.handleLoginSubmit}/>} />
                        <Route path='/register' element={<Register registerSubmit={this.handleRegisterSubmit}/>} />
                        <Route path='/' element={
                            <this.PrivateRoute>
                                    <Home handleLogout={this.handleLogout} logged={this.state.logged}/>
                            </this.PrivateRoute>
                            } 
                        />
                        <Route path='/songs' element={
                            <this.PrivateRoute>
                                    <Songs handleLogout={this.handleLogout} logged={this.state.logged}/>
                            </this.PrivateRoute>
                            } 
                        />
                        <Route path='/albums' element={
                            <this.PrivateRoute>
                                    <Albums handleLogout={this.handleLogout} logged={this.state.logged}/>
                            </this.PrivateRoute>
                            } 
                        />
                        <Route path='/songs/:songId' element={<Song logged={this.state.logged} handleLogout={this.handleLogout} />} />
                        <Route path='/albums/:albumId' element={<Album logged={this.state.logged} handleLogout={this.handleLogout} />} />
                        <Route path='*' element={<Ooops logged={this.state.logged} handleLogout={this.handleLogout} />} />
                </Routes>
            </div>
        )
    }
}

export default App
