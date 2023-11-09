import axios from "axios";
import Api from "./api";
import AuthKeeper from "./keepers/authKeeper";

jest.mock("axios", () => ({
  __esModule: true,
  get: jest.fn(),
  default: {
    defaults: { baseURL: "" },
    interceptors: {
      request: { use: jest.fn(() => {}) },
      response: { use: jest.fn(() => {}) },
    },
  },
}));
const relocator = (function () {
  let state = "/shop";

  return {
    setLocation: (dist) => {
      state = dist;

      return this;
    },
    relocate: () => "some",
    whereAmI: () => state,
  };
})();
const keeper = (function () {
  let state = {};

  return {
    get: (key) => state[key],
    keep: (key, value) => (state[key] = value),
    forget: (key) => delete state[key],
  };
})();

it("should remove stored client information when gets unauthorized", () => {
  const err = { response: { status: 401, data: { message: "some" } } };
  const place = (relocator, keeper) =>
    Api(relocator, keeper).interceptors.response.use.mock.calls[0][1];
  const authKeeper = AuthKeeper(keeper);
  authKeeper.keep({ name: "dummy" }, "some token");

  place(relocator, authKeeper)(err).catch(() => {});

  expect(authKeeper.getUser()).toBeUndefined();
  expect(authKeeper.getToken()).toBeUndefined();
});

it("should redirect the guest to expected path", () => {
  const err = { response: { status: 401, data: { message: "some" } } };
  const place = (relocator, keeper) =>
    Api(relocator, keeper).interceptors.response.use.mock.calls[0][1];

  place(relocator, keeper)(err).catch(() => {});

  expect(relocator.whereAmI()).toBe("/");
});

it("should call expected endpoint", () => {
  Api(relocator);

  expect(axios.defaults.baseURL).toBe(process.env.REACT_APP_BACKEND_BASE_URL);
});
