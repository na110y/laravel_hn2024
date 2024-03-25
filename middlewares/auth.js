export default function ({ app, redirect }) {
    if (!app.$auth.loggedIn || !app.$auth.user.role) {
      redirect('/')
    }
  }