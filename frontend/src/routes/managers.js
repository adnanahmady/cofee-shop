import LoginPage from "../pages/managers/loginPage";
import { isLoggedIn } from "../utils/helpers/auth";

export const LOGIN_LINK = "/managers/login/";

const router = [];

if (!isLoggedIn()) {
  router.push({ path: LOGIN_LINK, element: <LoginPage /> });
}

export default router;
