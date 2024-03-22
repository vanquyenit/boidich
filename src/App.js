import logo from './logo.svg';
import './App.css';
import { Button } from 'react-bootstrap';
import {BrowserRouter, Route} from 'react-router-dom';
import Login from './components/pages/users/Login';
import Users from './components/pages/users/index';
import Register from './components/pages/users/Register';
import AddUser from './components/pages/users/Add';
import EditUser from './components/pages/users/Edit';
import News from './components/pages/news/index';
import AddNews from './components/pages/news/Add';
import EditNews from './components/pages/news/Edit';
import Protected from './components/pages/Protected';
import TuTru from './components/pages/tutru/index';
import CreateQue from './components/pages/tutru/CreateQue';

function App() {
  return (
    <div className="App">
      <BrowserRouter>
        <Route path="/login">
            <Login />
        </Route>
        <Route path="/register">
            <Register />
        </Route>
        <Route path="/tu-tru">
            {/* <TuTru /> */}
            <Protected Cmp={TuTru} />
        </Route>
        <Route path="/tu-tru/que">
            {/* <CreateQue /> */}
            <Protected Cmp={CreateQue} />
        </Route>
        <Route path="/list-news">
            {/* <News /> */}
            <Protected Cmp={News} />
        </Route>
        <Route path="/add-news">
            {/* <AddNews /> */}
            <Protected Cmp={AddNews} />
        </Route>
        <Route path="/edit-news">
            {/* <EditNews /> */}
            <Protected Cmp={EditNews} />
        </Route>
      </BrowserRouter>
    </div>
  );
}

export default App;
