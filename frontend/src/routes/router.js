import { createBrowserRouter } from "react-router-dom";
import { Navigate } from "react-router";
import shop from "./shop";
import managers from './managers';

const router = createBrowserRouter([
  { path: "/", element: <Navigate to="/shop" /> },
  ...shop,
  ...managers
]);

export default router;
