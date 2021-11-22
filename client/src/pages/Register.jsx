import React, { useState } from 'react'
import Page from '../template/Page'
import { Button, Input } from 'reactstrap'
import { Link } from 'react-router-dom'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faUser, faStar } from '@fortawesome/free-solid-svg-icons'


const Register = props => {

    const [register, setRegister] = useState({username: '', mail: '', passwd: '', passwdConfirm: ''})
    

    const handleChange = e => {
        const { name, value } = e.target
        setRegister(() => ({
            ...register,
            [name]: value
        }))
    }

    const handleSubmit = () => {
        props.registerSubmit(register)
    }

    return (
        <Page content={
            <div className='form'>
                <Input name='username' placeholder='Login' value={register.username} onChange={handleChange}/>
                <Input name='mail' placeholder='Email' value={register.mail} onChange={handleChange}/>
                <Input type='password' name='passwd' placeholder='Senha' value={register.passwd} onChange={handleChange}/>
                <Input type='password' name='passwdConfirm' placeholder='Confirmação' value={register.passwdConfirm} onChange={handleChange}/>
                <Button onClick={handleSubmit} color='success'>
                    Entrar
                </Button>
            </div>
        }
        subHeader='Register'
        sidebar={false}
        headerLink={
            <Link to='/login'>
                <Button color='white'><FontAwesomeIcon icon={faUser} />&nbsp;Login</Button>
            </Link>
        }
        />
    )
}

export default Register