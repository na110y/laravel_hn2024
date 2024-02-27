import axios from 'axios';

const userApi = {
    // lấy danh sách người dùng sau khi đăng ký thành công
  getListUser() {
    return axios.get( 'http://localhost:8000/api/info-user/get-list-user');
  },
};

export default userApi;