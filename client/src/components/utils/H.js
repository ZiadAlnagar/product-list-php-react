const H = ({ lvl, text, style, id, className, children }) => {
  const Heading = `h${lvl}`;
  return (
    <Heading className={className} id={id} style={style}>
      {text || children}
    </Heading>
  );
};

H.defaultProps = {
  lvl: 1,
};

export default H;
