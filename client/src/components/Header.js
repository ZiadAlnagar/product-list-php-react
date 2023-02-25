import { Link } from 'react-router-dom';
import logo from '../assets/logo.png';

const Header = ({ children }) => (
  <header>
    <div className='flex justify-between items-center'>
      <div className='logo'>
        <Link to='/' className='flex items-center'>
          <div className='logo-title'>Producks</div>
          <img className='logo-img' src={logo} alt='logo' />
        </Link>
      </div>
      <div className='nav-bar flex'>
        <Link to='/'>Home</Link>
        <Link to='/add-product'>Create</Link>
      </div>
      <div className='placeholder' />
    </div>
    <div className='page-title'>{children}</div>
  </header>
);

export default Header;
