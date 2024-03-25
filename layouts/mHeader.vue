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
              <li v-if="!isAdmin">
                <div class="menu-link">
                  <nuxt-link to="/cart">
                    {{ $t('header.cart') }}
                  </nuxt-link>
                </div>
              </li>
              <li>
                <div class="menu-link">
                  <nuxt-link to="/pending">
                    {{ $t('header.pending') }}
                  </nuxt-link>
                </div>
              </li>
            </ul>
            <ul class="menu-icon">
              <li v-if="!isAdmin">
                <div class="menu-icon_mess">
                  <img src="@/assets/img/search.svg" alt="error-icon" id="icon" @click.prevent="btnSearch">
                </div>
              </li>
              <li>
                <div class="menu-icon_mess">
                  <img src="@/assets/img/mess.svg" alt="error-icon" id="icon" @click="btnIsShowMess">
                  <div class="messger" v-if="isMessgers">
                    <div class="messger_user">
                      <b-form @submit.prevent="submit" ref="messger">
                        <b-form-group id="login-label" label-for="input-1">
                            <div class="messger-header">
                              <div class="messger-header_userName">{{ username }}</div>
                              <img src="@/assets/img/close.svg" alt="image" @click="btnIsShowMess">
                            </div>
                            <div class="messger-content">
                             <div v-for="message in messages" :key="message">
                              <div class="messger-content_text">{{ message.username }} : {{ message.message }}</div>
                            </div>
                            </div>
                            <div class="messger-submit">
                              <div>
                                <b-form-input type="text" id="inputMess" v-model="message" placeholder="Nhập tin nhắn..."></b-form-input>
                              </div>
                              <b-button class="login-submit" type="submit" id="submit-messgers"> 
                                <img src="@/assets/img/send.svg" alt="image">
                              </b-button>
                              
                            </div>
                        </b-form-group>
                      </b-form>
                    </div>
                  </div>
                </div>
              </li>
              <li v-if="!isAdmin">
                <div class="menu-icon_notif">
                  <img src="@/assets/img/notif.svg" alt="error-icon" id="icon" @click="btnIsShowProductNoti">
                  <div class="product-cart-mini" v-if="listProduct.length > 0"></div>
                  <div v-else></div>

                  <div class="modal-notif" v-if="isShowProductNoti">
                   <ul>
                      <li v-for="(sp, index) in listProduct" :key="index + 'sp'" class="itemNotif" @click="btnHideProductNoti">
                        <nuxt-link to="/cart" >
                          <div class="modal-notif_image">{{ username }}</div>
                          <div class="modal-notif_info" >
                            <div class="notif_info-name">{{ sp.product_name }} - {{ $vali.formatDate(sp.created_at) }} </div>

                            <!-- <div class="notif_info-action">
                              <div class="notif_info-fee"> {{ $vali.formatCurrency(sp.product_price) }} </div>
                              <b-button variant="danger" class="btn-table_delete" size="sm" @click="btnDelete(sp.id)">
                                  Xóa sản phẩm
                              </b-button>
                            </div> -->
                          </div>
                        </nuxt-link>
                      </li>
                   </ul>
                  </div>
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

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script>
import cartApi from '~/plugins/api/listCart';
import { EventBus } from '~/plugins/event-bus.js';
import Pusher from 'pusher-js';
import { mapState } from 'vuex'
// import { mapState } from 'vuex';
export default {
  middleware: ['auth'],
  data() {
    return {
      toastVariant: "info",
      toastMessage: null,
      isShowInfoLogin : false,
      listProduct: [],
      isShowProductNoti : false,
      isMessgers: false,
      username: '',
      user_id: '',
      isAdmin: null,
      messages: [],
    };
  },
  mounted() {
    window.addEventListener("click", this.handleClickOutside);

    // Pusher.logToConsole = true;
    // const pusher = new Pusher('0624ad209e23f1ff966f', {
    //   cluster: 'eu',
    //   encrypted: true,
    // });

    // const channel = pusher.subscribe('chat');
    //   channel.bind('message', data => {
    //   this.messages.push(data);
    // });
    if (this.$auth.user) {
        this.username = this.$auth.user.name;
        this.user_id = this.$auth.user.user_id;
        this.isAdmin = this.$auth.user.role;


    }
  },
  created() {
    EventBus.$on('listProductChanged', () => {
      this.listPending();
    });

    this.listPending();
    this.listMessages();
  },

  methods: {
    async submit() {
      await this.$axios.post('http://localhost:8080/api/messages', {
        user_id: this.user_id,
        username: this.username,
        message: this.message,
      })
      .then((res) => {
        this.message = '',
        console.log(res);
      })

    },

    btnIsShowMess() {
      this.isMessgers = !this.isMessgers
    },

    async listMessages() {
      await this.$axios.get('http://localhost:8080/api/get-messages')
        .then((response) => {
          this.messages = response.data;
        })
        .catch((error) => {
          console.error('Error fetching messages:', error);
        });
    },

    async  listPending() {
        this.isLoading = true;
        await  cartApi.getListProduct()
        .then((res) => {
            this.listProduct = res.data.data;
            this.isLoading = false;
        })
        .catch((err) => {
            this.isLoading = false;
        });
    },

    btnHideProductNoti() {
      this.isShowProductNoti = !this.isShowProductNoti;
    },

    btnIsShowProductNoti() {
      this.isShowProductNoti = !this.isShowProductNoti;
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
        this.isShowProductNoti = false;
      }
    },

    // xóa sản phẩm trong giỏ hàng
    btnDelete(id) {
      if (confirm(`Bạn có chắc xóa sản phẩm có mã ID: ${id}`)) {
          this.deleteProductCart(id);
      }

    },

    async deleteProductCart(id) {
        this.isLoading = true;
        await cartApi.productDeleteDetail(id)
        .then((res) => {
            this.isLoading = false;
            this.listPending();
            EventBus.$emit('listProductChanged', res.data);
            this.showToast('success', 'Xóa sản phẩm thành công!');
        })
        .catch((err) => {
            this.showToast('danger', 'Xóa sản phẩm thất bại!');
            this.isLoading = false;
        });
    },

    async logout() {
      try {
        await this.$auth.logout();
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

.messger {
  position: fixed;
  bottom: 0;
  right: calc((100% - 1440px) / 2);
  width: 370px;
  height: 488px;
  border-radius: 16px 16px 0 0;
  box-shadow: 0 0 2px rgba(0, 0, 0, 0.5);
  background-color: $bgc-profile;
  &-header {
    display: flex;
    border-radius: 16px 16px 0 0;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    border-bottom: 1px solid $border;
    padding: 16px;
    background-color: $bgc-body;
    &_userName {
      color: $text-color;
      font-size: 16px;
      font-weight: 550;
    }
  }
  &-content {
    padding: 16px;
    height: 340px;
    &_text {
      color: $text-color;

    }
  }
  &-submit {
    position: relative;
    border-radius: 16px;
    padding: 16px;
    #inputMess {
      border: none;
      border-radius: 16px;
      padding: 10px;
      height: 50px;
      color: $text-color;
      padding: 16px;
      &::placeholder {
        color: $text-color;
      }
    }
    #submit-messgers {
      position: absolute;
      top: 30%;
      right: 20px;
      background-color: #fff;
      padding-right: 10px;
    }
  }
}
.info-icon {
  border: 1px solid $primary-color;
  border-radius: 50%;
  padding: 1px 8px 5px 8px;
}

#icon {
  width: 22px !important;
  height: 22px !important;
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
.menu-icon_notif{
  position: relative;
}
.product-cart-mini{
  position: absolute;
  top: 0px;
  right: 4px;
  padding: 4px;
  border-radius: 50%;
  background-color: $bgc-icon;
  color: $bgc-body;
  font-weight: 550;
}

.modal-notif {
  position: absolute;
  top: 30px;
  right: 0;
  width: 350px;
  height: 400px;
  border: 1px solid $border;
  border-radius: 16px;
  background-color: #fff;
  z-index: 999;
  overflow-y: auto;
  #iconNotif {
    width: 80px;
    height: 100px;
    border-radius: 16px;
  }
  .notif_info-name {
    color: $text-color;
    font-weight: 500;
    font-size: 16px;
  }

  .notif_info-fee {
    color: $primary-color;
    font-weight: 550;
  }
  a {
    display: flex;
    gap: 16px;
    padding: 8px 0;

  }
  .itemNotif{
    padding: 16px;
    border-radius: 16px;
    &:hover {
      background-color: $border;
    }
  }
}
.btn-table_delete{
  padding: 4px 8px;
}
.notif_info-action {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 15px;
}
.modal-notif_info {
  width: 100%;
}
.modal-notif_image {
  color: $text-color;
  font-weight: bold;
}
</style>