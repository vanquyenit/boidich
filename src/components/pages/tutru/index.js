import { Col, Row, Form, Button } from 'react-bootstrap'
import React, { useState, useEffect } from 'react'
import { useHistory } from 'react-router-dom'
import Header from "../../layouts/Header";
import axios from 'axios'

class TuTru extends React.Component {
    constructor(props, context) {
        super(props, context);
        // const history = useHistory()

        this.state = { name: '2022', thang: 1, ngay: 1, gio: 1, phut: 1 };
        this.handleChange = this.handleChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleChange(event) {
        this.setState({ nam: event.target.value });
        this.setState({ thang: event.target.value });
        this.setState({ ngay: event.target.value });
        this.setState({ gio: event.target.value });
        this.setState({ phut: event.target.value });
    }

    handleSubmit(event){
        event.preventDefault();

        // let thoigian = "" + this.state.gio + ":" + this.state.phut + ":00";npm 
        // let nam = this.state.nam;
        // let thang = this.state.thang;
        // let ngay = this.state.ngay;
        // let items = { nam: nam, thang: thang, ngay: ngay, thoigian: thoigian }

        // const sendPostRequest = async () => {
        //     try {
        //         const resp = await axios.post('http://127.0.0.1:8000/api/tu-tru', items);
        //         console.log(resp.data);
        //     } catch (err) {
        //         // Handle Error Here
        //         console.error(err);
        //     }
        // };
        
        // sendPostRequest();
      }
  


    // async public function xxxx(items) {
    //     let result = await fetch("http://127.0.0.1:8000/api/tu-tru", {
    //         method: 'POST',
    //         body: JSON.stringify(items),
    //         headers: {
    //             'Content-Type': 'application/json',
    //             'Accept': 'application/json'
    //         }
    //     })

    //     result = await result.json()

    //     console.log(result);
    //     if (result && result.status) {
    //         localStorage.setItem("tu-tru-que", JSON.stringify(result))
    //         // history.push("/tu-tru/que")
    //         this.context.router.push("/tu-tru/que")
    //     }
    // }

    render() {
        return (
            <div>
                <Header />
                <div className="container">
                    <h1>TỨ TRỤ CHU DỊCH</h1>
                    <Form onSubmit={this.handleSubmit}>
                        <Form.Group as={Row} className="mb-3" controlId="formPlaintextEmail">
                            <Form.Label column sm="2">
                                Việc cần xem:
                            </Form.Label>
                            <Col sm="10">
                                <Col sm="10">
                                    <Row className="mb-3">
                                        <Form.Group as={Col} controlId="formGridZip">
                                            <Form.Control size="sm" type="text" />
                                        </Form.Group>
                                    </Row>
                                </Col>
                            </Col>
                        </Form.Group>
                        <Form.Group as={Row} className="mb-3" controlId="formPlaintextEmail">
                            <Form.Label column sm="2">
                                Ngày lập quẻ:
                            </Form.Label>
                            <Col sm="10">
                                <Col sm="10">
                                    <Row className="mb-3">
                                        <Form.Group as={Col} controlId="formGridState">
                                            <Form.Select size="sm" defaultValue="2022" value={this.state.name} onChange={this.handleChange}>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </Form.Select>
                                        </Form.Group>
                                        <Form.Group as={Col} controlId="formGridState" value={this.state.thang} onChange={this.handleChange}>
                                            <Form.Select size="sm" defaultValue="1">
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </Form.Select>
                                        </Form.Group>
                                        <Form.Group as={Col} controlId="formGridState" value={this.state.ngay} onChange={this.handleChange}>
                                            <Form.Select size="sm" defaultValue="1">
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </Form.Select>
                                        </Form.Group>
                                    </Row>
                                </Col>
                            </Col>
                        </Form.Group><Form.Group as={Row} className="mb-3" controlId="formPlaintextEmail">
                            <Form.Label column sm="2">
                                Giờ lập quẻ: 
                            </Form.Label>
                            <Col sm="10">
                                <Col sm="10">
                                    <Row className="mb-3">
                                        <Form.Group as={Col} controlId="formGridState">
                                            <Form.Select size="sm" defaultValue="2022" value={this.state.name} onChange={this.handleChange}>
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </Form.Select>
                                        </Form.Group>
                                        <Form.Group as={Col} controlId="formGridState" value={this.state.thang} onChange={this.handleChange}>
                                            <Form.Select size="sm" defaultValue="1">
                                                <option value="1">One</option>
                                                <option value="2">Two</option>
                                                <option value="3">Three</option>
                                            </Form.Select>
                                        </Form.Group>
                                    </Row>
                                </Col>
                            </Col>
                            <Button variant="primary" type='submit'>
                                test
                            </Button>
                        </Form.Group>
                    </Form>
                </div>
            </div>
        );
    }
}
export default TuTru;