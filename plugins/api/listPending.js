import axios from 'axios';

const pendingApi = {

  setupCSRFCookie() {
    return axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
  },

    // lấy danh sản phẩm đang chờ xử lý
  listProductPending() {
    return pendingApi.setupCSRFCookie().then(() => {
        return axios.get('http://localhost:8000/api/product-pending/get-product-pending' ,{ withCredentials: true });
    });
  },

  // người dùng hủy đơn hàng
  handleCancelProduct(param) {
    return pendingApi.setupCSRFCookie().then(() => {
      return axios.post('http://localhost:8000/api/product-pending/post-product-nextStep-pending', param ,{ withCredentials: true });
    });
  },

};

export default pendingApi;