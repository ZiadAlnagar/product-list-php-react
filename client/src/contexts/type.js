import { createContext, useMemo, useState } from 'react';

export const TypeContext = createContext();

const TypeProvider = ({ children }) => {
  const [type, setType] = useState('');
  const [attribute, setAttribute] = useState('');
  const value = useMemo(
    () => [
      [type, setType],
      [attribute, setAttribute],
    ],
    [type, attribute],
  );

  return <TypeContext.Provider value={value}>{children}</TypeContext.Provider>;
};

export default TypeProvider;
