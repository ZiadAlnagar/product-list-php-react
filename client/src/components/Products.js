import { useEffect, useState } from 'react';
import { useSelector } from 'react-redux';
import Product from './Product';

const Products = () => {
  const products = useSelector((state) => state.products);
  const [fill, setFill] = useState(0);

  useEffect(() => {
    if (products) {
      const len = products.length;
      const limit = 4;
      const rem = len % limit;
      if (rem % 4 > 1) setFill(limit - rem);
    }
  }, [products]);

  return (
    <div className='products'>
      <div className='products-list flex justify-between wrap w-full'>
        {products ? products.map((product) => <Product key={product.id} data={product} />) : null}
        {products && fill
          ? [...Array(fill)].map((e, i) => <div key={i} style={{ width: '23.5%' }} />)
          : null}
      </div>
    </div>
  );
};

export default Products;
