import auth from "../../services/auth";
import { relocator } from "../../utils/helpers/relocator";

const LogoutLogic = () => {
  const logout = async () => {
    await auth.logout();
    relocator.setLocation("/");
    relocator.relocate();
  };
  return {
    logout,
  };
};

export default LogoutLogic;
