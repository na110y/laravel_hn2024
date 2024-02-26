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
                  <img src="@/assets/img/notif.svg" alt="error-icon" id="icon">
                </div>
              </li>
              <li class="menu-icon_info">
                <div class="menu-webcome">{{ $t('common.welcome') }}:</div>
                <div class="menu-info">
                  <div class="menu-info_name" @click="info_login">Đức Thịnh</div>
                </div>
                <div class="modal_info" v-show="isShowInfoLogin">
                  <div class="modal_info-setting">
                    <b-form-group
                        id="input-group-1"
                        label-for="search-available"
                        class="mt-2"
                      >
                        <b-form-select
                          v-model="$i18n.locale"
                          @change="changeLanguage"
                          id="lang"
                        >
                          <option v-for="(item, index) in options" 
                            :value="item.value" 
                            :key="index">
                              {{ item.text  }}
                          </option>
                        </b-form-select>
                    </b-form-group>

                    <div class="btn_info"> 
                      <b-button type="button" class="btn_info-logout">Đăng xuất</b-button>
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
    </b-container>
</template>
  
<script>

export default {
  data() {
    return {
      options: [
          { value: 'vn', text: 'Tiếng Việt' },
          { value: 'en', text: 'Tiếng Anh' },
      ],
      isShowInfoLogin : false,
    };
  },
  mounted() {
    window.addEventListener("click", this.handleClickOutside);
  },
  methods: {
    changeLanguage(locale) {
      this.$store.dispatch('changeLanguage', locale)
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
    &.nuxt-link-active {
      color: $primary-color !important;
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
    width: 200px;
    height: 100px;
    z-index: 999;
    background-color: $bgc-body;
    border: 1px solid $border;
    padding: 0 10px 0 20px;
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


</style>