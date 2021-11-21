import React from 'react'
import './sidebar.css'

const Sidebar = props => {
    return (
        <aside className='sidebar'>
            {props.children}
        </aside>
    )
}

export default Sidebar
