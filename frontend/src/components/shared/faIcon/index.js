import PropTypes from "prop-types";
import Template from "./template";

const FaIcon = ({
  icon,
  size,
  pulse = null,
  spin = null,
  fixedWidth = null,
  className = null,
}) => {
  const iconSize = size ? " fa-" + size : "";
  const iconClass = `fa-${icon}`;
  const classes = [
    pulse && "fa-pulse",
    spin && "fa-spin",
    fixedWidth && "fa-fw",
    className
  ].join(" ").trim();

  return <Template icon={iconClass} size={iconSize} className={classes} />;
};

FaIcon.propTypes = {
  icon: PropTypes.string.isRequired,
  size: PropTypes.string,
  pulse: PropTypes.bool,
  spin: PropTypes.bool,
  fixedWidth: PropTypes.bool,
  className: PropTypes.string
};

export default FaIcon;
