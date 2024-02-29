import axios from 'axios';

const cartApi = {

  setupCSRFCookie() {
    return axios.get('http://localhost:8000/sanctum/csrf-cookie', { withCredentials: true });
  },

    // lấy danh sách người dùng mua quần áo
  getListProduct() {
    return cartApi.setupCSRFCookie().then(() => {
      return axios.get('http://localhost:8000/api/cart-api/user-buys-product', { withCredentials: true });
    });
  },

  // người dùng thêm sản phẩm vào giỏ hàng
  productCart(param) {
    return cartApi.setupCSRFCookie().then(() => {
      return axios.post('http://localhost:8000/api/cart-api/post-detail-product-cart', param ,{ withCredentials: true });
    });
  },

  // chi tiết sản phẩm của người dùng mua hàng
  productCartDetail(productCode) {
    return cartApi.setupCSRFCookie().then(() => {
      return axios.get(`http://localhost:8000/api/cart-api/get-detail-product-cart/${productCode}` ,{ withCredentials: true });
    });
  },

  // xóa sản phẩm khỏi giỏ hàng
  productDeleteDetail(id) {
    return cartApi.setupCSRFCookie().then(() => {
      return axios.delete(`http://localhost:8000/api/cart-api/delete-detail-product-cart/${id}` ,{ withCredentials: true });
    });
  },

};

export default cartApi;