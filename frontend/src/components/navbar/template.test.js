import {
  render,
  screen
} from "@testing-library/react";
import Template from "./template.jsx";

it("should show the shop link", () => {
  render(<Template />);

  const element = screen.getByText(/Rock Star/);

  expect(element.href).toBe(process.env.REACT_APP_BASE_URL + "/");
  expect(element).toBeInTheDocument();
});

it('should show managers login link', () => {
  render(<Template />);

  const link = screen.getByText('Login as a manager');

  expect(link).toBeInTheDocument();
  expect(link).toHaveAttribute('href', '/managers/login/');
});
