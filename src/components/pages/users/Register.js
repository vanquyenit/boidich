import { Form, Button } from 'react-bootstrap'
import React, { useState, useEffect} from 'react'
import { useHistory } from 'react-router-dom'
import Header from "../../layouts/Header";

function Register() {

    useEffect(()=>{
        if(localStorage.getItem("user-info")){
            history.push("/add-news")
        }
    }, [])

    const history = useHistory()
    const [name, setName] = useState("")
    const [email, setEmail] = useState("")
    const [password, setPassword] = useState("")

    async function singUp() {
        let items = { name, email, password }
        let result = await fetch("http://127.0.0.1:8000/api/register", {
            method: 'POST',
            body: JSON.stringify(items),
            headers: {
                'Content-Type': 'application/json'
            }
        })

        result = await result.json()
        if(result && result.status){
            history.push("/login")
        }
    }

    return (
        <div>
            <Header />
            <div className="container">
                <h1>Register page</h1>
                <Form>
                    <Form.Group className="mb-3" controlId="formBasicUserName">
                        <Form.Label>User Name</Form.Label>
                        <Form.Control type="text" onChange={(e) => setName(e.target.value)} placeholder="User Name" />
                    </Form.Group>
                    <Form.Group className="mb-3" controlId="formBasicEmail">
                        <Form.Label>Email address</Form.Label>
                        <Form.Control type="email" onChange={(e) => setEmail(e.target.value)} placeholder="Enter email" />
                    </Form.Group>
                    <Form.Group className="mb-3" controlId="formBasicPassword">
                        <Form.Label>Password</Form.Label>
                        <Form.Control type="password" onChange={(e) => setPassword(e.target.value)} placeholder="Password" />
                    </Form.Group>
                    <Button variant="primary" onClick={singUp}>
                        Sign Up
                    </Button>
                </Form>
            </div>

        </div>
    )
}

export default Register;