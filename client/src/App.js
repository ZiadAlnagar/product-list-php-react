import { useEffect } from 'react';
import { useDispatch } from 'react-redux';
import { Route, Routes } from 'react-router-dom';
import Footer from './components/Footer';
import DeleteProvider from './contexts/delete';
import TypeProvider from './contexts/type';
import Index from './pages';
import Add from './pages/add';
import { initProducts } from './reducers/product.reducer';

const App = () => {
  const dispatch = useDispatch();

  useEffect(() => {
    dispatch(initProducts());
  }, []);

  return (
    <div className='container'>
      <Routes>
        <Route
          path='/add-product'
          element={
            <TypeProvider>
              <Add />
            </TypeProvider>
          }
        />
        <Route
          path='/'
          element={
            <DeleteProvider>
              <Index />
            </DeleteProvider>
          }
        />
      </Routes>
      <Footer />
    </div>
  );
};
export default App;
