import Button from './Button';

const BtnForm = ({ onSubmit, label, style, id, className, children, ...rest }) => (
  <form className='inline' onSubmit={onSubmit}>
    <Button type='submit' label={label} className={className} id={id} style={style} {...rest}>
      {children}
    </Button>
  </form>
);
export default BtnForm;
