import { useState } from "react";
import LoginLogic from "./loginLogic";
import Template from "./template";

const LoginForm = () => {
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [emailError, setEmailError] = useState("");
  const [passwordError, setPasswordError] = useState("");
  const [isSubmitting, setIsSubmitting] = useState(false);

  const { login } = LoginLogic({ email, password });
  const handleSetEmail = ({ target: { value } }) => setEmail(value);
  const handleSetPassword = ({ target: { value } }) => setPassword(value);
  const handleLogin = async () => {
    setIsSubmitting(true);
    const result = await login();
    console.log(result, result.errors.length);
    setEmailError(result.errors.email?.shift() || '');
    setPasswordError(result.errors.password?.shift() || '');
    setIsSubmitting(false);
  };

  return (
    <Template
      onSubmit={handleLogin}
      email={email}
      password={password}
      onSetEmail={handleSetEmail}
      onSetPassword={handleSetPassword}
      isSubmitting={isSubmitting}
      emailError={emailError}
      passwordError={passwordError}
    />
  );
};

export default LoginForm;
