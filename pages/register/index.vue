<template>
    <b-container fluid>
        <loading
            :active.sync="isLoading"
            :can-cancel="true"
            :is-full-page="true"
        ></loading>

        <div class="content">
            <b-form class="login" @submit.prevent="onSubmit">
                <div class="login-title">Đăng ký</div>
                <div class="login-icon">
                    <img src="@/assets/img/login.svg" alt="error-icon">
                </div>
                <div class="login-register">
                    <div class="login-register_txt">Bạn đã có tài khoản?</div>
                    <nuxt-link to="/login/" class="login-register_link">Đăng nhập</nuxt-link>
                </div>
                <div class="login-name">
                    <b-form-group id="login-label" label="Name" label-for="input-1">
                        <b-form-input id="input-1" v-model="userRegister.name"></b-form-input>
                    </b-form-group>
                </div>
                <div class="login-userName">
                    <b-form-group id="login-label" label="Email / Tên đăng nhập" label-for="input-1">
                        <b-form-input id="input-1" v-model="userRegister.user_name"></b-form-input>
                    </b-form-group>
                </div>
                <div class="login-userPasswork">
                    <b-form-group id="login-label" label="Mật khẩu" label-for="input-1">
                        <b-form-input :type="isPasswordPW ? 'text' : 'password'" id="input-1" v-model="userRegister.user_passwork" ></b-form-input>
                        <img src="@/assets/img/mat.svg" alt="error-icon" id="icon-mat" @click="isShowTypePW()">
                    </b-form-group>
                </div>
                <div class="login-checkPasswork">
                    <b-form-group id="login-label" label="Nhập lại mật khẩu" label-for="input-1">
                        <b-form-input :type="isPasswordCheck ? 'text' : 'password'" id="input-1" v-model="userRegister.check_passwork" ></b-form-input>
                        <img src="@/assets/img/mat.svg" alt="error-icon" id="icon-mat" @click="isShowTypeCheck()">
                    </b-form-group>
                </div>
                <div class="login-login">
                    <b-button class="login-submit" type="submit"> Đăng ký </b-button>
                </div>
            </b-form>
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
export default {
    components: {
        Loading
    },
    data() {
        return {
            userRegister: {
                name: '',
                user_name: '',
                user_passwork: '',
                check_passwork: ''
            },
            toastVariant: "info",
            toastMessage: null,
            isPasswordPW: false,
            isPasswordCheck: false,
            isLoading: false,
        }
    },
    methods: {
        onSubmit() {
            this.isLoading = true;
            const params = {
                userRegister :  this.userRegister
            }
            this.$axios
            .post("http://localhost:8080/api/login-api/post-register", params)
            .then((res) => {
                if(res.data.status === 200) {
                    this.showToast("success", "Đăng ký thành công!");
                }else if (res.data.status === 400) {
                    this.showToast("warning", "Nhập lại mật khẩu không trùng khớp với mật khẩu!");
                }else if (res.data.status === 500) {
                    this.showToast("info", "Tài khoản đã tồn tại!");
                }
                this.isLoading = false;
            })
            .catch((err) => {
                console.error("Error fetching data:", err);
            });
        },

        isShowTypePW() {
            this.isPasswordPW = !this.isPasswordPW;
        },

        isShowTypeCheck() {
            this.isPasswordCheck = !this.isPasswordCheck;
        },

        showToast(variant, message) {
            this.toastVariant = variant;
            this.toastMessage = message;
            this.$refs.customToast.show();

            setTimeout(() => {
                this.$refs.customToast.hide();
            }, 3000);
        },
    }
}
</script>
<style lang="scss" scoped>
@import "@/assets/styles/main.scss";
.content {
    max-width: 520px;
    width: 100%;
    margin: auto;
}
.login {
    border: 1px solid #ced4da;
    border-radius: 16px;
    padding: 20px 16px;
    &-title {
        text-align: center;
        font-weight: 700;
        color: $text-color;
        font-size: 24px;
    }
    &-icon {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 8px;
    }
    &-register {
        display: flex;
        align-items: center;
        gap: 0 8px;
        margin-bottom: 16px;
        &_txt {
            font-weight: 400;
            font-size: 14px;
            color: $text-color;
        }
        &_link {
            font-size: 14px;
            font-weight: 700;
            color: #FE616B;
        }
    }
    &-name {
        #login-label {
            color: $text-color;
            margin-top: 8px;
        }
        input {
            padding: 0 8px;
        }
    }
    &-userName {
        #login-label {
            color: $text-color;
            margin-top: 8px;
        }
        input {
            padding: 0 8px;
        }
    }
    &-userPasswork {
        position: relative;
        #login-label {
            color: $text-color;
            margin-top: 8px;
        }
        input {
            padding: 0 8px;
        }
        #icon-mat {
            position: absolute;
            cursor: pointer;
            top: 40px;
            right: 10px;
            width: 15px;
        }
    }
    &-checkPasswork {
        position: relative;
        #login-label {
            color: $text-color;
            margin-top: 8px;
        }
        input {
            padding: 0 8px;
        }
        #icon-mat {
            position: absolute;
            cursor: pointer;
            top: 40px;
            right: 10px;
            width: 15px;
        }
    }
    &-check {
        position: relative;
        margin: 16px 0;
        display: flex;
        justify-content: space-around;
        align-items: center;
        .btn-forgot {
            background-color: #fff;
            border: none;
            color: $text-color;
        }
        #icon-mat {
            position: absolute;
            cursor: pointer;
            top: 40px;
            right: 10px;
            width: 15px;
        }
    }
    &-login {
        width: 100%;
        margin: 16px 0;
        .login-submit {
            width: 100%;
            background: $text-color;
            color: #FFFFFF;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            padding: 8px;
        }
    }
    &-or {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    &-fb {
        margin: 16px 0;
        width: 100%;
        color: $text-color;
        font-size: 16px;
        border: none;
        border-radius: 8px;
        padding: 8px;
        text-align: center;
    }
}
</style>