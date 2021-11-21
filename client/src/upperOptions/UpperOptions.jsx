import React, { useState } from 'react'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faPlus } from '@fortawesome/free-solid-svg-icons'
import { Input } from 'reactstrap'
import Modal from '../common/Modal'

const UpperOptions = props => {
    const [searchValue, setSearchValue] = useState('')
    const [selectedFilter, setSelectedFilter] = useState('name')

    const handleChange = (value, filter) => {
        setSearchValue(value)
        setSelectedFilter(filter)
        props.filter(value, filter)
    }

    return (
        <>
            <Modal
                buttonLabel={<FontAwesomeIcon icon={faPlus} />}
                method='post'
                userInfo='false'
                title='Inserir'
                init={props.init}
            />

            <Input
                placeholder='Buscar...'
                onChange={e => handleChange(e.target.value, selectedFilter)}
                value={searchValue}
            />

            <select
                className='form-select text-muted'
                aria-label='filtro'
                defaultValue={selectedFilter}
                onChange={e => handleChange(searchValue, e.target.value)}
                value={selectedFilter}
            >
                <option className='text-dark' value='name' >
                    Nome
                </option>
                <option className='text-dark' value='mail' >
                    E-mail
                </option>
                <option className='text-dark' value='phone' >
                    Telefone
                </option>
                <option className='text-dark' value='birth'>
                    Nascimento
                </option>
                <option className='text-dark' value='city'>
                    Cidade
                </option>
            </select>
        </>
    )
}

export default UpperOptions
