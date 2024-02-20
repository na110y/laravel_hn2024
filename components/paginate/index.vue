<template>
  <div class="w-100">
    <b-row class="pagination">
      <b-col cols="4">
        <button
          class="navigate-button"
          type="button"
          @click="onClickFirstPage"
          :disabled="isInFirstPage"
          aria-label="Go to first page"
        >
          Trang đầu
        </button>

        <button
          class="navigate-button"
          type="button"
          @click="onClickPreviousPage"
          :disabled="isInFirstPage"
          aria-label="Go to previous page"
        >
          Trang trước
        </button>

        <button
          class="navigate-button"
          type="button"
          @click="onClickNextPage"
          :disabled="isInLastPage"
          aria-label="Go to next page"
        >
          Trang sau
        </button>
      </b-col>

      <b-col cols="4">
        <div v-for="(page, index) in pages" :key="index" class="paginate-item">
          <button
            class=""
            v-if="page.name > 0"
            type="button"
            @click="onClickPage(page.name)"
            :disabled="page.isDisabled"
            :class="[{ active: isPageActive(page.name) }, 'paginate-button']"
            :aria-label="`Go to page number ${page.name}`"
          >
            {{ page.name }}
          </button>
        </div>
      </b-col>

      <b-col cols="4">
        <button
          class="navigate-button"
          type="button"
          @click="onClickLastPage"
          :disabled="isInLastPage"
          aria-label="Go to last page"
        >
          Trang cuối
        </button>

        <button class="navigate-button" type="button" disabled aria-label="Go to last page">
          Trang {{ currentPage + "/" + totalPages }}
        </button>

        <button class="navigate-button" type="button" disabled aria-label="Go to last page">
          {{ total }} kết quả
        </button>
      </b-col>
    </b-row>
  </div>
</template>

<script>
export default {
  name: "pagination",
  template: "#pagination",
  props: {
    maxVisibleButtons: {
      type: Number,
      required: false,
      default: 3,
    },
    totalPages: {
      type: Number,
      required: true,
    },
    total: {
      type: Number,
      required: true,
    },
    perPage: {
      type: Number,
      required: true,
    },
    currentPage: {
      type: Number,
      required: true,
    },
  },
  computed: {
    startPage() {
      if (this.currentPage === 1) {
        return 1;
      }

      if (this.currentPage === this.totalPages) {
        return this.totalPages - this.maxVisibleButtons + 1;
      }

      return this.currentPage - 1;
    },
    endPage() {
      return Math.min(
        this.startPage + this.maxVisibleButtons - 1,
        this.totalPages
      );
    },
    pages() {
      const range = [];

      for (let i = this.startPage; i <= this.endPage; i += 1) {
        range.push({
          name: i,
          isDisabled: i === this.currentPage,
        });
      }

      return range;
    },
    isInFirstPage() {
      return this.currentPage === 1;
    },
    isInLastPage() {
      return this.currentPage === this.totalPages;
    },
  },
  methods: {
    onClickFirstPage() {
      this.$emit("pagechanged", 1);
    },
    onClickPreviousPage() {
      this.$emit("pagechanged", this.currentPage - 1);
    },
    onClickPage(page) {
      this.$emit("pagechanged", page);
    },
    onClickNextPage() {
      this.$emit("pagechanged", this.currentPage + 1);
    },
    onClickLastPage() {
      this.$emit("pagechanged", this.totalPages);
    },
    isPageActive(page) {
      return this.currentPage === page;
    },
  },
};
</script>

<style scoped>
.paginate-item {
  display: inline-block;
  padding: 5px;
}

.active {
  background-color: #0619c5dc  !important;
  color: #FFFFFF !important;
}

.paginate-button {
  background-color: #FFFFFF;
  color: #000000;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  min-height: 40px;
  min-width: 40px;
  box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
}

.paginate-button:hover {
  color: #0619c580;
  opacity: 0.9;
  box-shadow: 0px 10px 15px -3px #0619c580;
}

.navigate-button {
  background-color: #FFFFFF;
  color: #393E46;
  border: 1px solid #dee2e6 ;
  margin: 5px;
  border-radius: 5px;
  cursor: pointer;
  min-width: 100px;
  min-height: 40px;
  box-shadow: 0px 10px 15px -3px rgba(0,0,0,0.1);
}

.navigate-button:hover {
  color: #0619c580;
  opacity: 0.9;
  box-shadow: 0px 10px 15px -3px #0619c580;
}
</style>