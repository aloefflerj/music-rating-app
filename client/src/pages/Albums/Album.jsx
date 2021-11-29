import React, { useEffect, useState } from 'react'
import { useParams } from 'react-router'
import { getAlbum as getAlbumApi } from '../../routes/albumRoutes'
import Page from '../../template/Page'
import { Rating } from 'react-simple-star-rating'

const Album = props => {
    let params = useParams()

    const [album, setAlbum] = useState({})

    const [rating, setRating] = useState(0)

    useEffect(() => {
        getAlbum()
    }, [])

    const getAlbum = async () => {
        const res = getAlbumApi(params.albumId)
        const albumResponse = (await res).data

        setAlbum(albumResponse)
    }

    const handleRating = (rate) => {
        setRating(rate)
    }

    const renderAlbumContent = () => {
        // props.logged ??
        if (album && Object.keys(album).length > 0 && !album.msg) {
            return (
                <>
                    <img src='https://picsum.photos/200/200' alt={`${album.title}-image`} />
                    <h3>{album.title}</h3>
                    <p className='stars-label' >Great</p>
                    <div className='stars'>
                        <Rating onClick={handleRating} ratingValue={rating} />
                    </div>
                </>
            )
        } else {
            return <p>{album.success ? '...' : <h3>{album.msg}</h3>}</p>
        }
    }

    return (
        <Page handleLogout={props.handleLogout}
            logged={props.logged}
            // subHeader={song ? song.title : 'A música que procura não existe'} 
            content={renderAlbumContent()}
        />
    )
}

export default Album