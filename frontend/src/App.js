import { Container } from "react-bootstrap";
import Navbar from "./components/navbar";
import "./App.scss";
import { RouterProvider } from "react-router-dom";
import router from "./routes/router";

function App() {
  return (
    <Container>
      <Navbar />
      <RouterProvider router={router} />
    </Container>
  );
}

export default App;
