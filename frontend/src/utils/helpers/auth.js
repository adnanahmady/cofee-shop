import AuthKeeper from "../keepers/authKeeper";
import Keeper from "../keepers/keeper";

export const authKeeper = AuthKeeper(Keeper());
export const isLoggedIn = () => authKeeper.getUser() && authKeeper.getToken();

export default {
  isLoggedIn,
  authKeeper,
};
