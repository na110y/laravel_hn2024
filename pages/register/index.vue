<template>
    <b-container fluid>
        <loading
            :active.sync="isLoading"
            :can-cancel="true"
            :is-full-page="true"
        ></loading>

        <div class="content">
            <b-form class="login" @submit.prevent="onSubmit">
                <div class="login-title">{{ $t('logout.title') }}</div>
                <div class="login-icon">
                    <img src="@/assets/img/login.svg" alt="error-icon">
                </div>
                <div class="login-register">
                    <div class="login-register_txt">{{ $t('logout.isLogin') }}</div>
                    <nuxt-link to="/login/" class="login-register_link">{{ $t('logout.login') }}</nuxt-link>
                </div>
                <div class="login-name">
                    <b-form-group id="login-label" label="Name" label-for="input-1">
                        <b-form-input type="text" id="input-1" v-model="userRegister.user_name"></b-form-input>
                    </b-form-group>
                </div>
                <div class="login-userName">
                    <b-form-group id="login-label" :label="$t('logout.userName')" label-for="input-1">
                        <b-form-input type="email" id="input-1" v-model="userRegister.user_email"></b-form-input>
                    </b-form-group>
                </div>
                <div class="login-userPasswork">
                    <b-form-group id="login-label" :label="$t('logout.userPassword')" label-for="input-1">
                        <b-form-input :type="isPasswordPW ? 'text' : 'password'" id="input-1" v-model="userRegister.user_password" ></b-form-input>
                        <img src="@/assets/img/mat.svg" alt="error-icon" id="icon-mat" @click="isShowTypePW()">
                    </b-form-group>
                </div>
                <div class="login-checkPasswork">
                    <b-form-group id="login-label" :label="$t('logout.thePassword')" label-for="input-1">
                        <b-form-input :type="isPasswordCheck ? 'text' : 'password'" id="input-1" v-model="userRegister.check_password" ></b-form-input>
                        <img src="@/assets/img/mat.svg" alt="error-icon" id="icon-mat" @click="isShowTypeCheck()">
                    </b-form-group>
                </div>
                <div class="login-login">
                    <b-button class="login-submit" type="submit"> {{ $t('logout.login') }} </b-button>
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
            isPasswordPW: false,
            isPasswordCheck: false,
            isLoading: false,
        }
    },
    methods: {
        async onSubmit() {
            try {
                if (this.userRegister.user_password !== this.userRegister.check_password) {
                    return this.showToast('info', 'Mật khẩu nhập lại không khớp!');
                }
                const param = {
                    user_name: this.userRegister.user_name,
                    user_email: this.userRegister.user_email,
                    user_password: this.userRegister.user_password,
                    check_password: this.userRegister.check_password,
                }

                await userApi.postInfoLogin(param)
                .then((res) => {
                    this.$auth.loginWith('laravelSanctum', {
                        data: {
                            email: this.userRegister.user_email,
                            password: this.userRegister.user_password
                        }
                    });
                    this.showToast('success', 'Đăng ký thành công!');
                    this.is_loading = false;
                })
                .catch((error) => {
                    this.is_loading = false;
                });
            } catch (error) {
                this.showToast('info', 'Đã xảy ra lỗi trong quá trình đăng ký');
            }
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
    margin: 50px 0;
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
input {
    border: 1px solid $border;
}
</style>