import { useControl } from '../../hooks';
import InputError from './InputError';

const Dropdown = ({ id, options, state, initial, errMsg, ...rest }) => (
  <div className='inline-block'>
    <select id={id} name={id} {...useControl(state[0], state[1])} {...rest}>
      <option value=''>{initial}</option>
      {options.map((v, k) => (
        <option id={v} value={k} key={k}>
          {v}
        </option>
      ))}
    </select>
    <InputError message={errMsg} />
  </div>
);

export default Dropdown;
