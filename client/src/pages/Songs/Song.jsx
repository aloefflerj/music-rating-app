import React, { useEffect, useState } from 'react'
import { useParams } from 'react-router'
import { getSong as getSongApi } from '../../routes/songRoutes'
import Page from '../../template/Page'
import { Rating } from 'react-simple-star-rating'
import { getStarredSong as getStarredSongApi, starASong, updateSongStars } from '../../routes/starRoutes'

const Song = props => {
    let params = useParams()

    const [song, setSong] = useState({})

    const [rating, setRating] = useState({})
    console.log(rating)

    useEffect(() => {
        getSong()
    }, [])

    useEffect(() => {
        getStarredSong()
    }, [])

    const getSong = async () => {
        const res = getSongApi(params.songId)
        const songResponse = (await res).data
        setSong(songResponse)
    }

    const getStarredSong = async () => {
        const res = getStarredSongApi(params.songId)
        const starredSongResponse = (await res).data
        console.log(Object.keys(rating).length)

        await starASong({
            song: parseInt(params.songId),
            stars: 0,
        })
        
        setRating(starredSongResponse[0])
    }

    const handleRating = async (rate) => {
        console.log(params.songId)
        // setRating({...rating, stars: rate})
        const res = updateSongStars(params.songId, { stars: rate })
        const updatedSongStarsResponse = (await res).data
        if (!updatedSongStarsResponse.msg) {
            setRating(updatedSongStarsResponse)
            console.log(updatedSongStarsResponse)
        }
    }

    const renderSongContent = () => {
        if (song && Object.keys(song).length > 0 && !song.msg) {

            return (
                <>
                    <img src='https://picsum.photos/200/200' alt={`${song.title}-image`} />
                    <h3>{song.title}</h3>
                    <p className='stars-label' >{rating !== undefined ? rating.label : ''}</p>
                    <div className='stars'>
                        <Rating onClick={handleRating} ratingValue={rating !== undefined ? rating.stars : 0} />
                    </div>
                </>
            )
        } else {
            return <p>{song.success ? '...' : <h3>{song.msg}</h3>}</p>
        }
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