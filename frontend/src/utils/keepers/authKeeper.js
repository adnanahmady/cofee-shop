import PropTypes from "prop-types";

const AuthKeeper = (keeper) => {
  const USER = "user";
  const TOKEN = "access_token";

  return {
    getUser: () => keeper.get(USER),
    getToken: () => keeper.get(TOKEN),
    keep: (user, token) => {
      keeper.keep(USER, user);
      keeper.keep(TOKEN, token);
    },
    forget: () => {
      keeper.forget(USER);
      keeper.forget(TOKEN);
    },
  };
};

AuthKeeper.propTypes = {
  keeper: PropTypes.shape({
    get: PropTypes.func.isRequired,
    keep: PropTypes.func.isRequired,
    forget: PropTypes.func.isRequired,
  }).isRequired,
};

export default AuthKeeper;
