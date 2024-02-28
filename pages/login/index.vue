<template>
    <b-container fluid>
        <loading
            :active.sync="isLoading"
            :can-cancel="true"
            :is-full-page="true"
        ></loading>

        <div class="content">
            <b-form class="login" @submit.prevent="login" ref="loginForm">
                <div class="login-title">{{ $t('login.title') }}</div>
                <div class="login-icon">
                    <img src="@/assets/img/login.svg" alt="error-icon">
                </div>
                <div class="login-register">
                    <div class="login-register_txt">{{ $t('login.isLogin') }}</div>
                    <nuxt-link to="/register/" class="login-register_link">{{ $t('login.account') }}</nuxt-link>
                </div>
                <div class="login-userName">
                    <b-form-group id="login-label" :label="$t('login.userName')" label-for="input-1">
                        <b-form-input type="email" id="input-1" v-model="userLogin.user_email"></b-form-input>
                    </b-form-group>
                </div>
                <div class="login-userPasswork">
                    <b-form-group id="login-label" :label="$t('login.userPassword')" label-for="input-1">
                        <b-form-input :type="isPasswordVisible ? 'text' : 'password'" id="input-1" v-model="userLogin.user_passwork"></b-form-input>
                        <img src="@/assets/img/mat.svg" alt="error-icon" id="icon-mat" @click="isShowType()">
                    </b-form-group>
                </div>

                <div class="login-login">
                    <b-button class="login-submit" type="submit"> {{ $t('login.login') }} </b-button>
                </div>
                <div class="login-or">
                    <img src="@/assets/img/or.svg" alt="error-icon">
                </div>
                <div class="login-fb">
                    <nuxt-link to="" class="login-fb-text">{{ $t('login.loginFB') }}</nuxt-link>
                    <b-button class="btn-forgot">{{ $t('login.forgotPw') }}</b-button>
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
            userLogin: {
                user_email: '',
                user_passwork: ''
            },
            toastVariant: "info",
            toastMessage: null,
            isPasswordVisible: false,
            isLoading: false,
            user_name: '',
        }
    },
    methods: {
        async login() {
            try {
                const res = await this.$auth.loginWith('laravelSanctum', {
                data: {
                    email: this.userLogin.user_email,
                    password: this.userLogin.user_passwork
                }
                });
                this.$router.push("/");
                this.showToast('success', 'Đăng nhập thành công!');
            } catch (error) {
                this.showToast('info', 'Thông tin không hợp lệ!');
            }
        },
        showToast(variant, message) {
            this.toastVariant = variant;
            this.toastMessage = message;
            this.$refs.customToast.show();

            setTimeout(() => {
                this.$refs.customToast.hide();
            }, 3000);
        },

        isShowType() {
            this.isPasswordVisible = !this.isPasswordVisible;
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
    &-check {
        margin: 16px 0;
        display: flex;
        justify-content: space-around;
        align-items: center;

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
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 16px 0;
        width: 100%;
        .login-fb-text {
            color: $text-color;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            padding: 8px;
        }
        .btn-forgot {
            background-color: #fff;
            border: none;
            color: $text-color;
        }
    }
}
input {
    border: 1px solid $border;
}

</style>