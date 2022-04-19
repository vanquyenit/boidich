
import { Form, Button } from 'react-bootstrap'
import React, { useState, useEffect} from 'react'
import { useHistory } from 'react-router-dom'
import Header from "../../layouts/Header";

function Login()
{
    useEffect(()=>{
        if(localStorage.getItem("user-info")){
            history.push("/add-news")
        }
    }, [])
    const [email, setEmail] = useState("")
    const [password, setPassword] = useState("")
    const history = useHistory()

    async function login() {
        let items = { email, password }
        let result = await fetch("http://127.0.0.1:8000/api/login", {
            method: 'POST',
            body: JSON.stringify(items),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })

        result = await result.json()
        if(result && result.status){
            localStorage.setItem("user-info", JSON.stringify(result.response.data))
            history.push("/news-list")
        }
    }
    
    return(
        <div>
            <Header />
            <div className="container">
                <h1>Login page</h1>
                <Form>
                    <Form.Group className="mb-3" controlId="formBasicEmail">
                        <Form.Label>Email address</Form.Label>
                        <Form.Control type="email" onChange={(e) => setEmail(e.target.value)} placeholder="Enter email" />
                    </Form.Group>
                    <Form.Group className="mb-3" controlId="formBasicPassword">
                        <Form.Label>Password</Form.Label>
                        <Form.Control type="password" onChange={(e) => setPassword(e.target.value)} placeholder="Password" />
                    </Form.Group>
                    <Button variant="primary" onClick={login}>
                        Login
                    </Button>
                </Form>
            </div>
        </div>
    )
}

export default Login;