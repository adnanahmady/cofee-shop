import {
  Navbar,
  Container,
  Nav
} from "react-bootstrap";
import App from "../../config/app";
import { LOGIN_LINK as MANAGERS_LOGIN_LINK } from "../../routes/managers";

const Template = () => {
  const home = App.baseUrl;
  const themeMode = App.mode;

  return (
    <Navbar
      expand="lg"
      className={"shadow mb-5 bg-" + themeMode}
      variant={themeMode}
    >
      <Container>
        <Navbar.Brand href={home}>Rock Star</Navbar.Brand>

        <Nav className="ms-auto">
          <Nav.Link href={MANAGERS_LOGIN_LINK}>Login as a manager</Nav.Link>
        </Nav>
      </Container>
    </Navbar>
  );
};

export default Template;
