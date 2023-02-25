import { createContext, useMemo, useState } from 'react';

export const DeleteContext = createContext();

const DeleteProvider = ({ children }) => {
  const [deleteList, setDeleteList] = useState([]);
  const value = useMemo(() => [deleteList, setDeleteList], [deleteList]);

  return <DeleteContext.Provider value={value}>{children}</DeleteContext.Provider>;
};

export default DeleteProvider;
