import React, { useEffect, useState } from 'react'
import Page from '../../template/Page'
import { Link, Outlet } from 'react-router-dom'
import { getSongs as getSongsApi } from '../../routes/songRoutes'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faTrash } from '@fortawesome/free-solid-svg-icons'
import { Button } from 'reactstrap'

const Songs = props => {

    const [songs, setSongs] = useState()

    useEffect(() => {
        getAllSongs()
    }, [])

    const getAllSongs = async () => {
        const res = getSongsApi()
        const songsResponse = (await res).data
        setSongs(
            songsResponse.map(song => 
                <>
                <div key={song.id} className='songs'>
                    <Link to={`${song.id}`}> <h6>{song.title}</h6> </Link>
                    {props.logged.user_type === 'adm' ? <Button color='danger'><FontAwesomeIcon icon={faTrash} /></Button> : null}
                </div>
                </>
            )
        )
    }


    return (
        <Page handleLogout={props.handleLogout}
            logged={props.logged}
            subHeader={'Songs'}
            content={
                <>
                {songs}
                </>
            }

        />

    )
}

export default Songs