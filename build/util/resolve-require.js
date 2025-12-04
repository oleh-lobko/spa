module.exports = (dependency) => {
  try {
    require.resolve(dependency);
  } catch (err) {
    return {};
  }
  return require(dependency);
};
