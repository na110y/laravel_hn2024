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

                                    <template #cell(action)="row" class="">
                                        <div class="action-table">
                                            <div class="btn-table">
                                                <b-button variant="danger" class="btn-table_delete" size="sm" @click="deleteProductCart(row.item)">
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
                        Đặt hàng 
                    </b-button>
                </div>
            </div>
        </div>
    </b-container>
</template>
  
<script>
    import Loading from "vue-loading-overlay";
    import "vue-loading-overlay/dist/vue-loading.css";
    import cartApi from '~/plugins/api/listCart';
    export default {
        name: 'ProductCart',
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
            }
        }
        },
        created() {
            this.listUserItem();
        },
        methods: {
            addProductCart() {
                const param = {
                    product_code : this.finalizedPproduct.product_code,
                    product_name : this.finalizedPproduct.product_name,
                    note : this.finalizedPproduct.note,
                    size : this.finalizedPproduct.size,
                    product_price : this.finalizedPproduct.product_price,
                    staff : this.finalizedPproduct.staff,
                }
                console.log(param);
            },

            btnJustify() {
                this.isShowCartTable = !this.isShowCartTable;
                this.isShowCartColumn = !this.isShowCartColumn;
            },

            btnGrid() {
                this.isShowCartTable = !this.isShowCartTable;
                this.isShowCartColumn = !this.isShowCartColumn;
            },

            // lấy ra danh sách sản phẩm trong giỏ hàng
            async listUserItem() {
                this.isLoading = true;
                await cartApi.getListProduct()
                .then((res) => {
                    this.listProductCart = res.data;
                    this.isLoading = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                });
            },

            // xóa sản phẩm trong giỏ hàng

            async deleteProductCart(item) {
                this.isLoading = true;
                const id = item.id;
                await cartApi.productDeleteDetail(id)
                .then((res) => {
                    this.isLoading = false;
                    this.listUserItem();
                    this.showToast('success', 'Xóa sản phẩm thành công!');
                })
                .catch((err) => {
                    this.showToast('info', 'Xóa sản phẩm thất bại!');
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
</style>
  