import Api from "../utils/api";
import Relocator from "../utils/relocator";
import { authKeeper } from "../utils/helpers/auth";

const api = Api(Relocator(), authKeeper);
const VERSION = "/api/v1";

export const login = async ({ email, password, as }) => {
  const {
    data: { data, meta },
  } = await api.post(`${VERSION}/login`, {
    email,
    password,
    as,
  });
  authKeeper.keep(meta.user, data.access_token);

  return data;
};

export const logout = async () => {
  const { data } = await api.post(
    `${VERSION}/logout`,
    {},
    {
      headers: {
        Authorization: `Bearer ${authKeeper.getToken()}`,
      },
    }
  );
  authKeeper.forget();

  return data;
};

export default {
  login,
  logout,
};
