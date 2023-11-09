import PropTypes from "prop-types";
import FaIcon from "../faIcon";
import { Form } from "react-bootstrap";

const Template = ({ message }) => {
  return (
    <Form.Label>
      <FaIcon icon="warning" className="text-danger m-2" />
      <small>{message}</small>
    </Form.Label>
  );
};

Template.propTypes = {
  message: PropTypes.string,
};

export default Template;
