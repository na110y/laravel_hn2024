<template>
    <b-container fluid>

        <loading
        :active.sync="isLoading"
        :can-cancel="true"
        :is-full-page="true"
        ></loading>

        <div class="content">
            <div class="content-title">Thông tin đơn hàng</div>
            <div class="content-pending">
                <b-card no-body>
                    <b-tabs card>
                        <b-tab title="Chờ xử lý" active>
                            <b-card-text> 
                                <div class="listProductPending">
                                    <b-card class="mt-3">
                                        <b-row fluid sm="auto">
                                            <b-col>
                                                <b-table striped hover :items="listProduct" :fields="fields">

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

                                                </b-table>
                                            </b-col>
                                        </b-row>
                                    </b-card>
                                </div>
                            </b-card-text>
                        </b-tab>
                        <b-tab title="Chờ giao hàng">
                            <b-card-text b-card-text>Tab contents 2</b-card-text>
                        </b-tab>
                        <b-tab title="Hoàn thành">
                            <b-card-text b-card-text>Tab contents 3</b-card-text>
                        </b-tab>
                        <b-tab title="Đã hủy">
                            <b-card-text b-card-text>Tab contents 4</b-card-text>
                        </b-tab>
                        <b-tab title="Trả hàng && hoàn tiền">
                            <b-card-text b-card-text>Tab contents 5</b-card-text>
                        </b-tab>
                    </b-tabs>
                </b-card>
            </div>
        </div>
        
    </b-container>
</template>

<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import pendingApi from '~/plugins/api/listPending';
export default {
    components: {
        Loading,
    },
    data() {
        return {
            isLoading: false,
            toastVariant: "info",
            toastMessage: null,
            listProduct: [],
            step : [
                { value: 0, stepName: 'Chờ xử lý' },
                { value: 1, stepName: 'Chờ giao hàng' },
                { value: 2, stepName: 'Hoàn thành' },
                { value: 3, stepName: 'Đã hủy' },
                { value: 4, stepName: 'Trả hàng && hoàn tiền' },
            ],
            fields: [
                { key: "id", label: "ID", sortable: false, thClass: 'text-center'},
                { key: "product_code", label: "Mã", sortable: false, thClass: 'text-center' },
                { key: "product_name", label: "Tên", sortable: false, thClass: 'text-center' },
                { key: "note", label: "Sale", sortable: false, thClass: 'text-center' },
                { key: "size", label: "Size", sortable: false, thClass: 'text-center'},
                { key: "product_price", label: "Giá", sortable: false, thClass: 'float-right'},
                { key: "staff", label: "Người mua", sortable: false, thClass: 'text-center' },
            ],

        }
    },
    created() {
        this.listPending();
    },
    methods: {
        listPending() {
            this.isLoading = true;
            pendingApi.listProductPending()
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

  .content{
    max-width: 1440px;
    width: 100%;
    margin: auto;
    &-title {
        margin-top: 30px;
        color: $text-color;
        font-size: 20px;
        font-weight: 550;
    }
    &-pending {
        margin-top: 20px;
    }
  }

  ::v-deep a {
    color: $text-color !important;
    font-size: 16px;
    font-weight: 550;
  }

  ::v-deep .nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
    color: $bgc-icon !important;
  }

  ::v-deep .card-header-tabs {
    display: flex;
    justify-content: center;
  }
  .listProductPending {
    padding: 16px;
  }
</style>