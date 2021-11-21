import React from 'react'
import { Button } from 'reactstrap'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faEdit, faTrashAlt } from '@fortawesome/free-solid-svg-icons'
import Modal from '../common/Modal'
import { deleteUser } from '../routes/routes'
import moment from 'moment'

const UserRow = props => {
    const handleDelete = async e => {
        e.preventDefault()
        const res = deleteUser(props.id)
        const result = (await res).data
        props.init()
    }

    const formatDate = e => {
        return e ? moment(e).utcOffset('-03:00').format('DD/MM/YYYY') : ''
    }

    return (
        <>
            <tr key={props.id}>
                <td>{props.name}</td>
                <td>{props.mail}</td>
                <td>{props.phone}</td>
                <td>{formatDate(props.birth)}</td>
                <td>{props.city}</td>
                <td className='buttons'>
                    <Modal
                        buttonLabel={<FontAwesomeIcon icon={faEdit} />}
                        title='Editar'
                        method='put'
                        userInfo={props}
                        init={props.init}
                    />
                    <Button color='dark' onClick={handleDelete}>
                        <FontAwesomeIcon icon={faTrashAlt} />
                    </Button>
                </td>
            </tr>
        </>
    )
}

export default UserRow
