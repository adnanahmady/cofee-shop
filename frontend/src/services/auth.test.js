import auth from "./auth";
import loginSucceed from "./mockedApis/loginSucceed";
import Api from "../utils/api";
import AuthKeeper from "../utils/keepers/authKeeper";
import Keeper from "../utils/keepers/keeper";

it("should store the user data after login", async () => {
  jest.spyOn(Api(), "post").mockResolvedValue(loginSucceed);
  const mockedKeeper = AuthKeeper(Keeper());

  await auth.login({
    email: "user@dummy.com",
    password: "plain-password",
    as: "manager",
  });

  expect(mockedKeeper.getUser()).toBeDefined();
  expect(mockedKeeper.getToken()).toBeDefined();
});
