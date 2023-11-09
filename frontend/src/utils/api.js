import axios from "axios";
import PropTypes from "prop-types";

const BASE_URL = process.env.REACT_APP_BACKEND_BASE_URL;

axios.defaults.baseURL = BASE_URL;

const Api = (relocator, keeper) => {
  axios.interceptors.response.use(
    (response) => response,
    (error) => {
      if (error.response.status === 401) {
        keeper.forget();
        relocator.setLocation("/");
        relocator.relocate();
      }

      return Promise.reject(error);
    }
  );

  return axios;
};

Api.propTypes = {
  relocator: PropTypes.shape({
    setLocation: PropTypes.func.isRequired,
    relocate: PropTypes.func.isRequired,
  }),
  keeper: PropTypes.shape({
    forget: PropTypes.func.isRequired,
  }),
};

export default Api;


