import React from 'react'
import './content.css'

const Content = props => {
    return (
        <main className="content">
            {props.children}
        </main>
    )
}

export default Content