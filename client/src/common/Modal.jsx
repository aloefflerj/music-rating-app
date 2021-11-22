import React, { useState } from 'react'
import {
    Alert,
    Button,
    Modal,
    ModalHeader,
    ModalBody,
    Form,
    Row,
    Col,
} from 'reactstrap'
import FormItem from './FormItem'

import { newUser, updateUser } from '../routes/routes'

import './modal.css'

const ModalTemplate = props => {
    const { buttonLabel, className, title, method, action, userInfo } = props

    const [modal, setModal] = useState(false)

    const [error, setError] = useState({
        status: false,
        message: {
            type: '',
            fields: {
                name: '',
                mail: '',
            },
            content: '',
        },
    })

    const [user, setUser] = useState({
        user: {
            id: '',
            name: '',
            mail: '',
            phone: '',
            birth: '',
            city: '',
        },
    })

    const toggle = () => handleModal(!modal)

    const handleModal = () => {
        setModal(!modal)
        setUser(userInfo ?? user)
        //reload dos usuários
        setError({
            status: false,
            message: {
                type: '',
                fields: {
                    name: '',
                    mail: '',
                },
                content: '',
            },
        })
    }

    const handleChange = e => {
        const { name, value } = e.target
        setUser(() => ({
            ...user,
            [name]: value,
        }))
    }

    const clearFields = e => {
        e.preventDefault()
        setUser({
            ...user,
            name: '',
            mail: '',
            phone: '',
            birth: '',
            city: '',
        })
    }

    const handleSubmit = async e => {
        e.preventDefault()
        const { name, mail, phone, birth, city } = user
        let response = {}
        let res
        switch (props.method) {
            case 'post':
                res = newUser({
                    name,
                    mail,
                    phone,
                    birth,
                    city,
                })
                response = (await res).data
                break
            case 'put':
                res = updateUser(user.id, {
                    name,
                    mail,
                    phone,
                    birth,
                    city,
                })
                response = (await res).data
                break
            default:
                displayMessage('')
        }
        if (response.message) {
            setError({ ...error, status: true, message: response.message })
            return
        }
        props.init()
        toggle()
    }

    const displayMessage = msg => {
        console.log(msg)
    }

    const closeBtn = (
        <Button color='white' onClick={toggle}>
            <span color='muted'>&#x2715;</span>
        </Button>
    )

    return (
        <div>
            <Button color='btn btn-dark' onClick={toggle}>
                {buttonLabel}
            </Button>
            <Modal isOpen={modal} toggle={toggle} className={className}>
                <ModalHeader toggle={toggle} close={closeBtn}>
                    <strong>{title}</strong>
                </ModalHeader>
                <ModalBody>
                    <Form>
                        <FormItem
                            name='name'
                            label='Nome:'
                            value={user.name}
                            action={handleChange}
                            invalid={error.message.fields.name ?? ''}
                            required={'Obrigatório'}
                            feedBack={
                                error.message.fields.name
                                ? error.message.content
                                : ''
                            }
                            />
                        <FormItem
                            name='mail'
                            label='Email:'
                            value={user.mail ?? ''}
                            action={handleChange}
                            invalid={error.message.fields.mail ?? ''}
                            required={'Obrigatório'}
                            feedBack={
                                error.message.fields.mail
                                    ? error.message.content
                                    : ''
                            }
                        />
                        <Row>
                            <Col md={6}>
                                <FormItem 
                                    type='phone'
                                    name='phone'
                                    label='Telefone:'
                                    value={user.phone ?? ''}
                                    action={handleChange}
                                />
                            </Col>
                            <Col md={6}>
                                <FormItem
                                    type='date'
                                    name='birth'
                                    label='Data de nascimento:'
                                    value={user.birth ?? ''}
                                    action={handleChange}
                                />
                            </Col>
                        </Row>
                        <FormItem
                            name='city'
                            label='Cidade onde nasceu:'
                            value={user.city ?? ''}
                            action={handleChange}
                        />
                        <Alert
                            color='danger'
                            isOpen={
                                error.message.type &&
                                !error.message.fields.mail &&
                                !error.message.fields.name
                            }
                        >
                            {error.message.content}
                        </Alert>
                        <Row form>
                            <Button color='dark' onClick={clearFields}>
                                Limpar
                            </Button>
                            <Button color='dark' onClick={handleSubmit}>
                                Enviar
                            </Button>
                        </Row>
                    </Form>
                </ModalBody>
            </Modal>
        </div>
    )
}

export default ModalTemplate
