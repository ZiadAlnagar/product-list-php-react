import { useCallback, useContext, useEffect, useRef, useState } from 'react';
import { useDispatch } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import { TypeContext } from '../contexts/type';
import { useControl } from '../hooks';
import { useValidate } from '../hooks/validation';
import { createProduct } from '../reducers/product.reducer';
import productService from '../services/products';
import TypeSection from './TypeSection';
import Dropdown from './utils/Dropdown';
import Input from './utils/Input';

const AddForm = ({ formRef, submitState }) => {
  const navigate = useNavigate();
  const dispatch = useDispatch();
  const [submit, setSubmit] = submitState;
  const [valid, setValid] = useState(null);
  const [checked, setChecked] = useState(false);
  const [sku, setSku] = useState('');
  const [skuUnique, setSkuUnique] = useState(true);
  const [name, setName] = useState('');
  const [price, setPrice] = useState('');
  const [[type, setType], [attribute, setAttribute]] = useContext(TypeContext);
  const [validation, setValidation] = useState({
    sku: [true, ''],
    name: [true, ''],
    price: [true, ''],
    type: [true, ''],
    weight: [true, ''],
    size: [true, ''],
    height: [true, ''],
    width: [true, ''],
    length: [true, ''],
  });

  useEffect(() => {
    if (submit) validateForm();
    return () => setSubmit(false);
  }, [submit]);

  useEffect(() => {
    if (checked) setChecked(false);
    if (checked) isFormValid();
  }, [checked]);

  useEffect(() => {
    if (valid)
      formRef.current.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
    return () => setValid(null);
  }, [valid]);

  useEffect(() => {
    const isUnique = async () => {
      try {
        const unqiue = await productService.isUnique(sku);
        setSkuUnique(true);
      } catch (ex) {
        setSkuUnique(ex.response.data.error.unique);
      }
    };

    if (sku.length === 8) isUnique();
  }, [sku]);

  const validateForm = useCallback(() => {
    const inputs = formRef.current.querySelectorAll('input, #type');
    let timers = [];
    inputs.forEach((elem) => {
      const timer = setTimeout(() => {
        elem.focus();
        elem.blur();
      }, 0);
      timers = [...timers, timer];
    });
    setChecked(true);
    return () =>
      timers.forEach((id) => {
        clearTimeout(id);
      });
  }, [type]);

  const isFormValid = useCallback(() => {
    if (!sku && type === '' && !attribute && attribute !== 'xx' && !price && !name)
      return setValid(false);
    const len = Object.entries(validation).length;
    Object.entries(validation).some(([k, v], i) => {
      if (!v[0]) {
        setValid(false);
        return true;
      }
      if (i === len - 1) setValid(true);
      return false;
    });
  }, [validation, validation, sku, type, attribute, price, name]);

  const addFormHandle = useCallback(
    (e) => {
      e.preventDefault();
      const newProduct = {
        sku,
        name,
        price,
        type,
        attribute,
      };
      dispatch(createProduct(newProduct));
      navigate('/');
    },
    [sku, name, price, type, attribute],
  );

  return (
    <form id='product_form' onSubmit={addFormHandle} ref={formRef} noValidate>
      <Input
        {...field.sku}
        {...useControl(sku, setSku)}
        {...useValidate('sku', sku, field.sku, [validation, setValidation], { skuUnique })}
      />
      <Input
        {...field.name}
        {...useControl(name, setName)}
        {...useValidate('name', name, field.name, [validation, setValidation])}
      />
      <Input
        {...field.price}
        {...useControl(price, setPrice)}
        {...useValidate('price', price, field.price, [validation, setValidation])}
      />
      <Dropdown
        {...field.type}
        options={['Book', 'DVD', 'Furniture']}
        state={[type, setType]}
        initial='type'
        {...useValidate('type', type, field.type, [validation, setValidation])}
      />
      <TypeSection field={field.attributes} validate={[validation, setValidation]} />
    </form>
  );
};

export default AddForm;

const field = {
  sku: {
    id: 'sku',
    label: 'SKU',
    required: true,
    type: 'text',
    minLength: 8,
    maxLength: 8,
    placeholder: 'GGWP0007',
    spellCheck: 'off',
    autoComplete: 'off',
  },
  name: {
    id: 'name',
    label: 'Name',
    required: true,
    type: 'text',
    minLength: 2,
    maxLength: 150,
    placeholder: 'Cool Duck ( •ө• )',
    spellCheck: 'off',
    autoComplete: 'off',
  },
  price: {
    id: 'price',
    label: 'Price ($)',
    required: true,
    type: 'number',
    varType: 'float',
    step: '0.1',
    placeholder: '0.99',
    min: '0.99',
  },
  type: {
    id: 'productType',
    required: true,
  },
  attributes: {
    weight: {
      id: 'weight',
      label: 'Weight (KG)',
      required: true,
      type: 'number',
      varType: 'float',
      step: '0.1',
      placeholder: '1.5',
      min: '0.1',
    },
    size: {
      id: 'size',
      label: 'Size (MB)',
      required: true,
      type: 'number',
      step: '1',
      placeholder: '700',
      min: '185',
    },
    height: {
      id: 'height',
      label: 'Height (CM)',
      required: true,
      type: 'number',
      varType: 'float',
      step: '1',
      placeholder: '170',
      min: '0.1',
    },
    width: {
      id: 'width',
      label: 'Width (CM)',
      required: true,
      type: 'number',
      varType: 'float',
      step: '1',
      placeholder: '80',
      min: '0.1',
    },
    length: {
      id: 'length',
      label: 'Length (CM)',
      required: true,
      type: 'number',
      varType: 'float',
      step: '1',
      placeholder: '66',
      min: '0.1',
    },
  },
};
