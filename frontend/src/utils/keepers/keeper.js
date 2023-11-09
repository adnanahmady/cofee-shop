const Keeper = () => {
  const get = (key) => {
    const value = localStorage.getItem(key);

    return JSON.parse(value);
  };

  const keep = (key, value) => {
    localStorage.setItem(key, JSON.stringify(value));
  };

  const forget = (key) => {
    localStorage.removeItem(key);
  };

  return { get, keep, forget };
};

export default Keeper;
