import {
  Col,
  Container,
  Row
} from "react-bootstrap";
import Navbar from "./components/navbar";
import "./App.scss";

function App() {
  return (
    <Container>
      <Navbar />
      <Row>
        <Col>Written by:</Col>
        <Col>Adnan ahmady</Col>
      </Row>
      <Row>
        <Col>Rock Star Coffee Shop</Col>
      </Row>
    </Container>
  );
}

export default App;
