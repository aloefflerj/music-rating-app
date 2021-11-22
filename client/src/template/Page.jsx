import Header from './header/Header'
import Content from './content/Content'
import Footer from './footer/Footer'
import Sidebar from './sidebar/Sidebar'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faSignOutAlt, faStar } from '@fortawesome/free-solid-svg-icons'
import { Button } from 'reactstrap'
import { Link } from 'react-router-dom'


const Page = props => {

    const handleLogout = () => props.handleLogout()
    

    return (
        <>
            <Header>
                <div className="hidden"></div>
                <div className='title'>
                    <FontAwesomeIcon icon={faStar} />&nbsp;
                    <h1>{props.header ?? 'Music Rating App'}</h1>
                    &nbsp;<FontAwesomeIcon icon={faStar} />
                </div>
                {
                    props.headerLink ??
                    <Button color='white' onClick={handleLogout}>
                        <FontAwesomeIcon icon={faSignOutAlt} />
                        &nbsp;Logout
                    </Button>
                }
            </Header>
            {props.sidebar ??
                <Sidebar>
                    <ul>
                        <li>item1</li>
                        <li>item2</li>
                        <li>item3</li>
                    </ul>
                </Sidebar>
            }
            <Content>
                {props.subHeader ?? <h3>{props.subHeader}</h3>}
                {props.content}
            </Content>
            <Footer />
        </>
    )
}

export default Page