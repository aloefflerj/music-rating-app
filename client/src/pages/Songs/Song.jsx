import React, { useEffect, useState } from 'react'
import { useParams } from 'react-router'
import { getSong as getSongApi} from '../../routes/songRoutes'
import Page from '../../template/Page'
import { Rating } from 'react-simple-star-rating'

const Song = props => {
    let params = useParams()

    const [song, setSong] = useState({})

    const [rating, setRating] = useState(0)

    useEffect(() => {
        getSong()
    }, [])

    const getSong = async () => {
        const res = getSongApi(params.songId)
        const songResponse = (await res).data
        setSong(songResponse)
    }

    const handleRating = (rate) => {
        setRating(rate)
    }

    const renderSongContent = () => {
        // props.logged ??
        return (
            <>
                <img src='https://picsum.photos/200/200' alt={`${song.title}-image`} />
                <h3>{song.title}</h3>
                <p className='stars-label' >Great</p>
                <div className='stars'>
                    <Rating onClick={handleRating} ratingValue={rating} />
                </div>
            </>
        )
    }

    return (
        <Page handleLogout={props.handleLogout}
        logged={props.logged}
        // subHeader={song ? song.title : 'A música que procura não existe'} 
        content={renderSongContent()}
        />
    )
}

export default Song