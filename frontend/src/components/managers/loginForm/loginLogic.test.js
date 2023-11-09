import LoginLogic from "./loginLogic";
import loginSucceed from "./mockedApis/loginSucceed";
import loginFailure from "./mockedApis/loginFailure";
import internalServerError from "./mockedApis/internalServerError";
import Api from "../../../utils/api";

const fakeRelocator = (() => {
  let state = "/shop";
  let relocated = false;

  return {
    setLocation: (dist) => {
      state = dist;

      return this;
    },
    relocate: () => (relocated = true),
    whereAmI: () => state,
    isRelocated: () => relocated,
  };
})();
const fakeInstance = () => ({
  setLocation: jest.fn((path) => fakeRelocator.setLocation(path)),
  relocate: jest.fn(() => fakeRelocator.relocate()),
  whereAmI: jest.fn(() => fakeRelocator.whereAmI()),
});
jest.mock("../../../utils/relocator", () => {
  return {
    __esModule: true,
    default: fakeInstance,
  };
});

it("should redirect the user to home path", async () => {
  jest.spyOn(Api(), "post").mockResolvedValue(loginSucceed);
  const { login } = LoginLogic({
    email: "john@email.com",
    password: "password",
  });

  await login();

  expect(fakeRelocator.whereAmI()).toBe("/");
  expect(fakeRelocator.isRelocated()).toBeTruthy();
});

it("should notify the client code about request 500 failure", async () => {
  jest.spyOn(Api(), "post").mockRejectedValue(internalServerError);
  const { login } = LoginLogic({
    email: "john@email.com",
    password: "password",
  });

  const result = await login();

  expect(result.isLoggedIn).toBe(false);
  expect(result.isHealthy).toBe(false);
});

it("should notify the client code about operation failure", async () => {
  jest.spyOn(Api(), "post").mockRejectedValue(loginFailure);
  const { login } = LoginLogic({
    email: "john@email.com",
    password: "password",
  });

  const result = await login();

  expect(result.isLoggedIn).toBe(false);
  expect(result.isHealthy).toBe(true);
});

it("should notify the client code about operation success", async () => {
  jest.spyOn(Api(), "post").mockResolvedValue(loginSucceed);
  const { login } = LoginLogic({
    email: "john@email.com",
    password: "password",
  });

  const result = await login();

  expect(result.isLoggedIn).toBe(true);
  expect(result.isHealthy).toBe(true);
});
