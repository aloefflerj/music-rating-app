import React, { Component } from 'react'
import './App.css'
import { getUsers } from './routes/routes'
import { Routes, Route, Link, Navigate } from 'react-router-dom'
import If from './common/If'
import Ooops from './pages/Ooops'
import Login from './pages/Login'
import Home from './pages/Home'

class App extends Component {
    constructor(props) {
        super(props)
        this.state = {
            users: [],
            logged: false
        }
    }
    
    async componentDidMount() {
        await this.init()
    }

    init = async () => {
        const res = getUsers()
        const usersResponse = (await res).data
        console.log(this.state.logged)
        // this.setState({
        //     users: usersResponse,
        // })
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
                        <Route path='/login' element={<Login />} />
                        <Route path='*' element={<Navigate replace to='/login' />} />
                    </Routes>
                </div>
            )
        }

        return (
            <div className='App'>
                <Routes>
                    <Route path='/' element={<Home />} />
                    <Route path='*' element={<Ooops />} />
                </Routes>
            </div>
        )
    }
}

export default App
