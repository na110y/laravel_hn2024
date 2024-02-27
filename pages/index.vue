<template>
  <div>
    <div>content</div>
    <!-- <div v-for="(items, index) in listUser" :key="index">
      <img :src="items.img" alt="">
    </div> -->
  </div>
</template>

<script>
import userApi from '~/plugins/api/userApi';
export default {
  name: 'IndexPage',
  data() {
    return {
      listUser : [],
    }
  },
  created() {
    this.listUserItem();
  },
  methods: {
    listUserItem() {
      userApi.getListUser()
        .then((res) => {
            this.listUser = res.data;
        })
        .catch((err) => {
            this.is_loading = false;
        });
    },
  },
}
</script>
