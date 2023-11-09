import auth from "../../../services/auth";
import { relocator } from "../../../utils/helpers/relocator";

const LoginLogic = ({ email, password }) => {
  const login = async () => {
    let isHealthy = true;
    let isLoggedIn = true;
    let errors = {};

    try {
      await auth.login({
        email: email,
        password: password,
        as: "manager",
      });
      relocator.setLocation("/");
      relocator.relocate();
    } catch (ex) {
      if (ex.response && ex.response.status === 422) {
        errors = ex.response.data.errors;
      }

      if (ex.response && ex.response.status === 500) {
        isHealthy = false;
      }
      isLoggedIn = false;
    }

    return { isLoggedIn, isHealthy, errors };
  };

  return { login };
};

export default LoginLogic;
