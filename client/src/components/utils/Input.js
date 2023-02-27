import InputError from './InputError';

const Input = ({
  label,
  id,
  name,
  type,
  value,
  onChange,
  onBlur,
  errMsg,
  placeholder,
  varType,
  min,
  max,
  children,
  ...rest
}) => (
  <label className='field flex col' htmlFor={id}>
    <div className='input-label'>{label}</div>
    <input
      id={id}
      name={name ?? id}
      type={type}
      value={value}
      onChange={onChange}
      onBlur={onBlur}
      placeholder={placeholder}
      min={null}
      max={null}
      {...rest}
    />
    <InputError message={errMsg} />
    {children}
  </label>
);

export default Input;
