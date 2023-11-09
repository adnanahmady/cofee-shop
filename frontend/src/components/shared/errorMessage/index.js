import PropTypes from 'prop-types';
import Template from "./template";

const ErrorMessage = ({ message }) => {
  return message && <Template message={message} />;
};

ErrorMessage.propTypes = {
  message: PropTypes.string
};
 
export default ErrorMessage;