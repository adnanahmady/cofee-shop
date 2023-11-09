import PropTypes from "prop-types";

const Template = ({ icon, size, className }) => {
  return (
    <i
      className={`fa ${icon}${size}${className ? ` ${className}` : ""}`}
      aria-hidden="true"
    />
  );
};

Template.propTypes = {
  icon: PropTypes.string,
  size: PropTypes.string,
  className: PropTypes.string,
};

export default Template;
