import Api from "../utils/api";
import Keeper from "../utils/keepers/keeper";
import AuthKeeper from "../utils/keepers/authKeeper";
import Relocator from "../utils/relocator";

const keeper = AuthKeeper(Keeper());
const api = Api(Relocator(), keeper);
const VERSION = "/api/v1";

export const login = async ({ email, password, as }) => {
  const {
    data: { data, meta },
  } = await api.post(`${VERSION}/login`, {
    email,
    password,
    as,
  });
  keeper.keep(meta.user, data.access_token);

  return data;
};

export default {
  login,
};
