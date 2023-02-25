import { useCallFuncByName, useUcfirst } from './index';

export const useValidate = (name, value, rules, validation, options = null) => ({
  onBlur: () => validate(name, value, rules, validation, options),
  errMsg: validation[0][name][1],
  // onInput: !validation[0][name][0] ? () => validate(name, value, rules, validation) : null,
});

const validate = (name, value, rules, [validation, setValidation], options) => {
  const [fieldName, varType] = validationRules(rules);
  name = name ?? fieldName;
  const len = Object.entries(rules).length;
  Object.entries(rules).some(([k, v], i) => {
    if (name === 'sku' && value.length === 8) {
      if ('skuUnique' in options && !options.skuUnique) {
        setValidation({ ...validation, [name]: [false, uniqueErr(name)] });
        return true;
      }
    }
    if (k === 'required')
      if (!value) {
        setValidation({ ...validation, [name]: [false, requiredErr(name)] });
        return true;
      }
    if (k === 'minLength')
      if (value.length < v) {
        setValidation({
          ...validation,
          [name]: [false, lengthErr('<', name, v)],
        });
        return true;
      }
    if (k === 'maxLength')
      if (value.length > v) {
        setValidation({
          ...validation,
          [name]: [false, lengthErr('>', name, v)],
        });
        return true;
      }
    if (k === 'min') {
      const parse = varType ?? 'Int';
      value = useCallFuncByName(`parse${parse}`, value, 10);
      v = useCallFuncByName(`parse${parse}`, v, 10);
      if (value < v) {
        setValidation({ ...validation, [name]: [false, numberErr('<', v)] });
        return true;
      }
    }
    if (k === 'max') {
      const parse = varType ?? 'Int';
      value = useCallFuncByName(`parse${parse}`, value, 10);
      v = useCallFuncByName(`parse${parse}`, value, 10);
      if (value > v) {
        setValidation({ ...validation, [name]: [false, numberErr('>', v)] });
        return true;
      }
    }
    if (i === len - 1) setValidation({ ...validation, [name]: [true, ''] });
    return false;
  });
  // Object.entries(validation).some(([att, val]) => {
  //   if (att !== 'allValid') {
  //     if (!val[0]) return true;
  //   } else setValidation({ ...validation, allValid: true });
  //   return false;
  // });
};

const validationRules = (ruleSet) => {
  const rules = { ...ruleSet };
  let varType;
  const name = rules.name ?? rules.id;

  if (rules.type === 'number') {
    if (rules.varType) varType = useUcfirst(rules.varType);
    else varType = 'Int';
  }

  delete rules.id;
  delete rules.name;
  delete rules.label;
  delete rules.placeholder;
  delete rules.step;
  delete rules.spellCheck;
  delete rules.autoComplete;
  delete rules.type;
  delete rules.varType;

  return [name, varType];
};

const requiredErr = (field) => `${field} is required`;
const uniqueErr = (field) => `${field} must be unique`;
const lengthErr = (cond, field, value) => {
  if (cond === '<') return `${field} must be atleast ${value} long`;
  if (cond === '>') return `${field} must not be greater than ${value} characters long`;
  return null;
};

const numberErr = (cond, value) => {
  if (cond === '<') return `Value must be greater than  or equal to ${value}`;
  if (cond === '>') return `Value must be less than  or equal to ${value}`;
  return null;
};

export default useValidate;
