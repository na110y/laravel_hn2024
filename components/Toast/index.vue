<template>
    <div>
        <b-toast
            ref="customToast"
            no-auto-hide
            :variant="toastData.variant"
            class="my-custom-toast"
            >
            <template #toast-title>
                <strong>Thông báo</strong>
            </template>
            <span class="my-custom-toast-message">{{ toastData.message }}</span>
        </b-toast>
    </div>
</template>
<script>
export default {
    name:'vtoast',
    props: {
        showToast: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            toastData: {
                variant: 'info',
                message: null
            }
        };
    },
    watch: {
        showToast(newValue) {
            this.toastData = newValue;
            this.$refs.customToast.show();
            setTimeout(() => {
                this.$refs.customToast.hide();
            }, 3000);
    }
  }
}
</script>