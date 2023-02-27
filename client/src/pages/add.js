import { useRef, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useDispatch } from 'react-redux';
import Header from '../components/Header';
import PageTitle from '../components/PageTitle';
import Button from '../components/utils/Button';
import Notification from '../components/Notification';
import AddForm from '../components/AddForm';

const Add = () => {
  const navigate = useNavigate();
  const addFormRef = useRef(null);
  const [submit, setSubmit] = useState(false);

  const saveBtnClick = () => setSubmit(true);
  const cancelBtnClick = () => navigate('/');

  return (
    <div>
      <Header>
        <PageTitle text='Product Add'>
          <Button label='Save' onClick={saveBtnClick} />
          <Button label='Cancel' onClick={cancelBtnClick} />
        </PageTitle>
      </Header>
      <div className='main'>
        <Notification />
        <AddForm formRef={addFormRef} submitState={[submit, setSubmit]} />
      </div>
    </div>
  );
};

export default Add;
