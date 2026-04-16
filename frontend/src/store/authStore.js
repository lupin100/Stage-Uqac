import { reactive } from 'vue'

export const authStore = reactive({
  isAdmin: localStorage.getItem('isAdmin') === 'true',
  
  login() {
    this.isAdmin = true
    localStorage.setItem('isAdmin', 'true')
  },
  
  logout() {
    this.isAdmin = false
    localStorage.removeItem('isAdmin')
  }
})