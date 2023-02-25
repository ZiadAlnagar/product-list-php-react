import H from './utils/H';

const PageTitle = ({ text, children }) => (
  <div className='flex justify-between items-center'>
    <H text={text} />
    <div className='actions'>{children}</div>
  </div>
);

export default PageTitle;
