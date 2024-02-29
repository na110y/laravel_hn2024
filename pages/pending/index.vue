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
                            <b-card-text>Tab contents 1</b-card-text>
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
            listProductPending: [],
        }
    },
    created() {
        this.listProductPending();
    },
    methods: {

        async listProductPending() {
            this.isLoading = true;
            await pendingApi.listProductPending()
            .then((res) => {
                this.listProductPending = res.data;
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
</style>