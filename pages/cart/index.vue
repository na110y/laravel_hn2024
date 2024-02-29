<template>
    <b-container fluid>
  
      <loading
        :active.sync="isLoading"
        :can-cancel="true"
        :is-full-page="true"
      ></loading>
  
        <div class="content">
            <div class="cart">
                <div class="cart-title"> Giỏ hàng của bạn </div>
                <div class="cart-body" >
                    <div class="btn-transform">
                        <b-icon icon="justify" aria-hidden="true" v-show="isShowCartTable" scale="2" @click.prevent="btnJustify"></b-icon>
                        <b-icon icon="grid" aria-hidden="true" v-show="isShowCartColumn" scale="2" @click.prevent="btnGrid"></b-icon>
                    </div>
                    <b-card class="mt-3" v-show="isShowCartTable">
                        <b-row fluid sm="auto">
                            <b-col>
                                <b-table striped hover :items="listProductCart" :fields="fields" id="list-items-ebook">
                                    <template #cell(action)="row">
                                        <div class="d-flex">
                                            <b-button variant="danger" class="d-flex w-50" size="sm" @click="onclickSubmitDeleteItem(row.item)">
                                                <i class="fas fa-trash"></i>
                                                Xóa sản phẩm
                                            </b-button>
                                        </div>
                                    </template>
                                </b-table>
                            </b-col>
                        </b-row>
                    </b-card>

                    <b-card class="mt-3" v-show="isShowCartColumn">
                        <b-row fluid sm="auto">
                            <b-col>
                                zxczxc
                            </b-col>
                        </b-row>
                    </b-card>
                </div>


                <div class="cart-btn">
                    <b-button class="btnSubmit" variant="primary" @click="addProductCart()">
                        Mua 
                    </b-button>
                </div>
            </div>
        </div>
    </b-container>
</template>
  
<script>
  import Loading from "vue-loading-overlay";
  import "vue-loading-overlay/dist/vue-loading.css";
  import productApi from '~/plugins/api/listProduct';
  export default {
    name: 'IndexPage',
    components: {
      Loading
    },
    data() {
      return {
        isLoading: false,
        toastVariant: "info",
        toastMessage: null,
        isShowCartTable: true,
        isShowCartColumn: false,
        fields: [
            { key: "id", label: "ID", sortable: false},
            { key: "product_code", label: "Mã", sortable: false },
            { key: "product_name", label: "Tên", sortable: false },
            { key: "product_price", label: "Giá", sortable: false},
            { key: "note", label: "Sale", sortable: false },
            { key: "size", label: "Size", sortable: false,},
            { key: "staff", label: "Người mua", sortable: false },
            { key: "action", label: "Hành động", sortable: false },
        ],
        listProductCart: []
      }
    },
    created() {
      this.listUserItem();
    },
    methods: {
        btnJustify() {
            this.isShowCartTable = !this.isShowCartTable;
            this.isShowCartColumn = !this.isShowCartColumn;
        },

        btnGrid() {
            this.isShowCartTable = !this.isShowCartTable;
            this.isShowCartColumn = !this.isShowCartColumn;
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

.content {
    max-width: 1440px;
    width: 100%;
    margin: auto;
}

.cart {
    &-title {
        margin-top: 20px;
        margin-bottom: 50px;
        text-align: center;
        color: $text-color;
        font-size: 22px;
        font-weight: 550;
    }
    &-body {

    }
}

.btn-transform {
    display: flex;
    justify-content: end;
    cursor: pointer;
}
</style>
  