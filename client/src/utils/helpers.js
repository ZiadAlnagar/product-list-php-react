export const uri = (...path) => path.reduce((url, segment) => (url += `/${segment}`));

export default {
  uri,
};
