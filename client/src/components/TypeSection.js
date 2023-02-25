import { useContext, useEffect, useState } from 'react';
import { TypeContext } from '../contexts/type';
import { useControl } from '../hooks';
import { useValidate } from '../hooks/validation';
import Input from './utils/Input';

const TypeSection = ({ field, validate }) => {
  const [[type, setType], [attribute, setAttribute]] = useContext(TypeContext);
  const [weight, setWeight] = useState('');
  const [size, setSize] = useState('');
  const [height, setHeight] = useState('');
  const [width, setWidth] = useState('');
  const [length, setLength] = useState('');

  const [validation, setValidation] = validate;

  const description = (attr, format) => `Please, provide ${attr} in ${format} format`;

  useEffect(() => {
    setAttribute('');
    setValidation({
      ...validation,
      weight: valid,
      size: valid,
      height: valid,
      width: valid,
      length: valid,
    });
  }, [type]);

  useEffect(() => {
    if (type === '0') setAttribute(weight);
    if (type === '1') setAttribute(size);
    if (type === '2') setAttribute(`${height}x${width}x${length}`);
  }, [type, size, weight, height, width, length]);

  switch (type) {
    case '0':
      return (
        <div>
          <Input
            {...field.weight}
            {...useControl(weight, setWeight)}
            {...useValidate('weight', weight, field.weight, [validation, setValidation])}
          />
          <div>{description('weight', 'KG')}</div>
        </div>
      );
    case '1':
      return (
        <div>
          <Input
            {...field.size}
            {...useControl(size, setSize)}
            {...useValidate('size', size, field.size, [validation, setValidation])}
          />
          <div>{description('size', 'MB')}</div>
        </div>
      );
    case '2':
      return (
        <div>
          <Input
            {...field.height}
            {...useControl(height, setHeight)}
            {...useValidate('height', height, field.height, [validation, setValidation])}
          />
          <Input
            {...field.width}
            {...useControl(width, setWidth)}
            {...useValidate('width', width, field.width, [validation, setValidation])}
          />
          <Input
            {...field.length}
            {...useControl(length, setLength)}
            {...useValidate('length', length, field.length, [validation, setValidation])}
          />
          <div>{description('dimentions', 'HxWxL')}</div>
        </div>
      );
    default:
      return null;
  }
};

export default TypeSection;

const valid = [true, ''];
