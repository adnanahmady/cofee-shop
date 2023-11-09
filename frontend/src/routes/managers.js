import LoginPage from "../pages/managers/loginPage";

export const LOGIN_LINK = '/managers/login/';

const router = [
  {path: LOGIN_LINK, element: <LoginPage />}
];

export default router;