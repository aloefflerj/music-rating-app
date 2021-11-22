import React from 'react'
import Page from '../template/Page'


const Home = props => {
    return (
        <Page handleLogout={props.handleLogout}/>
    )
}

export default Home