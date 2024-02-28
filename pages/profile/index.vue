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
            <div class="profile_txt">
                <div class="profile_txt-info">
                    <div class="info-content">

                        <div class="fee-user">
                            <div class="info-content_free">
                                <div class="free-number"> {{ $t('profile.total') }} </div>
                                <div class="fee-info">
                                    <div class="free-number"> {{ info_user.count_fee }} </div>
                                    <img src="@/assets/img/fee.svg" alt="error-fee">
                                </div>
                            </div>

                            <div class="info-content_free">
                                <div class="free-number">{{ $t('profile.money') }}</div>
                                <div  class="fee-info">
                                    <div class="free-number"> {{ info_user.free }} </div>
                                    <img src="@/assets/img/fee.svg" alt="error-fee">
                                </div>
                            </div>

                            <div class="info-content_free recharge">
                                <div>
                                    <b-icon icon="plus-square" font-scale="1.5" aria-hidden="true" id="recharge"></b-icon>
                                </div>
                            </div>
                        </div>


                        <div class="info-content_txt">
                            <b-form @submit.prevent="saveInfoUser(info_user)">
                                <div class="info-title">{{ $t('profile.infoUser') }}</div>
                                <div class="column-first">
                                    <div class="column-first_info">
                                        <b-form-group id="login-label" :label="$t('profile.fullName')" label-for="search-student-code" class="mt-2">
                                            <b-form-input class="custom-input" id="search-student-code" v-model="info_user.fullName"></b-form-input>
                                        </b-form-group>
                                    </div>

                                    <div class="column-first_info">
                                        <b-form-group id="login-label" :label="$t('profile.sdt')" label-for="search-student-code" class="mt-2">
                                            <b-form-input type="number" class="custom-input" id="search-student-code" v-model="info_user.sdt"></b-form-input>
                                        </b-form-group>
                                    </div>

                                </div>


                                <div class="column-content">
                                    <div class="column-content_info">
                                        <b-form-group id="login-label" :label="$t('profile.dateOfBirst')" label-for="search-student-code" class="mt-2">
                                            <b-form-input class="custom-input" id="search-student-code" v-model="info_user.dateOfBirst"></b-form-input>
                                        </b-form-group>
                                    </div>

                                    <div class="column-content_info">
                                        <b-form-group id="login-label" :label="$t('profile.gender')" label-for="search-student-code" class="mt-2">
                                            <div class="mydict">
                                                <div>
                                                    <label>
                                                        <input type="radio" name="radio" checked value="1" v-model="info_user.gender">
                                                        <span>{{ $t('profile.Male') }}</span>
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="radio" value="0" v-model="info_user.gender">
                                                        <span>{{ $t('profile.Female') }}</span>
                                                    </label>
                                                    <label>
                                                        <input type="radio" name="radio" value="2" v-model="info_user.gender">
                                                        <span>{{ $t('profile.Other') }}</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </b-form-group>
                                    </div>

                                </div>

                                <div class="column-end">
                                    <b-form-group id="login-label" :label="$t('profile.adrres')" label-for="search-student-code" class="mt-2">
                                        <b-form-input class="custom-input" id="search-student-code" v-model="info_user.adress"></b-form-input>
                                    </b-form-group>
                                </div>

                                <div class="column-setting">
                                    <div class="column-password">
                                        <div class="column-password_info">
                                            <div class="column-password_txt">{{ $t('profile.pw') }}</div>
                                            <div class="column-password_pw" @click.prevent="btnPW">{{ $t('profile.ChangePassword') }}</div>
                                        </div>


                                        <div class="checkPW" v-show="isShowPW">
                                            <div class="login-userPasswork">
                                                    <b-form-group id="login-label" :label="$t('profile.Currentpassword')" label-for="input-1">
                                                        <b-form-input :type="isPasswordPW ? 'text' : 'password'" id="input-1" v-model="info_password.password" ></b-form-input>
                                                        <img src="@/assets/img/mat.svg" alt="error-icon" id="icon-mat" @click="isShowTypePW()">
                                                    </b-form-group>
                                                </div>
                                                <div class="login-checkPasswork">
                                                    <b-form-group id="login-label" :label="$t('profile.PWToChange')" label-for="input-1">
                                                        <b-form-input :type="isPasswordCheck ? 'text' : 'password'" id="input-1" v-model="info_password.the_password" ></b-form-input>
                                                        <img src="@/assets/img/mat.svg" alt="error-icon" id="icon-mat" @click="isShowTypeCheck()">
                                                </b-form-group>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="setting-lang">
                                        <b-form-group id="login-label" :label="$t('profile.lang')" label-for="search-available" class="mt-2">
                                            <b-form-select v-model="$i18n.locale" @change="changeLanguage" id="lang">
                                                <option v-for="(item, index) in options" :value="item.value" :key="index">
                                                    {{ item.text  }}
                                                </option>
                                            </b-form-select>
                                        </b-form-group>
                                    </div>
                                </div>

                                <div class="btnUpdate">
                                    <b-button type="submit">{{ $t('profile.Update') }}</b-button>
                                </div>
                            </b-form>
                        </div>
                    </div>
                </div>
            </div>
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
            info_user: {
                id:'',
                fullName: '',
                sdt: '',
                dateOfBirst: '',
                gender: '',
                adress: '',
            },
            info_password: {
                password: '',
                the_password: ''
            },
            options: [
                { value: 'vn', text: 'Tiếng Việt' },
                { value: 'en', text: 'Tiếng Anh' },
            ],
            toastVariant: "info",
            toastMessage: null,
            isLoading: false,
            listUser : [],
            isPasswordPW: false,
            isPasswordCheck: false,
            isShowPW: false,
        }
    },
    created() {
        this.listUserItem();
    },
    methods: {
        btnPW() {
            this.isShowPW = !this.isShowPW;
        },

        changeLanguage(locale) {
            this.$store.dispatch('changeLanguage', locale)
        },

        /**
         * @description: Lưu thông tin thay đổi của người dùng
         */
         async saveInfoUser(info_user) {
            this.is_loading = true;
            const param = {
                fullName: this.info_user.fullName,
                sdt: this.info_user.sdt,
                dateOfBirst: this.info_user.dateOfBirst,
                gender: this.info_user.gender,
                adress: this.info_user.adress,
            }

            await userApi.postInfoUser(info_user, param)
            .then((res) => {
                this.showToast('success', 'Cập nhập thành công!');
                this.is_loading = false;
            })
            .catch((err) => {
                this.showToast('error', 'Cập nhập thất bại!');
                this.is_loading = false;
            });
        },

        /**
         * @description: lấy ra danh sách thông tin của người dùng
         */
         async listUserItem() {
            this.is_loading = true;
            await userApi.getListUser()
            .then((res) => {
                this.info_user = res.data.info_user;
                this.listUser = res.data.info_user;
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
        isShowTypeCheck() {
            this.isPasswordCheck = !this.isPasswordCheck;
        },
        isShowTypePW() {
            this.isPasswordPW = !this.isPasswordPW;
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
.profile {
    background-color: $bgc-profile;
    padding: 20px;
}

.info-title {
    text-align: center;
    font-weight: 550;
    color: $news-title;
    font-size: 16px;
    margin-bottom: 25px;
}
.info-content{
    max-width: 1440px;
    width: 100%;
    margin:20px auto;
    .fee-user {
        display: flex;
        align-items: center;
        gap: 0 40px;
    }
    .recharge {
        height: 122px;
    }
    #recharge {
        color: $bgc-body;
        cursor: pointer;
    }
    &_free {
        background-color: $bgc-icon;
        width: 50%;
        gap:0 8px;
        padding: 40px;
        border-radius: 16px;
        text-align: center;
        img {
            width: 16px;
            height: 16px;
        }
        .free-number{
            color: $bgc-body;
            font-size: 14px;
            font-weight: 550;
        }
        .fee-info {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0 8px;
        }
    }
    &_txt {
        margin-top: 20px;
        background-color: $bgc-info;
        border-radius: 20px;
        padding: 20px;
        border: 1px solid $border-input;
        .column-first {
            display: flex;
            align-items: center;
            gap: 0 40px;
            &_info {
                width: 50%;
                input {
                    padding: 0 8px;
                }
            }
        }
        .column-content {
            display: flex;
            align-items: center;
            gap: 0 40px;
            &_info {
                width: 50%;
                input {
                    padding: 0 8px;
                }
            }
        }
        .column-end {
            input {
                padding: 0 8px;
            }
        }
        .column-password {
            margin-top: 20px;
            width: 50%;
            &_info {
                display: flex;
                align-items: center;
                gap: 0 8px;
                input {
                    padding: 0 8px;
                }
            }
            &_txt {
                color: $text-color;
            }

            &_pw {
                color: $bgc-icon;
                cursor: pointer;
            }
        }
        .column-setting {
            margin-top: 20px;
            display: flex;
            gap: 0 40px;
            width: 100%;
        }
    }
}
.setting-lang {
    width: 50%;
}
::v-deep #lang {
  width: 100%;
  border: 1px solid $border;
  padding: 0 8px;

}


.mydict div {
  display: flex;
  flex-wrap: wrap;
  margin-top: 0.5rem;
  justify-content: center;
}

.mydict input[type="radio"] {
  clip: rect(0 0 0 0);
  clip-path: inset(100%);
  height: 1px;
  overflow: hidden;
  position: absolute;
  white-space: nowrap;
  width: 1px;
}

.mydict input[type="radio"]:checked + span {
  box-shadow: 0 0 0 0.0625em $bgc-icon;
  background-color: $bgc-gender;
  z-index: 1;
  color: $bgc-icon;
}

label span {
  display: block;
  cursor: pointer;
  background-color: $bgc-body;
  padding: 0.375em .75em;
  position: relative;
  margin-left: .0625em;
  box-shadow: 0 0 0 0.0625em $border-input;
  letter-spacing: .05em;
  text-align: center;
  transition: background-color .5s ease;
  color: $text-color;
}

label:first-child span {
  border-radius: .375em 0 0 .375em;
  color: $text-color;
}

label:last-child span {
  border-radius: 0 .375em .375em 0;
  color: $text-color;
}

.btnUpdate {
    margin-top: 50px;
    display: flex;
    justify-content: end;

    button {
        background-color: $bgc-icon;
        color: $bgc-body;
        padding: 8px 12px;
    }
}

.checkPW {
    display: flex;
    align-items: center;
    gap: 0 40px;
    .login-userPasswork {
        position: relative;
        width: 50%;
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
            top: 52px;
            right: 10px;
            width: 15px;
        }
    }
    .login-checkPasswork {
        position: relative;
        width: 50%;
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
            top: 52px;
            right: 10px;
            width: 15px;
        }
    }
}

#login-label {
    color: $text-color;
}
</style>