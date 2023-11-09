import {
  Navbar,
  Container
} from "react-bootstrap";
import App from '../../config/app';

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
