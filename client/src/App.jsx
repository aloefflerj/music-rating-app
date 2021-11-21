import React, { Component } from 'react'
import './App.css'
import Header from './template/header/Header'
import Content from './template/content/Content'
import Footer from './template/footer/Footer'
import UserTable from './users/UserTable'
import UpperOptions from './upperOptions/UpperOptions'
import { getUsers } from './routes/routes'

class App extends Component {
    constructor(props) {
        super(props)
        this.state = {
            users: [],
        }
    }
    
    async componentDidMount() {
        await this.init()
    }

    init = async () => {
        console.log('called from App')
        const res = getUsers()
        const usersResponse = (await res).data
        this.setState({
            users: usersResponse,
        })
    }

    filter = (value, filter) => {
        if(value === ''){
            this.init()
        }
        const users = []
        this.state.users.map(user => {
            for (var property in user) {
                if (property === filter) {
                    const parsedUserValue = this.normalizeString(user[property])
                    const parsedInput = this.normalizeString(value)
                    if (parsedUserValue.match(parsedInput)) {
                        users.push(user)
                    }
                }
            }
        })
        this.setState({
            users: users
        })
    }

    normalizeString(str) {
        const lowerCase = str ? str.toLowerCase() : ''
        const parsed = lowerCase.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        return parsed
    }

    render() {
        return (
            <div className='App'>
                
                <Header>
                    <UpperOptions
                        init={this.init}
                        users={this.state.users}
                        filter={this.filter}
                    />
                </Header>
                <Content>
                    <UserTable
                        init={this.init}
                        users={this.state.users}
                        filter={this.filter}
                    />
                </Content>
                <Footer />
            </div>
        )
    }
}

export default App
