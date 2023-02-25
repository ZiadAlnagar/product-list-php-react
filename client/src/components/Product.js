import { useContext, useEffect, useState } from 'react';
import { DeleteContext } from '../contexts/delete';

const Product = ({ data }) => {
  const { id, sku, name, price, type, attribute } = data;

  const [deleteList, setDeleteList] = useContext(DeleteContext);
  const [attr, setAttr] = useState('');
  const [attrName, setAttrName] = useState('');

  useEffect(() => {
    if (type === 0) {
      // Book
      setAttrName('Weight');
      setAttr(`${attribute}KG`);
    } else if (type === 1) {
      // DVD
      setAttrName('Size');
      setAttr(`${attribute} MB`);
    } else if (type === 2) {
      // Furniture
      setAttrName('Dimension');
      setAttr(`${attribute}`);
    }
  }, []);

  const deleteCheckboxClick = () => {
    setDeleteList([...deleteList, id]);
  };

  return (
    <div className='product'>
      <div className='product-card flex col items-center justify-center'>
        <div>{sku}</div>
        <div>{name}</div>
        <div>{price} $</div>
        <div>
          {attrName} {attr}
        </div>
      </div>
      <div className='product-delete flex'>
        <input type='checkbox' className='delete-checkbox' onClick={deleteCheckboxClick} />
      </div>
    </div>
  );
};

export default Product;
