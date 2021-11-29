import React, { useEffect, useState } from 'react'
import Page from '../../template/Page'
import { Link, Outlet } from 'react-router-dom'
import { getAlbums as getAlbumsApi } from '../../routes/albumRoutes'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faTrash } from '@fortawesome/free-solid-svg-icons'
import { Button } from 'reactstrap'

const Albums = props => {

    const [albums, setAlbums] = useState()

    useEffect(() => {
        getAllAlbums()
    }, [])

    const getAllAlbums = async () => {
        const res = getAlbumsApi()
        const albumsResponse = (await res).data
        setAlbums(
            albumsResponse.map(album => 
                <>
                <div key={album.id} className='albums'>
                    <Link to={`${album.id}`}> <h6>{album.title}</h6> </Link>
                    {props.logged.user_type === 'adm' ? <Button color='danger'><FontAwesomeIcon icon={faTrash} /></Button> : null}
                </div>
                </>
            )
        )
    }


    return (
        <Page handleLogout={props.handleLogout}
            logged={props.logged}
            subHeader={'Albums'}
            content={
                <>
                {albums}
                </>
            }

        />

    )
}

export default Albums