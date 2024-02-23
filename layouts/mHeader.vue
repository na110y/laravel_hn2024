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
              <li v-for="(item, index) in menuNav" :key="index">
                <div class="menu-link">
                  <nuxt-link :to="item.href">
                    {{ item.title }}
                  </nuxt-link>
                </div>
              </li>
            </ul>
            <ul class="menu-icon">
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
                <div class="menu-webcome">Xin chào:</div>
                <div class="menu-info">
                  <div class="menu-info_name">Đức Thịnh</div>
                </div>
              </li>
            </ul>
          </div>
        </div>
    </b-container>
</template>
  
<script>

export default {
  data() {
    return {
      menuNav: [
        {
          href: '/',
          title: 'Trang chủ',
        },
        {
          href: '/players/',
          title: 'Thuê người chơi',
        },
        {
          href: '/watching/',
          title: 'Đang theo dõi',
        },
      ],
      isOpen: false,
      isOpenLever: false
    };
  },
  methods: {
    toggleDropdown() {
      this.isOpen = !this.isOpen;
    },
    btnShowLever() {
      this.isOpenLever = !this.isOpenLever;
    },

  },
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/main.scss";
.menuNav {
  border-bottom: 1px solid $border;
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

</style>