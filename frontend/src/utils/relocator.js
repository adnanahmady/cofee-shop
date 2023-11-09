const Relocator = () => {
  let state = "";

  const setLocation = (dist) => state = dist;

  const relocate = () => (window.location.href = state);

  const whereAmI = () => state;

  return { setLocation, relocate, whereAmI };
};

export default Relocator;
