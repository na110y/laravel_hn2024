<template>
    <b-container fluid>
  
      <loading
        :active.sync="isLoading"
        :can-cancel="true"
        :is-full-page="true"
      ></loading>

        <div class="content" v-if="listUser.length > 0">
            <div class="cart">
                <div class="cart-title"> Danh sách người dùng </div>
                <div class="cart-body" >
                    <div class="btn-transform">
                        <div class="search">
                            <b-form-group>
                                <b-form-input id="search-product" placeholder="Tìm kiếm theo từ khóa..." v-model="search.keywords" @keyup.enter="onClickSearch"></b-form-input>
                            </b-form-group>
                        </div>
                    </div>
                    <b-card class="mt-3">
                        <b-row fluid sm="auto">
                            <b-col>
                                <b-table striped hover :items="listUser" :fields="fields">

                                    <template #cell(id)="row">
                                        <div class="text-center">{{ row.item.id }}</div>
                                    </template>

                                    <template #cell(fullName)="row">
                                        <div class="text-center">{{ row.item.fullName }}</div>
                                    </template>

                                    <template #cell(adress)="row">
                                        <div class="text-center">{{ row.item.adress }}</div>
                                    </template>

                                    <template #cell(sdt)="row">
                                        <div class="text-center">{{ row.item.sdt }}</div>
                                    </template>

                                    <template #cell(gender)="row">
                                        <div class="text-center">{{ $vali.formatGender(row.item.gender) }}</div>
                                    </template>

                                    <template #cell(dateOfBirst)="row">
                                        <div class="text-center">{{ $vali.formatDate(row.item.dateOfBirst) }}</div>
                                    </template>

                                    <template #cell(created_at)="row" >
                                        <div class="float-right" >{{ $vali.formatDate(row.item.created_at) }}</div>
                                    </template>

                                    <template #cell(staff)="row">
                                        <div class="text-center">{{ row.item.staff }}</div>
                                    </template>

                                    <template #cell(action)="row">
                                        <div class="action-table">
                                            <div class="btn-table">
                                                <b-button variant="info" class="btn-table_delete" size="sm" @click="btnProductHandle(row.item)">
                                                    <b-icon icon="pen" aria-hidden="true"></b-icon>
                                                    Xử lý
                                                </b-button>
                                                <b-button variant="danger" class="btn-table_delete" size="sm" @click="btnDelete(row.item)">
                                                    <b-icon icon="x-square" aria-hidden="true"></b-icon>
                                                    Xóa
                                                </b-button>
                                            </div>
                                        </div>
                                    </template>
                                </b-table>
                            </b-col>
                        </b-row>
                    </b-card>
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
            
        </div>
        <div class="productNull" v-else>
            <div class="productNull-title">Không có thông tin người dùng!</div>
        </div>

        <product-pending
            :detail-product-user="showModalDetailUser"
            :detail-user="detailProductUser"
            @cancel="isShowModal"
        />
        <Toast :show-toast="toastData" />
    </b-container>
</template>
  
<script>
    import Loading from "vue-loading-overlay";
    import "vue-loading-overlay/dist/vue-loading.css";
    import userApi from '~/plugins/api/userApi';
    import Pagination from "@/components/paginate/index.vue";
    import { EventBus } from '~/plugins/event-bus.js';
    import Toast from '@/components/Toast/index.vue';
    import ProductPending from '@/components/ProductPending/index.vue';
    export default {
        name: 'ProductCart',
        components: {
            Loading,
            Pagination,
            Toast,
            ProductPending
        },
        data() {
            return {
                isLoading: false,
                fields: [
                    { key: "id", label: "ID", sortable: false, thClass: 'text-center'},
                    { key: "fullName", label: "Họ và tên", sortable: false, thClass: 'text-center' },
                    { key: "adress", label: "Địa chỉ", sortable: false, thClass: 'text-center' },
                    { key: "sdt", label: "Số điện thoại", sortable: false, thClass: 'text-center' },
                    { key: "gender", label: "Giới tính", sortable: false, thClass: 'text-center'},
                    { key: "dateOfBirst", label: "Ngày sinh", sortable: false, thClass: 'float-right'},
                    { key: "created_at", label: "Ngày tạo", sortable: false, thClass: 'text-center' },
                    { key: "staff", label: "Người tạo", sortable: false, thClass: 'text-center' },
                    { key: "action", label: "Hành động", sortable: false, thClass: 'text-center' },
                ],
                listUser: [],
                pagination: {
                    total_page: 0,
                    total: 0,
                    per_page: 8,
                    current_page: 1,
                },

                search:{
                    keywords: "",
                },
                toastData: null,
                isAdmin: '',
                showModalDetailUser: false,
                detailProductUser: null,
            }
        },
        mounted() {
            if (this.$auth.user) {
                this.isAdmin = this.$auth.user.role;
            }
        },
        created() {
            EventBus.$on('listProductChanged', () => {
                this.listUserItem();
            });
            this.listUserItem();
        },
        methods: {

            onClickSearch() {
                this.pagination.current_page = 1;
                this.listUserItem();
            },

            onPaginateChange(page) {
                this.pagination.current_page = page;
                this.listUserItem();
            },
            
            // Chi tiết sản phẩm của người dùng đang chờ xử lý
            btnProductHandle(item) {
                this.btnShowModal(item)
            },

            isShowModal(showModalDetailUser) {
                this.showModalDetailUser = showModalDetailUser;
            },

            btnShowModal(item) {
                this.detailProductUser = item.id;
                this.isShowModal(true);
            },

            // lấy ra danh sách người dùng
            async listUserItem() {
                this.isLoading = true;
                let param = {
                    page: this.pagination.current_page,
                    rows_per_page: this.pagination.per_page,
                    keywords: this.search.keywords,
                };

                await userApi.getAllUser(param)
                    .then((res) => {
                        this.listUser = res.data.data || [];
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

            async btnDelete(item) {
                if (confirm(`Bạn có chắc xóa sản phẩm : ${item.fullName}`)) {
                    await this.deleteUserDetail(item);
                }

            },

            async deleteUserDetail(item) {
                this.isLoading = true;
                const id = item.id;
                const res = await userApi.deleteUser(id)
                .then((res) => {
                    this.isLoading = false;
                    this.listUserItem();
                    this.toastData = { variant: 'success', message: 'Xóa người dùng thành công!' };
                })
                .catch((err) => {
                    this.isLoading = false;
                    this.toastData = { variant: 'danger', message: 'Xóa người dùng thất bại!' };
                });
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
    gap: 0 8px;
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
.validationSale{
    color: $primary-color;
    font-weight: 550;
}
</style>
  