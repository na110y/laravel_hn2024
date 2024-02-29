<template>
    <b-container fluid>
        <loading
            :active.sync="isLoading"
            :can-cancel="true"
            :is-full-page="true"
        ></loading>
        <b-card>
            <div class="custom-style">
                <div class="info_product">
                    <div class="product">
                        <div class="product_img">
                            <img :src="detailProduct.img" alt="error-icon" id="icon-product">
                        </div>
                    </div>
                </div>
                <div class="info_text">
                    <div class="product-titlee"></div>
                    <div class="list-text">
                        <ul class="list-item">
                            <li class="list-item_name">
                                {{ detailProduct.product_name }}
                            </li>
                            <li class="list-item_note">
                                <div> Sale: </div>
                                <div class="product_note-txt">{{ detailProduct.Note }} %</div>
                            </li>
                            <li class="list-item_fee">
                                <div> Chỉ còn: </div>
                                <div class="list-item_fee-fee">{{ $vali.formatCurrency(detailProduct.Fee) }}</div>
                            </li>

                            <li class="item-color">
                                <div> Thể loại: </div>
                                <div class="item-color_name">{{ selected.currentColorName }}</div>
                            </li>

                            <li class="list-item_other">
                                <div class="item_other" v-for="(items, index) in detailProductBranch" :key="index">
                                    <div class="item_other-img">
                                        <img :src="items.Img" alt="error-icon" id="icon-product" @click="changedImg(index)">
                                    </div>
                                </div>
                            </li>

                            <li class="list-item_sizeTXT">
                                <div> Kích cỡ: </div>
                            </li>
                            <li class="list-item_size">
                                <div v-for="(sizeItem, index) in itemSize" :key="index + 'size'">
                                    <div class="item_size" @click="btnChangedSize(index)" :class="{ 'selectOptionSize': index === selectedSizeIndex }"> {{ sizeItem.nameSize }}</div>
                                </div>
                            </li>

                        </ul>
                    </div>

                    <div class="btn">
                        <div class="">
                            <b-button class="btnSubmit" variant="primary" @click="addProductCart()">
                                Thêm vào giỏ hàng
                            </b-button>
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
        </b-card>
    </b-container>
</template>

<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import productApi from '~/plugins/api/listProduct';
import cartApi from '~/plugins/api/listCart';

export default {
    name: 'ProductDetail',
    components: {
        Loading
    },
    data() {
        return {
            detailProduct:{
                img: "",
                product_name: "",
                note: "",
                Fee: "",
                sale: "",
            },
            selected : {
                currentColorName: "",
            },
            itemSize:[
                { id: 0, nameSize: 'S'},
                { id: 1, nameSize: 'M'},
                { id: 2, nameSize: 'L'},
                { id: 3, nameSize: 'XL'},
            ],
            detailProductBranch:[],
            selectedSizeIndex: null,
            toastVariant: "info",
            toastMessage: null,
            isLoading: false,

        }
    },
    created() {
        this.getDetailProduct();
    },
    methods:{
        showToast(variant, message) {
            this.toastVariant = variant;
            this.toastMessage = message;
            this.$refs.customToast.show();

            setTimeout(() => {
                this.$refs.customToast.hide();
            }, 3000);
        },

        async getDetailProduct() {
            this.isLoading = true;
            const productCode = this.$route.params.id;
            await productApi.detailProduct(productCode)
                .then((res) => {
                    this.detailProduct = res.data.detailProduct;
                    this.detailProductBranch = res.data.detailProductBranch;
                    this.isLoading = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },


        /**
         * @description: Thay đổi các dạng áo khi click
         */
        changedImg(index) {
            this.detailProduct.img = this.detailProductBranch[index].Img;
            this.detailProduct.product_name = this.detailProductBranch[index].productName;
            this.detailProduct.note = this.detailProductBranch[index].Note;
            this.detailProduct.fee = this.detailProductBranch[index].Fee;
        },

        /**
         * @description: thay đổi kích thước của áo
         */
        btnChangedSize(index) {
            this.itemSize.id = this.itemSize[index].id;
            this.selectedSizeIndex = index;
        },
        
        /**
         * @description: thêm sản phẩm vào giỏ hàng
         */
        async addProductCart() {
            this.isLoading = true;
            const param = {
                product_code: this.$route.params.id,
                product_name: this.detailProduct.product_name,
                size: this.itemSize.id,
                note: this.detailProduct.note,
                img: this.detailProduct.img,
                fee: this.detailProduct.Fee,
            };
            await cartApi.productCart(param)
                .then((res) => {
                    this.showToast("success", "Thêm thành công vào giỏ hàng!");
                    this.isLoading = false;
                })
                .catch((err) => {
                    this.isLoading = false;
                });
        },
    }
}
</script>

<style lang="scss" scoped>
@import "@/assets/styles/main.scss";

.custom-style {
    display: flex;
    max-width: 1440px;
    width: 100%;
    margin: auto;
}
.product-title {
    padding: 20px;
    font-weight: bold;
    color: $news-title;
}
.product-titlee {
    text-align: center;
    padding: 20px 0;
    font-weight: bold;
    color: $news-title;
}

.product {
    padding: 20px;
    &_img {
        margin-top: 16px;
    }
    #icon-product {
        border-radius: 24px;
        width: 400px;
        height: 500px;
    }
}

.text-item-txt {
    font-weight: bold;
    color: $text-colorRank;
    margin-top: 20px;
}
.btn {
    margin-top: 16px;
}
.btnCancel {
    padding: 4px 8px;
}
.btnSubmit {
    padding: 4px 8px;
}
.info_product {
    width: 40%;

}
.info_text {
    width: 60%;
}

.list-item {
    &_name {
        font-size: 20px;
        font-weight: 600;
        color: $news-title;
    }
    &_fee {
        display: flex;
        align-items: center;
        gap: 0 8px;
        font-weight: 600;
        color: $news-title;
        &-note {
            color: $primary-color;
            text-decoration: line-through;
        }
        &-fee {
            color: $primary-color;
        }
    }
    &_note {
        display: flex;
        align-items: center;
        gap: 0 8px;
        font-weight: 600;
        color: $news-title;
        .product_note-txt {
            color: $primary-color;
        }
    }
    .item-color {
        margin-top: 20px;
        color: $news-title;
        display: flex;
        align-items: center;
        gap: 0 8px;
        font-weight: 550;
        &_name {
            font-weight: 550;
        }
    }
    &_other {
        display: flex;
        align-items: center;
        gap: 0 16px;
        img {
            border-radius: 8px;
            width: 170px;
            height: 200px;
            cursor: pointer;
            user-select: none;
        }

    }
    &_sizeTXT {
        margin-top: 20px;
        color: $news-title;
        font-weight: 550;
    }
    &_size {
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 0 16px;
        color: $news-title;
        font-weight: 550;
        .item_size {
            cursor: pointer;
            user-select: none;
            padding: 20px 24px;
            border-radius: 16px;
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
    }
}
.selectOptionSize {
    background: $body-column-title;
    color: $bgc-body;
}

</style>