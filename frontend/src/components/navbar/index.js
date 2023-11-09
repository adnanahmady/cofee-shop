import LogoutLogic from "./logoutLogic";
import Template from "./template";

const Navbar = () => {
  const { logout } = LogoutLogic();

  const handleLogout = async (e) => {
    e.preventDefault();
    await logout();
  };

  return <Template onLogout={handleLogout} />;
};

export default Navbar;
