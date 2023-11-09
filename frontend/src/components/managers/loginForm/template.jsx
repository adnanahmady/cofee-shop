import PropTypes from "prop-types";
import {
  Button,
  Card,
  Form
} from "react-bootstrap";
import ErrorMessage from "../../shared/errorMessage";
import FaIcon from "../../shared/faIcon";

const Template = ({
  email,
  password,
  onSetEmail,
  onSetPassword,
  emailError,
  passwordError,
  onSubmit,
  isSubmitting = false,
}) => {
  const handleKeyUp = (e) => {
    if (e.code === 'Enter') {
      onSubmit();
    }
  };

  return (
    <Card>
      <Card.Header>Login</Card.Header>
      <Card.Body>
        <Form.Group controlId="email">
          <Form.Label>Email</Form.Label>
          <Form.Control
            type="email"
            value={email}
            onChange={onSetEmail}
            onKeyUp={handleKeyUp}
          />
          <ErrorMessage message={emailError} />
        </Form.Group>

        <Form.Group controlId="password">
          <Form.Label>Password</Form.Label>
          <Form.Control
            type="password"
            value={password}
            onChange={onSetPassword}
            onKeyUp={handleKeyUp}
          />
          <ErrorMessage message={passwordError} />
        </Form.Group>
      </Card.Body>
      <Card.Footer>
        <Button variant="primary" onClick={onSubmit} disabled={isSubmitting}>
          {isSubmitting && <FaIcon icon="spinner" size="1x" pulse fixedWidth />}
          Login
        </Button>
      </Card.Footer>
    </Card>
  );
};

Template.propTypes = {
  email: PropTypes.string.isRequired,
  password: PropTypes.string.isRequired,
  onSetEmail: PropTypes.func.isRequired,
  onSetPassword: PropTypes.func.isRequired,
  emailError: PropTypes.string,
  passwordError: PropTypes.string,
  onSubmit: PropTypes.func.isRequired,
  isSubmitting: PropTypes.bool,
};

export default Template;
