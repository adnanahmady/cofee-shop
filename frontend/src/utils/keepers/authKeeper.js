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
  keeper: PropTypes.object.isRequired,
};

export default AuthKeeper;
