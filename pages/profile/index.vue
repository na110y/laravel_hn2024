<template>
    <b-container fluid>
        <loading
            :active.sync="isLoading"
            :can-cancel="true"
            :is-full-page="true"
        ></loading>

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

        <div class="profile">
            <div class="profile_logo">
                <div class="profile_logo-item">

                    <div class="avatar-upload">
                        <div class="avatar-edit">
                        <input type="file" ref="imageUpload" @change="handleImageChange" accept=".png, .jpg, .jpeg" />
                        <label for="imageUpload"></label>
                        </div>
                        <div class="avatar-preview">
                        <div :style="{ backgroundImage: 'url(' + imageUrl + ')' }" @click="triggerFileInput"></div>
                        </div>
                    </div>


                    <ul v-for="(item, index) in listUser" :key="index">
                        <li>
                            <!-- <img :src="item.img" alt="error-logo" id="logo_user"> -->
                            <!-- <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type="file" ref="imageUpload" @change="handleImageChange" accept=".png, .jpg, .jpeg" />
                                    <label for="imageUpload"></label>
                                </div>
                                <div class="avatar-preview">
                                    <div :style="{ backgroundImage: 'url(' + imageUrl + ')' }" @click="triggerFileInput"></div>
                                </div>
                            </div> -->
                        </li>
                        <li>
                            <div class="user-name">{{ item.fullName }}</div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="profile_txt"></div>
        </div>


    </b-container>
</template>

<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import userApi from '~/plugins/api/userApi';

export default {
    components: {
        Loading
    },
    data() {
        return {
            userRegister: {
                user_name: '',
                user_email: '',
                user_password: '',
                check_password: ''
            },
            toastVariant: "info",
            toastMessage: null,
            isLoading: false,
            listUser : [],
            imageUrl: 'http://i.pravatar.cc/500?img=7',
        }
    },
    created() {
        this.listUserItem();
    },
    methods: {
        listUserItem() {
            this.is_loading = true;
            userApi.getListUser()
            .then((res) => {
                this.listUser = res.data;
                this.is_loading = false;
            })
            .catch((err) => {
                this.is_loading = false;
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
        handleImageChange() {
            console.log(1);
            const input = this.$refs.imageUpload;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.readAsDataURL(input.files[0]);

                reader.onload = (evt) => {
                this.imageUrl = evt.target.result;
                };
            }
        },
        triggerFileInput() {
            this.$refs.imageUpload.click();
        },
    }
}
</script>

<style lang="scss" scoped>
@import "@/assets/styles/main.scss";

#logo_user {
    width: 40px;
    height: 40px;
}

.avatar-upload {
    position: relative;
    max-width: 205px;
    .avatar-edit {
        position: absolute;
        right: 12px;
        z-index: 1;
        top: 10px;
        input {
            display: none;
            + label {
                display: inline-block;
                width: 34px;
                height: 34px;
                margin-bottom: 0;
                border-radius: 100%;
                background: #FFFFFF;
                border: 1px solid transparent;
                box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
                cursor: pointer;
                font-weight: normal;
                transition: all .2s ease-in-out;
                &:hover {
                    background: #f1f1f1;
                    border-color: #d6d6d6;
                }
                &:after {
                    content: "\f040";
                    font-family: 'FontAwesome';
                    color: #757575;
                    position: absolute;
                    top: 10px;
                    left: 0;
                    right: 0;
                    text-align: center;
                    margin: auto;
                }
            }
        }
    }
    .avatar-preview {
        width: 192px;
        height: 192px;
        position: relative;
        border-radius: 100%;
        border: 6px solid #F8F8F8;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
        > div {
            width: 100%;
            height: 100%;
            border-radius: 100%;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
    }
}
</style>