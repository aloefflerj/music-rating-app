import React from 'react'
import { Table } from 'reactstrap'
import UserRow from './UserRow'
import './user.css'

const UserTable = props => {

    const renderUsers = () => {
        return props.users.map(user => (
            <UserRow
                key={user.id}
                id={user.id}
                name={user.name}
                mail={user.mail}
                phone={user.phone}
                birth={user.birth}
                city={user.city}
                init={props.init}
            />
        ))
    }

        return (
            <>
                <Table responsive className='user-table'>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Nascimento</th>
                            <th>Cidade</th>
                            <th></th>
                        </tr>
                    </thead>
                    {/* {init()} */}
                    <tbody>{renderUsers()}</tbody>
                </Table>
            </>
        )
    
}

export default UserTable
