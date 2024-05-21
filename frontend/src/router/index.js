import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import LandingView from '../views/LandingView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      // name: 'home',
      name: 'Login',
      // component: HomeView
      component: LoginView
    },
    {
      path: '/landing',
      name: 'landing',
      component: LandingView
    }
  ]
})

export default router
