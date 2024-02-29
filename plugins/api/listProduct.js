import axios from 'axios';

const productApi = {

  setupCSRFCookie() {
    return axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
  },

    // lấy danh sách sản phẩm váy nữ
  getListProduct() {
    return productApi.setupCSRFCookie().then(() => {
      return axios.get('http://localhost:8000/api/product/get-product', { withCredentials: true });
    });
  },

  // lấy chi tiết sản phẩm
  detailProduct(productCode) {
    return productApi.setupCSRFCookie().then(() => {
      return axios.get(`http://localhost:8000/api/product/get-detail-product/${productCode}` ,{ withCredentials: true });
    });
  },

  // lấy danh sách sản phẩm quần
  postInfoUser(param) {
    return productApi.setupCSRFCookie().then(() => {
      return axios.post('http://localhost:8000/api/info-user/update-info', param, { withCredentials: true });
    });
  },

};

export default productApi;