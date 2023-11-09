import { authKeeper } from "../utils/helpers/auth";

test("when user is logged in, login page should not add up", async () => {
  authKeeper.keep("some user", "some token");
  const managers = await import('./managers');
  const routes = managers.default;

  const loginPage = routes.filter((route) => route.path.match(/login/));

  expect(loginPage).toHaveLength(0);
});
