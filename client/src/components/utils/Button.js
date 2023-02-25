const Button = ({ type, onClick, label, style, id, className, children, ...rest }) => (
  <button type={type} onClick={onClick} className={className} id={id} style={style} {...rest}>
    <div>{label || children}</div>
  </button>
);

Button.defaultProps = {
  type: 'button',
};

export default Button;
