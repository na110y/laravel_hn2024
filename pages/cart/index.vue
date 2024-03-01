<template>
    <b-container fluid>
  
      <loading
        :active.sync="isLoading"
        :can-cancel="true"
        :is-full-page="true"
      ></loading>

        <div class="content" v-if="listProductCart.length > 0">
            <div class="cart">
                <div class="cart-title"> Giỏ hàng của bạn </div>
                <div class="cart-body" >
                    <div class="btn-transform">
                        <div class="search" v-show="isShowCartTable">
                            <b-form-group>
                                <b-form-input id="search-product" placeholder="Tìm kiếm theo từ khóa..." v-model="search.product_code" @keyup.enter="onClickSearch"></b-form-input>
                            </b-form-group>
                        </div>
                        <b-icon icon="justify" aria-hidden="true" v-show="isShowCartTable" scale="2" @click.prevent="btnJustify"></b-icon>
                    </div>
                    <div class="btnShowGrid">
                        <b-icon icon="grid" aria-hidden="true" v-show="isShowCartColumn" scale="2" @click.prevent="btnGrid" ></b-icon>
                    </div>
                    <b-card class="mt-3" v-show="isShowCartTable">
                        <b-row fluid sm="auto">
                            <b-col>
                                <b-table striped hover :items="listProductCart" :fields="fields">

                                    <template #cell(id)="row">
                                        <div class="text-center">{{ row.item.id }}</div>
                                    </template>

                                    <template #cell(product_code)="row">
                                        <div class="text-center">{{ row.item.product_code }}</div>
                                    </template>

                                    <template #cell(product_name)="row">
                                        <div class="text-center">{{ row.item.product_name }}</div>
                                    </template>

                                    <template #cell(note)="row">
                                        <div class="text-center">{{ row.item.note }}</div>
                                    </template>

                                    <template #cell(size)="row">
                                        <div class="text-center">{{ row.item.size }}</div>
                                    </template>

                                    <template #cell(staff)="row">
                                        <div class="text-center">{{ row.item.staff }}</div>
                                    </template>

                                    <template #cell(product_price)="row" >
                                        <div class="float-right" style="color: #FE616B">{{ $vali.formatCurrency(row.item.product_price) }}</div>
                                    </template>

                                    <template #cell(action)="row">
                                        <div class="action-table">
                                            <div class="btn-table">
                                                <b-button variant="danger" class="btn-table_delete" size="sm" @click="btnDelete(row.item)">
                                                    <b-icon icon="x-square" aria-hidden="true"></b-icon>
                                                    Xóa sản phẩm
                                                </b-button>
                                            </div>
                                        </div>
                                    </template>
                                </b-table>
                            </b-col>
                        </b-row>
                    </b-card>

                    <div class="mt-3" v-show="isShowCartColumn">

                        <div class="product-body">
                            <div class="info-product" v-for="(items, index) in listProductCart" :key="index + 'product'">
                                <img :src="items.img" alt="error" class="info-product_img">
                                <div class="info-product_name">{{ items.product_name }}</div>
                                <div class="info-product_fee">{{ $vali.formatCurrency(items.product_price) }}</div>
                                <div class="info-product_detail">
                                    <b-button class="btnDeleteCart" @click="btnDelete(items)">Xóa</b-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <b-card class="mt-3">
                <b-row class="mt-2">
                    <b-col cols="12">
                        <pagination
                            :perPage="pagination.per_page"
                            :total="pagination.total"
                            @pagechanged="onPaginateChange"
                            :totalPages="pagination.total_page"
                            :currentPage="pagination.current_page"
                        ></pagination>
                    </b-col>
                </b-row>
            </b-card>

            <div class="selectPayments">
                <b-form-group id="login-label" label="Chọn phương thức thanh toán" label-for="search-available" class="mt-2">
                    <b-form-select v-model="selectedPayment" id="selectPayment" @change="selectPayment">
                        <option v-for="(item, index) in selectPaymentMethod" :value="item.value" :key="index + 'text'">
                            {{ item.text  }}
                        </option>
                    </b-form-select>
                </b-form-group>
            </div>
            <div class="cart-btn">
                <b-button class="btnSubmit" variant="primary" @click="addProductCart">
                    Đặt hàng 
                </b-button>
            </div>

            
        </div>
        <div class="productNull" v-else>
            <div class="productNull-title">Không có sản phẩm nào trong giỏ hàng!</div>
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
    import cartApi from '~/plugins/api/listCart';
    import Pagination from "@/components/paginate/index.vue";
    import { EventBus } from '~/plugins/event-bus.js';
    export default {
        name: 'ProductCart',
        components: {
            Loading,
            Pagination
        },
        data() {
            return {
                isLoading: false,
                toastVariant: "info",
                toastMessage: null,
                isShowCartTable: true,
                isShowCartColumn: false,
                fields: [
                    { key: "id", label: "ID", sortable: false, thClass: 'text-center'},
                    { key: "product_code", label: "Mã", sortable: false, thClass: 'text-center' },
                    { key: "product_name", label: "Tên", sortable: false, thClass: 'text-center' },
                    { key: "note", label: "Sale", sortable: false, thClass: 'text-center' },
                    { key: "size", label: "Size", sortable: false, thClass: 'text-center'},
                    { key: "product_price", label: "Giá", sortable: false, thClass: 'float-right'},
                    { key: "staff", label: "Người mua", sortable: false, thClass: 'text-center' },
                    { key: "action", label: "Hành động", sortable: false, thClass: 'text-center' },
                ],
                listProductCart: [],
                finalizedPproduct: {
                    product_code: '',
                    product_name: '',
                    note: '',
                    size: '',
                    product_price: '',
                    staff: '',
                },
                pagination: {
                    total_page: 0,
                    total: 0,
                    per_page: 8,
                    current_page: 1,
                },

                search:{
                    product_code: "",
                },
                selectPaymentMethod : [
                    { value: 1, text: 'Nhận hàng rồi thanh toán' },
                    { value: 2, text: 'Thanh toán thông qua ngân hàng' },
                ],
                selectedPayment: null,


            }
        },
        
        created() {
            EventBus.$on('listProductChanged', () => {
                this.listUserItem();
            });
            this.listUserItem();
        },
        methods: {

            // chọn phương thức thanh toán sản phẩm
            selectPayment() {
                this.selectedPayment
            },

            addProductCart() {
                this.isLoading = true;
                if (this.selectedPayment == null) {
                    return this.showToast('warning', 'vui lòng chọn phương thức thanh toán!'),  this.isLoading = false; 
                }
                const params = {
                    listProduct : this.listProductCart,
                    selectedPayment: this.selectedPayment
                }
                cartApi.postConfirms(params)
                .then((res) => {
                    this.isLoading = false;
                    this.$router.push("/pending");
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.showToast('danger', 'Thất bại!');
                });
            },

            btnJustify() {
                this.isShowCartTable = !this.isShowCartTable;
                this.isShowCartColumn = !this.isShowCartColumn;
            },

            btnGrid() {
                this.isShowCartTable = !this.isShowCartTable;
                this.isShowCartColumn = !this.isShowCartColumn;
            },

            onClickSearch() {
                this.pagination.current_page = 1;
                this.listUserItem();
            },

            onPaginateChange(page) {
                this.pagination.current_page = page;
                this.listUserItem();
            },
            

            // lấy ra danh sách sản phẩm trong giỏ hàng
            async listUserItem() {
                this.isLoading = true;
                let params = {
                    page: this.pagination.current_page,
                    rows_per_page: this.pagination.per_page,
                    product_code: this.search.product_code,
                };

                await cartApi.getListProduct(params)
                    .then((res) => {
                        this.listProductCart = res.data.data || [];
                        this.isLoading = false;
                        let url_from = new URL(res.data.first_page_url);
                        let url_to = new URL(res.data.last_page_url);
                        const total_page = parseInt(url_to.searchParams.get("page")) - parseInt(url_from.searchParams.get("page")) + 1;
                        this.pagination = {
                            path: res.data.path,
                            total: res.data.total,
                            current_page: res.data.current_page,
                            per_page: parseInt(res.data.per_page),
                            total_page: total_page,
                        };

                    })
                    .catch((err) => {
                        this.isLoading = false;
                    });
            },

            // xóa sản phẩm trong giỏ hàng

            btnDelete(item) {
                if (confirm(`Bạn có chắc xóa sản phẩm có mã ID: ${item.id}`)) {
                    this.deleteProductCart(item);
                }

            },

            async deleteProductCart(item) {
                this.isLoading = true;
                const id = item.id;
                const res = await cartApi.productDeleteDetail(id)
                .then((res) => {
                    this.isLoading = false;
                    this.listUserItem();
                    EventBus.$emit('listProductChanged', res.data);
                    this.showToast('success', 'Xóa sản phẩm thành công!');
                })
                .catch((err) => {
                    this.showToast('danger', 'Xóa sản phẩm thất bại!');
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
.btnShowGrid {
    display: flex;
    justify-content: end;
    cursor: pointer;
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
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
}

#search-product {
    padding: 4px 8px;
    width: 350px;
}

.action-table {
    display: flex;
    align-items: center;
    gap: 0 12px;
}
.btn-table {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    &_delete {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 6px 12px;
        gap: 0 8px;
    }
}

.cart-btn {
    display: flex;
    justify-content: end;
    padding: 20px 0;
    .btnSubmit {
        padding: 12px 24px;
    }
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
    height: 580px;
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
      margin-bottom: 20px;
      padding: 0 16px;
      color: $bgc-icon;
      font-size: 16px;
      font-weight: 550;
    }
    &_detail {
        padding: 0 16px;
    }
    .btnDeleteCart {
        padding: 8px 20px;
        width: 100%;
        margin-top: auto;
    }
}
.selectPayments {
    display: flex;
    justify-content: end;
    margin-top: 20px;
}
::v-deep #selectPayment {
    width: 250px;
    border: 1px solid $border;
    padding: 0 8px;
}

#login-label{
    color: $text-color;
}

.productNull {
    display: flex;
    justify-content: center;
    margin-top: 100px;
    &-title {
        color: $bgc-icon;
        font-size: 2rem;
        font-weight: 550;
    }
}
</style>
  