import React, { Component } from 'react'
import './App.css'
import { getUsers } from './routes/userRoutes'
import { Routes, Route, Navigate } from 'react-router-dom'
import Ooops from './pages/Ooops'
import Login from './pages/Login'
import Home from './pages/Home'
import { register, login, logout, logged } from './routes/authRoutes'
import Register from './pages/Register'

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
        console.log(registerResponse.msg)
        if(registerResponse.success == false) {
            alert(registerResponse.msg)
            return
        }
        window.location.reload(false)
    }

    handleLoginSubmit = async data => {
        const res = login(data)
        const loginResponse = (await res).data
        if(loginResponse.success == false) {
            alert(loginResponse.msg)
            return
        }
        window.location.reload(false)
    }
    
    handleLogout = async () => {
        console.log('called from app')
        const res = logout()
        const logoutResponse = (await res).data
        window.location.reload(false)
    }

    // filter = (value, filter) => {
    //     if(value === ''){
    //         this.init()
    //     }
    //     const users = []
    //     this.state.users.map(user => {
    //         for (var property in user) {
    //             if (property === filter) {
    //                 const parsedUserValue = this.normalizeString(user[property])
    //                 const parsedInput = this.normalizeString(value)
    //                 if (parsedUserValue.match(parsedInput)) {
    //                     users.push(user)
    //                 }
    //             }
    //         }
    //     })
    //     this.setState({
    //         users: users
    //     })
    // }

    // normalizeString(str) {
    //     const lowerCase = str ? str.toLowerCase() : ''
    //     const parsed = lowerCase.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
    //     return parsed
    // }

    render() {

        if(!this.state.logged) {
            return (
                <div className='App'>
                    <Routes>
                        <Route path='/login' element={<Login loginSubmit={this.handleLoginSubmit}/>} />
                        <Route path='/register' element={<Register registerSubmit={this.handleRegisterSubmit}/>} />
                        <Route path='*' element={<Navigate replace to='/login' />} />
                    </Routes>
                </div>
            )
        }

        return (
            <div className='App'>
                <Routes>
                    <Route path='/' element={<Home handleLogout={this.handleLogout}/>} />
                    <Route path='/login' element={<Navigate replace to='/' />} />
                    <Route path='*' element={<Ooops />} />
                </Routes>
            </div>
        )
    }
}

export default App
