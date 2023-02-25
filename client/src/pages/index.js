import { useContext } from 'react';
import { useDispatch } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import Header from '../components/Header';
import PageTitle from '../components/PageTitle';
import Products from '../components/Products';
import BtnForm from '../components/utils/BtnForm';
import Button from '../components/utils/Button';
import { DeleteContext } from '../contexts/delete';
import { destroyProducts } from '../reducers/product.reducer';

const Index = () => {
  const dispatch = useDispatch();
  const navigate = useNavigate();

  const [deleteList, setDeleteList] = useContext(DeleteContext);

  const addBtnClick = () => navigate('/add-product');

  const deleteFormHandle = (e) => {
    e.preventDefault();
    dispatch(destroyProducts(deleteList));
  };

  return (
    <div>
      <Header>
        <PageTitle text='Product List'>
          <Button label='add' onClick={addBtnClick} />
          <BtnForm label='mass delete' id='delete-product-btn' onSubmit={deleteFormHandle} />
        </PageTitle>
      </Header>
      <div className='main'>
        <Products />
      </div>
    </div>
  );
};

export default Index;
