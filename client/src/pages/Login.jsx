import React from 'react'
import Page from '../template/Page'
import { Forms, Input } from 'reactstrap'


const Login = props => {
    return (
        <Page content={
            <div className='form'>
                <Input placeholder='Login' />
                <Input placeholder='Senha' />
            </div>
        }
        subHeader='Login'
        sidebar={false}
        />
    )
}

export default Login