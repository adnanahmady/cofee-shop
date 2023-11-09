import Api from "../../utils/api";
import { authKeeper } from "../../utils/helpers/auth";
import LogoutLogic from "./logoutLogic";
import logoutSucceed from "./mockedApis/logoutSucceed";

const relocator = (() => {
  let state = "/market";
  let isRelocated = false;

  return {
    setLocation: (path) => (state = path),
    relocate: () => (isRelocated = true),
    whereAmI: () => state,
    isRelocated: () => isRelocated,
  };
})();

const fakedRelocator = () => ({
  setLocation: (path) => relocator.setLocation(path),
  relocate: () => relocator.relocate(),
  whereAmI: () => relocator.whereAmI(),
});

jest.mock("../../utils/relocator", () => ({
  __esModule: true,
  default: fakedRelocator,
}));

it("should redirect to main page after logout", async () => {
  jest.spyOn(Api(), "post").mockResolvedValue(logoutSucceed);
  const { logout } = LogoutLogic();

  await logout();

  expect(relocator.whereAmI()).toBe("/");
  expect(relocator.isRelocated()).toBeTruthy();
});

it("should send the logout request", async () => {
  jest.spyOn(Api(), "post").mockResolvedValue(logoutSucceed);
  const { logout } = LogoutLogic();

  await logout();

  expect(Api().post).toBeCalledWith(
    "/api/v1/logout",
    {},
    { headers: { Authorization: "Bearer null" } }
  );
});

it("should clear user data", async () => {
  authKeeper.keep("some user", "some token");
  jest.spyOn(Api(), "post").mockResolvedValue(logoutSucceed);
  const { logout } = LogoutLogic();

  await logout();

  expect(authKeeper.getUser()).toBeNull();
  expect(authKeeper.getToken()).toBeNull();
});
