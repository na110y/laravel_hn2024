<template>
    <b-container fluid>
      <loading
        :active.sync="isLoading"
        :can-cancel="true"
        :is-full-page="true"
      ></loading>
  
      <div class="content">
          <div class="product">
            <div class="product-first">
              <div class="product-first_title">Sản phẩm mới</div>
              <div class="product-first_select">
                <b-form-group id="login-label" label="Lọc theo" label-for="search-available" class="mt-2">
                    <b-form-select v-model="$i18n.locale" id="lang" @change="toggleSortOrder">
                        <option v-for="(item, index) in productBody" :value="item.value" :key="index + 'text'">
                            {{ item.text  }}
                        </option>
                    </b-form-select>
                </b-form-group>
              </div>
            </div>
  
            <div class="product-body">
              <div class="info-product" v-for="(items, index) in listProduct" :key="index + 'detail'">
                <img :src="items.img" alt="error" class="info-product_img">
                <div class="info-product_name">{{ items.name }}</div>
                <div class="info-product_fee">{{ $vali.formatCurrency(items.fee) }}</div>
                <div class="info-product_detail">
                  <nuxt-link :to="`/product/${items.product_code}`" class="detail-txt">Xem chi tiết</nuxt-link>
                </div>
              </div>
            </div>
          </div>
      </div>

      <b-toast
        ref="customToast"
        no-auto-hide
        :variant="toastVariant"
        class="my-custom-toast"
        >
        <template #toast-title>
            <strong>Thông báo</strong>
        </template>
        <span class="my-custom-toast-message">{{ toastMessage }}</span>
      </b-toast>
    </b-container>
</template>
  
<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import productApi from '~/plugins/api/listProduct';
  export default {
    name: 'ProductAo',
    components: {
        Loading
    },
    data() {
      return {
        listProduct : [],
        isLoading: false,
        toastVariant: "info",
        toastMessage: null,
        selectedProduct:null,
        productBody: [
          { value: 1, text: 'Giá tăng dần' },
          { value: 2, text: 'Giá giảm dần' },
        ],
        sortBy: 1,
      }
    },
    watch: {
      listProduct: 'sortProducts',
    },
    created() {
      this.listUserItem();
    },
    methods: {
      toggleSortOrder() {
        this.sortBy = this.sortBy === 'asc' ? 'desc' : 'asc';
        this.sortProducts();
      },

      sortProducts() {
        if (this.sortBy === 'asc') {
          this.listProduct.sort((a, b) => a.fee - b.fee);
        } else if (this.sortBy === 'desc') {
          this.listProduct.sort((a, b) => b.fee - a.fee);
        }
      },

      async listUserItem() {
        this.isLoading = true;
        await productApi.getListProduct()
          .then((res) => {
              this.listProduct = res.data;
              this.isLoading = false;
          })
          .catch((err) => {
              this.isLoading = false;
          });
      },
  
      showToast(variant, message) {
        this.toastVariant = variant;
        this.toastMessage = message;
        this.$refs.customToast.show();
  
        setTimeout(() => {
            this.$refs.customToast.hide();
        }, 3000);
      },
  
  
    },
  }
</script>
  
<style lang="scss" scoped>
  @import "@/assets/styles/main.scss";
  
  #login-label {
    color: $text-color;
    margin-top: 8px;
  }
  ::v-deep #lang {
    width: 100%;
    border: 1px solid $border;
    padding: 0 8px;
  
  }
  .content {
    max-width: 1440px;
    width: 100%;
    margin: auto;
  
  }
  .product-first {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    margin-bottom: 50px;
    &_title {
      color: $text-color;
      font-size: 20px;
      font-weight: 550;
    }
  
    &_select {
      width: 20%;
      color: $text-color;
      font-size: 16px;
      font-weight: 550;
    }
  }
  .detail-txt {
    color: $text-color;
  }
  .product-body {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 30px;
    margin-bottom: 50px;
  }
  .info-product {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    width: 320px;
    height: 560px;
    border-radius: 20px;
    img {
      width: 100%;
      height: 420px;
      border-radius: 20px;
    }
    &_name {
      padding: 16px;
      color: $text-color;
      font-size: 16px;
      font-weight: 550;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    &_fee {
      margin-top: auto;
      padding: 0 16px;
      color: $bgc-icon;
      font-size: 16px;
      font-weight: 550;
    }
    &_detail {
      margin-top: auto;
      padding: 16px;
      color: $text-color;
      font-size: 16px;
    }
  }
</style>
  