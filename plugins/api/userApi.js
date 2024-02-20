import axios from 'axios';

const userApi = {
    // lấy danh sách người dùng
  getListUser() {
    return axios.get('http://127.0.0.1:8000/api/info-user/get-list-user');
  },
};

export default userApi;