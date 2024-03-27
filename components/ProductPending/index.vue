<template>
    <div>
        <loading
            :active.sync="isLoading"
            :can-cancel="true"
            :is-full-page="true"
        ></loading>

        <b-modal id="modal-1" title="BootstrapVue" tabindex="-1" v-model="detailProductUser">
            <template #modal-header>
                <div class="d-flex">
                    <h5 class="m-0">Danh sách sản phẩm chờ xử lý</h5>
                </div>
            </template>
            <b-form class="w-100">
                <div class="content">
                    <b-card class="product">
                        <div >
                            <b-table striped hover :items="listProduct" :fields="fields" @row-clicked="selectItem">
                                <template #cell(product_code)="row">
                                    <div class="text-center">{{ row.item.product_code }}</div>
                                </template>

                                <template #cell(product_name)="row">
                                    <div class="text-center">{{ row.item.product_name }}</div>
                                </template>

                                <template #cell(product_price)="row">
                                    <div class="text-center">{{ $vali.formatCurrency(row.item.product_price) }}</div>
                                </template>
                            </b-table>
                        </div>
                    </b-card>

                    <b-card class="step">
                        <div>
                            <table class="table step-item">
                                <tbody>
                                    <tr v-for="(step, index) in listStep" :key="index" :class="{ 'colorStep': step.step <= selectedItem.classStep, 'secondary': isColorStep()}">
                                        <th>{{ step.step_name }}</th>
                                        <th v-if="step.step <= selectedItem.classStep">{{ isAdmin }}</th>
                                        <th v-if="step.step <= selectedItem.classStep"><b-icon icon="check" scale="2" aria-hidden="true"></b-icon></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </b-card>

                </div>
            </b-form>

            <template #modal-footer>
                <div class="action-table">
                    <div class="btn-table">
                        <b-button class="btn-table_delete" size="sm" @click="close">
                            Hủy
                        </b-button>
                        <b-button variant="info" :disabled="selectedItem === null || selectedItem.classStep == 4" class="btn-table_delete" size="sm" @click="btnHandleProduct()">
                            Xử lý
                        </b-button>
                    </div>
                </div>
            </template>
        </b-modal>
        <Toast :show-toast="toastData" />
    </div>
</template>

<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import pendingApi from '~/plugins/api/listPending';
import Toast from '@/components/Toast/index.vue';
export default {
    name: 'modal-user',
    props: ['detailProductUser', 'detailUser'],
    components: {
        Loading,
        pendingApi,
        Toast
    },
    data() {
        return {
            toastData: null,
            listProduct: [],
            fields: [
                { key: "id", label: "ID", sortable: true, thClass: 'text-center'},
                { key: "product_name", label: "Tên Sản Phẩm", sortable: true, thClass: 'text-center' },
                { key: "product_price", label: "Giá", sortable: true, thClass: 'text-center' },
            ],
            selectedItem: {
                id:'',
                classStep: ''
            },
            classStep: null,
            rowKey: 'id',
            listStep : [
                { step: 0, step_name: 'Chờ xử lý' },
                { step: 1, step_name: 'Chờ giao hàng'},
                { step: 2, step_name: 'Hoàn thành'},
                { step: 3, step_name: 'Đã hủy'},
                { step: 4, step_name: 'Trả hàng && hoàn tiền'},
            ],
            isAdmin: '',
            selectedItemId: null
        }
    },
    created() {
        this.listItemProduct();
        if (this.$auth.user) {
            this.isAdmin = this.$auth.user.name;
        }
    },
    methods: {
        async listItemProduct() {
            this.isLoading = true;
            const param = {
                id: this.detailUser
            }
            await pendingApi.getAllProductPending(param)
                .then((res) => {
                    this.listProduct = res.data || [];
                    console.log(this.classStep);
                    this.isLoading = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },

        selectItem(item) {
            this.selectedItem.id = item.id;
            this.selectedItem.classStep = item.classStep;
            this.selectedItemId = item.id;
        },


        btnHandleProduct() {
            const param = {
                id: this.selectedItem
            }
            pendingApi.nextStep(param)
                .then((res) => {
                    this.selectedItem.classStep ++;
                    this.listItemProduct();
                    this.toastData = { variant: 'success', message: 'Thành công!' };
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
        close() {
            this.$emit('cancel', false);
        },

        isColorStep() {
            if (this.selectedItem.classStep == 4) {
                return "secondary";
            }
        },
    },
}
</script>
<style lang="scss" scoped>
@import "@/assets/styles/main.scss";

.action-table {
    display: flex;
    align-items: center;
    gap: 0 12px;
}

.secondary {
    background-color: $text-color--maganer !important;
    color: $bgc-body !important;
}

::v-deep .modal-dialog {
    max-width: 100%;
    width: 70% !important;
}
.content {
    display: flex;
    gap: 0 8px;
}
.product {
    padding: 16px;
    width: 60%;
    height: 70vh !important;
}
.step {
    display: flex;
    padding: 8px;
    width: 40%;
    &-item {
        padding: 10px 8px;
        border-bottom: 1px solid $bgc-body;
        color: $news-title;
        background-color: $bgc-service;
    }
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
.colorStep {
    background: rgb(190, 235, 124);
    color: $service;
}

</style>