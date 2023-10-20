import { createBrowserRouter } from "react-router-dom";
import { Navigate } from "react-router";
import shop from "./shop";

const router = createBrowserRouter([
  { path: "/", element: <Navigate to="/shop" /> },
  ...shop,
]);

export default router;
