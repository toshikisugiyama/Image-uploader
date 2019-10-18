import React from 'react'
import PhotoList from '../components/PhotoList'
import Login from '../components/Login'
import {
  BrowserRouter as Router,
  Switch,
  Route,
  Link,
} from "react-router-dom";

const App = () => {
  return(
    <Router>
      <div>
        <nav>
          <ul>
            <li>
              <Link to="/">Photo List</Link>
            </li>
            <li>
              <Link to="/login">Login</Link>
            </li>
          </ul>
        </nav>
        <Switch>
          <Route path="/login">
            <Login />
          </Route>
          <Route path="/">
            <PhotoList />
          </Route>
        </Switch>
      </div>
    </Router>
  )
}
export default App
