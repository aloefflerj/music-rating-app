import React, { useState } from 'react'
import Page from '../template/Page'
import { Button, Input } from 'reactstrap'
import { login } from '../routes/authRoutes'
import { Link } from 'react-router-dom'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faUser, faStar } from '@fortawesome/free-solid-svg-icons'


const Login = props => {

    const [login, setLogin] = useState({username: '', passwd: ''})
    

    const handleChange = e => {
        const { name, value } = e.target
        setLogin(() => ({
            ...login,
            [name]: value
        }))
    }

    const handleSubmit = data => {
        props.loginSubmit(login)
    }

    return (
        <Page content={
            <div className='form'>
                <Input name='username' placeholder='Login' value={login.username} onChange={handleChange}/>
                <Input type='password' name='passwd' placeholder='Senha' value={login.passwd} onChange={handleChange}/>
                <Button onClick={handleSubmit} color='success'>
                    Entrar
                </Button>
            </div>
        }
        subHeader='Login'
        sidebar={false}
        headerLink={
            <Link to='/register'>
                <Button color='white'><FontAwesomeIcon icon={faUser} />&nbsp; Register</Button>
            </Link>
        }
        />
    )
}

export default Login