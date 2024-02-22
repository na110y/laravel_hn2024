<template>
  <div>
    <div>content</div>
    <div>{{ listUser }}</div>
    <div v-for="item in list" :key="item.id">
      <div> {{ item.id }}</div>
      <div> {{ item.name }}</div>
     <table>
      <tbody>
        <tr v-if="item.info_user" :key="item.id">
            <th>{{ item.info_user.group_name }}</th>
            <th>{{ item.info_user.start_date }}</th>
            <th>{{ item.info_user.end_date }}</th>
            <th>{{ item.info_user.psubject_code }}</th>
          </tr>
      </tbody>
     </table>
    </div>
  </div>
</template>

<script>
import userApi from '~/plugins/api/userApi';
export default {
  name: 'IndexPage',
  data() {
    return {
      listUser : [],
      list : [
        { id: 1, name: ' thinh' },
        { id: 2, name: ' zxc' },
        { id: 3, name: ' cvb' },
        { 
          id: 4, 
          name: ' cb', 
          info_user: {
            group_name: 'MAR1021.04',
            start_date: '2024-03-04',
            end_date: '2024-04-10',
            psubject_code: 'MAR1021',
          } 
        },

      ],
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
