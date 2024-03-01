<template>
    <b-container fluid>
      <div class="menuNav">
          <div class="menu">
            <div class="menu-title"> 
              <nuxt-link to="/">
                <img src="@/assets/img/logo.png" alt="error-icon">
              </nuxt-link>
            </div>
            <ul class="menu-item">
              <li>
                <div class="menu-link">
                  <nuxt-link to="/">
                    {{ $t('header.home') }}
                  </nuxt-link>
                </div>
              </li>
              <li>
                <div class="menu-link">
                  <nuxt-link to="/product">
                    {{ $t('header.product') }}
                  </nuxt-link>
                </div>
              </li>
              <li>
                <div class="menu-link">
                  <nuxt-link to="/cart">
                    {{ $t('header.cart') }}
                  </nuxt-link>
                </div>
              </li>
              <li>
                <div class="menu-link">
                  <nuxt-link to="/about">
                    {{ $t('header.about') }}
                  </nuxt-link>
                </div>
              </li>
            </ul>
            <ul class="menu-icon">
              <li>
                <div class="menu-icon_mess">
                  <img src="@/assets/img/search.svg" alt="error-icon" id="icon" @click.prevent="btnSearch">
                </div>
              </li>
              <li>
                <div class="menu-icon_mess">
                  <img src="@/assets/img/mess.svg" alt="error-icon" id="icon">
                </div>
              </li>
              <li>
                <div class="menu-icon_notif">
                  <div>{{ listProduct.length }}</div>
                  <img src="@/assets/img/notif.svg" alt="error-icon" id="icon">
                </div>
              </li>
              <li class="menu-icon_info">
               <div class="menu-logout" v-if="$auth.user">
                <div class="menu-webcome">{{ $t('common.welcome') }}:</div>
                <div class="menu-info">
                  <div class="menu-info_name" @click="info_login">{{ $auth.user.name }}</div>
                </div>
               </div>
               <div class="menu-login" v-else>
                <nuxt-link to="/login/" class="menu-login_txt">{{ $t('common.login') }}</nuxt-link>
               </div>
                <div class="modal_info" v-show="isShowInfoLogin">
                  <div class="modal_info-setting">
                    <div class="btn_info"> 
                      <nuxt-link to="/profile">
                        <b-button type="button" class="btn_info-profile" @click="btnProfile">{{ $t('common.profile') }}</b-button>
                      </nuxt-link>
                    </div>

                    <div class="btn_info"> 
                      <b-button type="button" class="btn_info-logout" @click="logout">{{ $t('common.logout') }}</b-button>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
            <b-modal id="modal-search" title="Tìm kiếm sản phẩm" hide-footer>
              <b-form-group
              >
                <b-form-input id="search-product" placeholder="Tìm kiếm theo từ khóa..."></b-form-input>
                <img src="@/assets/img/search.svg" alt="error-icon" id="icon-search">
              </b-form-group>
            </b-modal>
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
    </b-container>
</template>
  
<script>
import cartApi from '~/plugins/api/listCart';
// import { mapState } from 'vuex';
export default {
  middleware: ['auth'],
  data() {
    return {
      toastVariant: "info",
      toastMessage: null,
      isShowInfoLogin : false,
      listProduct: []
    };
  },
  mounted() {
    window.addEventListener("click", this.handleClickOutside);
  },
  created() {
    this.listPending();
  },
  methods: {
    listPending() {
        this.isLoading = true;
        cartApi.getListProduct()
        .then((res) => {
            this.listProduct.length = res.data;
            this.isLoading = false;
        })
        .catch((err) => {
            this.isLoading = false;
        });
    },

    info_login() {
      this.isShowInfoLogin = !this.isShowInfoLogin;
    },
    btnSearch() {
      this.$bvModal.show("modal-search");
    },
    handleClickOutside(event) {
      if (!this.$el.contains(event.target)) {
        this.isShowInfoLogin = false;
      }
    },

    async logout() {
      try {
        await this.$auth.logout();
        this.$store.commit('SET_INFOLOGIN', );
        this.showToast('success', 'Đăng xuất thành công!');
        this.isShowInfoLogin = !this.isShowInfoLogin;
        this.$router.push('/login');
      } catch (error) {
          console.error(error);
      }
    },

    btnProfile() {
      console.log(1);
      this.isShowInfoLogin = !this.isShowInfoLogin;
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
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/main.scss";
.menuNav {
  border-bottom: 1px solid $border;
}
::v-deep #lang {
  width: 100%;
}
.menu {
  background-color: $bgc-body;
  max-width: 1440px;
  width: 100%;
  height: 80px;
  display: flex;
  align-items: center;
  justify-content: space-around;
  gap: 0 80px;
  margin: auto;
  &-item {
    display: flex;
    align-items: center;
    gap: 0 40px;
    &_text {
      color: $text-color;
    }
  }
  &-icon {
    display: flex;
    align-items: center;
    gap: 0 16px;
    &_mess {
      cursor: pointer;
      border-radius: 50%;
      padding: 1px 8px 5px 8px;
      background-color: $bgc-icon 20%;
    }

    &_notif {
      cursor: pointer;
      border-radius: 50%;
      padding: 1px 8px 5px 8px;
      background-color: $bgc-icon 20%;
    } 

    &_info {
      display: flex;
      align-items: center;
      gap: 0 8px;
    }
  }
}
.info-icon {
  border: 1px solid $primary-color;
  border-radius: 50%;
  padding: 1px 8px 5px 8px;
}

#icon {
  width: 16px;
  height: 16px;
}

.menu-info_name {
  color: $text-color;
  font-size: 16px;
  font-weight: 500;
  cursor: pointer;
}

.menu-link {
  cursor: pointer;
  a {
    color: $text-color;
    font-weight: 550;
    font-size: 16px;
    &.nuxt-link-active {
      color: $primary-color !important;
      font-weight: 550;
      font-size: 16px;
    }
  }
}
.menu-webcome {
  color: $primary-color;
  font-weight: 550;
  font-size: 16px;
}
.menu-icon_info {
  position: relative;
}
.modal_info {
  position: absolute;
  top: 25px;
  right: 0;
  &-setting {
    width: 250px;
    height: 120px;
    z-index: 999;
    background-color: $bgc-body;
    border: 1px solid $border;
    padding: 15px;
  } 
}

.btn_info {
  margin-top: 8px;
  &-logout {
    padding: 4px 8px;
    width: 100%;
    background-color: $bgc-info;
    color: $color-info;
    border-radius: 8px;
  }
  &-profile {
    padding: 4px 8px;
    width: 100%;
    border-radius: 8px;
    background-color: $color-info;
    color: $bgc-body;
  }
}

.setting-lang {
    display: flex;
    align-items: center;
    gap: 0 8px;
}

#search-product {
  border: 1px solid $border-input;
  width: 100%;
  padding: 8px 12px;
  position: relative;
}

#icon-search {
  position: absolute;
  top: 28px;
  right: 30px;
  cursor: pointer;
}

.menu-logout {
  display: flex;
  align-items: center;
  gap: 0 8px;
}

.menu-login {
  &_txt {
    color: $text-color;
  }
}
</style>