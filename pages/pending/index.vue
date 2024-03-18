<template>
    <b-container fluid>

        <loading
        :active.sync="isLoading"
        :can-cancel="true"
        :is-full-page="true"
        ></loading>

        <div class="content">
            <div class="content_head">
                <div class="content-title">Thông tin đơn hàng</div>
                <div class="content_head-excel">
                    <b-button variant="primary" @click="onClickExport()">
                        Export Excel
                        <b-icon icon="cloud-plus" aria-hidden="true" scale="1" id="icon"></b-icon>
                    </b-button>
                    <b-button variant="success" @click="onClickPDF()">
                        Export PDF
                        <b-icon icon="cloud-plus" aria-hidden="true" scale="1" id="icon"></b-icon>
                    </b-button>
                </div>
            </div>
            <div class="content-pending" v-if="listProduct.length > 0">
                <b-card no-body>
                    <b-tabs card>
                        <b-tab v-for="(step, index) in list_step" :key="index" :title="step.stepName" @click="changeStep(step.step)">
                            <div class="listProductPending">
                                    <b-card class="mt-3">
                                        <b-row fluid sm="auto">
                                            <b-col>
                                                <b-table v-if="step.step == currentStep" striped hover :items="filteredList" :fields="fields">
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

                                                    <template #cell(status)="row">
                                                        <div class="text-center">
                                                            <b :class="variantStatus(row.item.status)" >
                                                                {{ statusName(row.item.status) }}
                                                            </b>
                                                        </div>
                                                    </template>
                                                    
                                                    <template #cell(staff)="row">
                                                        <div class="text-center">{{ row.item.staff }}</div>
                                                    </template>

                                                    <template #cell(product_price)="row" >
                                                        <div class="float-right" style="color: #FE616B">{{ $vali.formatCurrency(row.item.product_price) }}</div>
                                                    </template>

                                                    <template #cell(action)="row" v-if="step.step === 1">
                                                        <div class="text-center">
                                                            <div class="btn-table">
                                                                <b-button variant="danger" class="btn-table_delete" size="sm" @click="btnCancelProduct(row.item)">
                                                                    Hủy đơn
                                                                </b-button>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </b-table>
                                            </b-col>
                                        </b-row>
                                    </b-card>
                                </div>
                        </b-tab>
                    </b-tabs>
                </b-card>
            </div>

            <div v-else class="content-text">Chưa có đơn hàng nào được xử lý!</div>
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
            list_step : [
                { step: 0, stepName: 'Chờ xử lý' },
                { step: 1, stepName: 'Chờ giao hàng' },
                { step: 2, stepName: 'Hoàn thành' },
                { step: 3, stepName: 'Đã hủy' },
                { step: 4, stepName: 'Trả hàng && hoàn tiền' },
            ],
            fields: [
                { key: "id", label: "ID", sortable: false, thClass: 'text-center'},
                { key: "product_code", label: "Mã", sortable: false, thClass: 'text-center' },
                { key: "product_name", label: "Tên", sortable: false, thClass: 'text-center' },
                { key: "note", label: "Sale", sortable: false, thClass: 'text-center' },
                { key: "size", label: "Size", sortable: false, thClass: 'text-center'},
                { key: "product_price", label: "Giá", sortable: false, thClass: 'float-right'},
                { key: "status", label: "Trạng thái", sortable: false, thClass: 'text-center'},
                { key: "staff", label: "Người mua", sortable: false, thClass: 'text-center' },
                { key: "action", label: "Hủy đơn", sortable: false, thClass: 'text-center' },
            ],
            currentStep: 0,
            export: {
                product_code: "",
                step: null,
            },

        }
    },
    created() {
        this.listPending();
    },
    computed: {
        filteredList() {
            return this.listProduct.filter(item => item.step === this.currentStep);
        },
    },
    methods: {
        changeStep(step) {
            this.currentStep = step;
        },
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

        /**
         * @description: hủy đơn hàng
         */
        btnCancelProduct(item) {
            this.isLoading = true;
            const param = {
                id: item.id
            };
            pendingApi.handleCancelProduct(param)
                .then((res) => {
                    this.listPending();
                    this.isLoading = false;
                    this.showToast("success", "Hủy đơn hàng thành công!");
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

        statusName(status) {
            switch (status) {
                case 0:
                    return "Chờ xử lý";
                case 1:
                    return "Đang giao hàng";
                case 2:
                    return "Thành công";
                case 3:
                    return "Đã hủy";
                case 100:
                    return "Đã hoàn tiền";
            }
        },
        variantStatus(status) {
            if(status < 0) return "text-danger";
            else if(status == 0) return "text-warning";
            else if(status == 1) return "text-primary";
            else if(status == 2) return "text-success";
            else if(status == 3) return "text-danger";
            else if(status == 100) return "text-success";
        },

        onClickExport() {
            let href = 'http://localhost:8000/api/export/export-excel-registration?';
            for (const [key, value] of Object.entries(this.export.product_code, this.export.step)) {
                href += `${key}=${value}&`;
            }
            window.open(href, "_blank");
        },

        onClickPDF() {
            let href = 'http://localhost:8000/api/export/export-pdf?';
            for (const [key, value] of Object.entries(this.export.product_code, this.export.step)) {
                href += `${key}=${value}&`;
            }
            window.open(href, "_blank");
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
    &_head {
        display: flex;
        justify-content: space-between;
        &-excel {
            display: flex;
            gap: 0 16px;
            align-items: center;   
            button {
                padding: 4px 8px;
            }
        }
    }
    &-title {
        margin-top: 30px;
        color: $text-color;
        font-size: 20px;
        font-weight: 550;
    }
    &-pending {
        margin-top: 20px;
    }
    &-text {
        color: $bgc-icon;
        font-weight: 550;
        font-size: 20;
        display: flex;
        justify-content: center;
        margin-top: 30px;
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
  .btn-table_delete {
    padding: 8px 12px;
  }
</style>