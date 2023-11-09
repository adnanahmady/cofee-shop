import {
  render,
  screen
} from "@testing-library/react";
import Template from "./template";

it("should show login form", () => {
  render(<Template />);

  const element = screen.getAllByText(/Login/);

  expect(element[0]).toBeInTheDocument();
});
