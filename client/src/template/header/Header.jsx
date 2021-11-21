import React from 'react'
import './header.css'

const Header = props => {
    return (
        <header className='header'>
            {props.children}
        </header>
    )
}

export default Header
