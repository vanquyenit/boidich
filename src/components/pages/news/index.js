import { Form, Button } from 'react-bootstrap'
import React, { useState, useEffect} from 'react'
import { useHistory } from 'react-router-dom'
import Header from "../../layouts/Header";

function News()
{
    useEffect(()=>{
        if(localStorage.getItem("user-info")){
            history.push("/list-news")
        }
    }, [])
    const [year, setYear] = useState("")
    const [month, setMonth] = useState("")
    const [day, setDay] = useState("")
    const history = useHistory()

    async function test() {
        let items = { year, month, day }
        let result = await fetch("http://127.0.0.1:8000/api/admin/create-que", {
            method: 'POST',
            body: JSON.stringify(items),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })

        result = await result.json()

        console.log(result);
        // if(result && result.status){
        //     localStorage.setItem("user-info", JSON.stringify(result.data))
        //     history.push("/news-list")
        // }
    }
    
    return(
        <div>
            <Header />
            <div className="container">
                <h1>List News</h1>
                <Form>
                    <Form.Group className="mb-3" controlId="formBasicyear">
                        <Form.Label>year</Form.Label>
                        <Form.Control type="text" onChange={(e) => setYear(e.target.value)} placeholder="year" />
                    </Form.Group>
                    <Form.Group className="mb-3" controlId="formBasicMonth">
                        <Form.Label>Month</Form.Label>
                        <Form.Control type="text" onChange={(e) => setMonth(e.target.value)} placeholder="month" />
                    </Form.Group>
                    <Form.Group className="mb-3" controlId="formBasicDay">
                        <Form.Label>Day</Form.Label>
                        <Form.Control type="text" onChange={(e) => setDay(e.target.value)} placeholder="day" />
                    </Form.Group>
                    <Button variant="primary" onClick={test}>
                    test
                    </Button>
                </Form>
            </div>
        </div>
    )
}

export default News;