import { createRouter, createWebHistory } from 'vue-router'
import ProfsTable from '../views/ProfsTable.vue'
import UserLogin from '../views/UserLogin.vue'
import Message from '../views/Message.vue'
import UserLogout from '../views/UserLogout.vue'
import VoteForm from '../views/VoteForm.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: ProfsTable
    },
    {
      path: '/login',
      component: UserLogin
    },
    {
      path: '/message',
      component: Message
    },
    {
      path: '/input',
      component: VoteForm
    }, 
    {
      path: '/logout',
      component: UserLogout
    },
  
  ]
})

export default router
