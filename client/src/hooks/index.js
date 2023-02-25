import { useEffect, useRef } from 'react';

export const useIncludes = (string, needle) => string.toLowerCase().includes(needle);

export const useControl = (state, setState) => ({
  value: state,
  onChange: useOnChange(setState),
});

export const useOnChange =
  (setState) =>
  ({ target }) =>
    setState(target.value);

export const useCallFuncByName = (name, ...args) => window[name](...args);

export const useUcfirst = (string) => string.charAt(0).toUpperCase() + string.slice(1);

export const useIsMount = () => {
  const isMountRef = useRef(true);
  useEffect(() => {
    isMountRef.current = false;
  }, []);
  return isMountRef.current;
};

export const usePrevious = (value) => {
  const ref = useRef();
  useEffect(() => {
    ref.current = value;
  });
  return ref.current;
};

export const useObjEq = (left, right) => {
  if (JSON.stringify(left) === JSON.stringify(right)) return true;
  return false;
};

export default {
  useOnChange,
};
