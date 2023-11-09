import {
  render,
  screen
} from "@testing-library/react";
import Template from "./template";

const createTemplate = (props) => (
  <Template
    email=""
    password=""
    onSetEmail={() => {}}
    onSetPassword={() => {}}
    onSubmit={() => {}}
    {...props}
  />
);

it("should disable and show proper loading icon while logging in", () => {
  const { container } = render(createTemplate({ isSubmitting: true }));

  const disabledButton = container.querySelector("button[disabled]");
  const loadingIcon = container.querySelector(".fa-spinner.fa-pulse");

  expect(disabledButton).toBeInTheDocument();
  expect(loadingIcon).toBeInTheDocument();
});

describe("email section tests", () => {
  test("a proper label needs to be set for email input", () => {
    const { container } = render(createTemplate());

    const emailLabel = container.querySelector("label[for=email]");

    expect(emailLabel).toHaveTextContent("Email");
  });

  test('email input needs to be of type "email"', () => {
    const { container } = render(createTemplate());

    const email = container.querySelectorAll("[type=email]");

    expect(email).toHaveLength(1);
  });

  describe("email error message tests", () => {
    it("should be showing when necessary", () => {
      const error = new Date().toString();
      render(createTemplate({ emailError: error }));

      const alert = screen.getByText(error);

      expect(alert).toBeInTheDocument();
    });

    it("should be showing only when its presented", () => {
      const { container } = render(createTemplate());

      const parent = container.querySelector("[type=email]").parentElement;

      expect(parent.children).toHaveLength(2);
    });
  });
});

describe("password section tests", () => {
  test("a proper label needs to be set for password input", () => {
    const { container } = render(createTemplate());

    const emailLabel = container.querySelector("label[for=password]");

    expect(emailLabel).toHaveTextContent("Password");
  });

  test('password input needs to be of type "password"', () => {
    const { container } = render(createTemplate());

    const password = container.querySelectorAll("[type=password]");

    expect(password).toHaveLength(1);
  });

  describe("password error message tests", () => {
    it("should be showing when necessary", () => {
      const error = new Date().toString();
      render(createTemplate({ passwordError: error }));

      const alert = screen.getByText(error);

      expect(alert).toBeInTheDocument();
    });

    it("should be showing only when its presented", () => {
      const { container } = render(createTemplate());

      const parent = container.querySelector("[type=password]").parentElement;

      expect(parent.children).toHaveLength(2);
    });
  });
});

test('Submit button named "login" should exist in form', () => {
  const { container } = render(createTemplate());

  const submitButton = container.querySelector("button");

  expect(submitButton).toHaveTextContent("Login");
});

test("It should be able get email and password of the user", () => {
  const { container } = render(createTemplate());

  const elements = container.querySelectorAll("input");

  expect(elements).toHaveLength(2);
});
