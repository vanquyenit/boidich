import {Navbar, Nav, Container, NavDropdown} from 'react-bootstrap'
import {Link, useHistory} from 'react-router-dom'
import React from 'react'

function Header()
{
    let user = JSON.parse(localStorage.getItem("user-info"))
    const history = useHistory()
    function logOut(){
        localStorage.clear()
        history.push("/login")
    }
    return (
        <div>
            <Navbar bg="dark" variant="dark">
                <Container>
                    <Navbar.Brand href="#home">Dashboard</Navbar.Brand>
                    <Nav className="me-auto header-link">
                        {
                            localStorage.getItem("user-info") ?
                            <>
                                <Link to="/news-list">News list</Link>
                                <Link to="/add-news">add news</Link>
                                <Link to="/edit-news">edit news</Link>
                            </>
                            :
                            <>
                                <Link to="/login">Login</Link>
                                <Link to="/register">Register</Link>
                            </>
                        }
                    </Nav>
                    {localStorage.getItem("user-info") ? 
                    <Nav>
                        <NavDropdown title={user && user.name}>
                            <NavDropdown.Item onClick={logOut}>Logout</NavDropdown.Item>
                        </NavDropdown>
                    </Nav>
                    : null }
                </Container>
            </Navbar>
        </div>
    )
}
export default Header;