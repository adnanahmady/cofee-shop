import {
  Navbar,
  Container
} from "react-bootstrap";
import App from '../../config/app';

const Template = () => {
  const home = App.baseUrl;

  return (
    <Navbar
      expand="lg"
      className="bg-dark text-bg-dark shadow mb-5"
      data-bs-theme={App.theme}
    >
      <Container>
        <Navbar.Brand
          href={home}
          className="text-bg-dark"
        >
          Rock Star
        </Navbar.Brand>
      </Container>
    </Navbar>
  );
};

export default Template;
