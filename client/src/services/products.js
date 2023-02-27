import axios from 'axios';
import { uri } from '../utils/helpers';

const baseUrl = '/api/products';

const index = async () => {
  const response = await axios.get(baseUrl);
  return response.data;
};

const show = async (id) => {
  const response = await axios.get(uri(baseUrl, id));
  return response.data;
};

const create = async (obj) => {
  const response = await axios.post(baseUrl, obj);
  return response.data;
};

const update = async (id, obj) => {
  const response = await axios.put(uri(baseUrl, id), obj);
  return response.data;
};

const destroy = async (ids) => {
  const response = await axios.post(
    baseUrl,
    { ids },
    {
      headers: {
        'x-delete': 1,
      },
    },
  );
  // const response = await axios.delete(baseUrl, { data: { ids } });
  return response.data;
};

const isUnique = async (sku) => {
  const response = await axios.get(uri(baseUrl, 'sku', sku));
  return response.data;
};

export default {
  index,
  show,
  create,
  update,
  destroy,
  isUnique,
};
