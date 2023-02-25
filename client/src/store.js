import { configureStore } from '@reduxjs/toolkit';
import notificationReducer from './reducers/notification.reducer';
import productReducer from './reducers/product.reducer';

const store = configureStore({
  reducer: {
    products: productReducer,
    notification: notificationReducer,
  },
});

Object.defineProperty(window, 'reduxStore', {
  get() {
    return store.getState();
  },
});

export default store;
