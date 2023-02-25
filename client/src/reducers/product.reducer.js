import { createSlice } from '@reduxjs/toolkit';
import productService from '../services/products';

const productSlice = createSlice({
  name: 'products',
  initialState: null,
  reducers: {
    removeProduct(state, action) {
      return state.filter((p) => p.id !== action.payload);
    },
    removeProducts(state, action) {
      const ids = action.payload;
      return state.filter((p) => !ids.includes(p.id));
    },
    appendProduct(state, action) {
      state.push(action.payload);
    },
    setProducts(state, action) {
      return action.payload;
    },
  },
});

export const { setProducts, appendProduct, removeProduct, removeProducts } = productSlice.actions;
export default productSlice.reducer;

export const initProducts = () => async (dispatch) => {
  const products = await productService.index();
  dispatch(setProducts(products));
};

export const createProduct = (product) => async (dispatch) => {
  try {
    const newProduct = await productService.create(product);
    dispatch(appendProduct(newProduct));
  } catch (ex) {
    /* empty */
  }
};

export const destroyProducts = (ids) => async (dispatch) => {
  try {
    await productService.destroy(ids);
    dispatch(removeProducts(ids));
  } catch (ex) {
    /* empty */
  }
};