import { createSlice } from '@reduxjs/toolkit';

const notificationReducer = createSlice({
  name: 'notification',
  initialState: null,
  reducers: {
    createNotification(state, action) {
      return (state = action.payload);
    },
    clearNotification(state, action) {
      return null;
    },
  },
});

export const { createNotification, clearNotification } = notificationReducer.actions;
export default notificationReducer.reducer;

let displayTimer;

export const setNotification =
  (message, type = 'error', duration = 5) =>
  (dispatch) => {
    dispatch(createNotification({ message, type }));
    clearTimeout(displayTimer);
    displayTimer = setTimeout(() => {
      dispatch(clearNotification());
    }, duration * 1000);
  };
