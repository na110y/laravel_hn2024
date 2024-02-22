<template>
    <b-container fluid>
      <div class="menuNav">
          <div class="menu">
            <div class="menu-title"> BAE SHOP</div>
            <ul class="menu-item">
              <li v-for="(item, index) in menuNav" :key="index">
                <nuxt-link :to="item.href">
                  <div> {{ item.title }} </div>
                </nuxt-link>
              </li>
            </ul>
            <ul class="menu-icon">
              <li>
                <img src="@/assets/img/mes.svg" alt="error-icon">
              </li>
              <li>
                <div class="menu-info_icon">
                  <img src="@/assets/img/mes.svg" alt="error-icon">
                </div>
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
    }
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
  gap: 0 80px;
  margin: auto;
  &-item {
    display: flex;
    align-items: center;
    gap: 0 40px;
  }
}

</style>