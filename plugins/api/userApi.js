import axios from 'axios';

const userApi = {

  setupCSRFCookie() {
    return axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
  },

  // Tạo tài khoản người dùng
  postInfoLogin(param) {
    return userApi.setupCSRFCookie().then(() => {
      return axios.post('http://localhost:8000/api/register', param , { withCredentials: true });
    });
  },

  // lấy danh sách tất cả người dùng sau khi đăng ký thành công
  getAllUser(param) {
    return userApi.setupCSRFCookie().then(() => {
      return axios.get('http://localhost:8000/api/info-user/get-all-info-user', { params: param ,withCredentials: true });
    });
  },

  // Xóa thông tin của người dùng
  deleteUser(id) {
    return cartApi.setupCSRFCookie().then(() => {
      return axios.delete(`http://localhost:8000/api/info-user/delete-detail-user/${id}` ,{ withCredentials: true });
    });
  },

  // lấy danh sách người dùng sau khi đăng ký thành công
  getListUser() {
    return userApi.setupCSRFCookie().then(() => {
      return axios.get('http://localhost:8000/api/info-user/get-info-user', { withCredentials: true });
    });
  },

  // chỉnh sửa thông tin của người dùng
  postInfoUser(param) {
    return userApi.setupCSRFCookie().then(() => {
      return axios.post('http://localhost:8000/api/info-user/update-info', param, { withCredentials: true });
    });
  },

  postMessger(param) {
    return userApi.setupCSRFCookie().then(() => {
      return axios.post('http://localhost:3000/api/messages', param, { withCredentials: true });
    });
  },

};

export default userApi;