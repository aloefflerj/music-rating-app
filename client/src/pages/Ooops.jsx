import React from 'react'
import Page from '../template/Page'
import { Link } from 'react-router-dom'
import { Button } from 'reactstrap'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faUser, faStar } from '@fortawesome/free-solid-svg-icons'

const Ooops = props => {
    return (
        <Page content={'ooops'} header={'Error 404'}
            headerLink={
                <Link to='/login'>
                    <Button color='white'><FontAwesomeIcon icon={faUser} />&nbsp;Login</Button>
                </Link>
            } 
            logged={props.logged}
            />
    )
}

export default Ooops