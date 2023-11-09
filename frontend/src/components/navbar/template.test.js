import {
  render,
  screen
} from "@testing-library/react";
import Template from "./template.jsx";
import { authKeeper } from "../../utils/helpers/auth.js";

const createTemplate = () => <Template onLogout={() => {}} />;

it("should show logout when user is already loggedin", () => {
  authKeeper.keep("some user", "some token");
  render(createTemplate());
  authKeeper.forget();

  const link = screen.getByText("Logout");

  expect(link).toBeInTheDocument();
  expect(link).toHaveAttribute("href", "#");
});

it("should show the shop link", () => {
  render(createTemplate());

  const element = screen.getByText(/Rock Star/);

  expect(element.href).toBe(process.env.REACT_APP_BASE_URL + "/");
  expect(element).toBeInTheDocument();
});

it("should show managers login link", () => {
  render(createTemplate());

  const link = screen.getByText("Login as a manager");

  expect(link).toBeInTheDocument();
  expect(link).toHaveAttribute("href", "/managers/login/");
});
