import { render } from "@testing-library/react";
import FaIcon from "./index";

it("should add spin related class", () => {
  const { container } = render(<FaIcon icon="warning" spin />);

  const element = container.querySelector(".fa-warning.fa-spin");

  expect(element).toBeInTheDocument();
});

it("should add fixed width related class", () => {
  const { container } = render(<FaIcon icon="warning" fixedWidth />);

  const element = container.querySelector(".fa-warning.fa-fw");

  expect(element).toBeInTheDocument();
});

it("should add pulse related class", () => {
  const { container } = render(<FaIcon icon="warning" pulse />);

  const element = container.querySelector(".fa-warning.fa-pulse");

  expect(element).toBeInTheDocument();
});

it("should change the icon is expected", () => {
  const { container } = render(<FaIcon icon="warning" />);

  const element = container.querySelector(".fa-warning");

  expect(element).toBeInTheDocument();
});

it("should be able to change the size by user choice", () => {
  const { container } = render(<FaIcon icon="warning" size="2x" />);

  const element = container.querySelector(".fa-2x");

  expect(element).toBeInTheDocument();
});
