import Header from './header/Header'
import Content from './content/Content'
import Footer from './footer/Footer'
import Sidebar from './sidebar/Sidebar'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faStar } from '@fortawesome/free-solid-svg-icons'


const Page = props => {
    return (
        <>
            <Header>
                <FontAwesomeIcon icon={faStar} />&nbsp;
                    <h1>{props.header ?? 'Music Rating App'}</h1>
                &nbsp;<FontAwesomeIcon icon={faStar} />
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