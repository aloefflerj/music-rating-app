import React from 'react'
import EntityContent from '../common/EntityContent'
import Page from '../template/Page'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faMusic } from '@fortawesome/free-solid-svg-icons'


const Home = props => {
    return (
        <Page handleLogout={props.handleLogout}
        logged={props.logged}
        subHeader={'Welcome, ' + props.logged.username}
        content={
            <EntityContent 
            
            >
                
                <p>
                <FontAwesomeIcon icon={faMusic} /> Start starring your songs, albums and artists<FontAwesomeIcon icon={faMusic} /></p>
                
            </EntityContent>
        }
        />
    )
}

export default Home