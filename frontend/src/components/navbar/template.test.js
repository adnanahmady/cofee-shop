import {
  render,
  screen
} from "@testing-library/react";
import Template from "./template.jsx";

test("It should show the shop link", () => {
  render(<Template />);

  const element = screen.getByText(/Rock Star/);

  expect(element.href).toBe(process.env.REACT_APP_BASE_URL + "/");
  expect(element).toBeInTheDocument();
});
