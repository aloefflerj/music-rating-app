import React from 'react'
import './footer.css'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faMugHot } from '@fortawesome/free-solid-svg-icons'

const Footer = () => {
    return (
        <footer className='footer'>
            <span className='text-muted'>
                ~ Feito com muito&nbsp; <FontAwesomeIcon icon={faMugHot} />
                &nbsp; por Anderson ~
            </span>
        </footer>
    )
}

export default Footer
