import React from 'react'
import './header.css'
import logo from '../../assets/img/logo.png'

const Header = props => {
    return (
        <header className='header'>
            <div className='upper-header'>
                <img src={logo} alt='contato-seguro-logo' />
            </div>
            <div className='lower-header'>{props.children}</div>
        </header>
    )
}

export default Header
