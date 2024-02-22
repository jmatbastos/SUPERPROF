import { createRouter, createWebHistory } from 'vue-router'
import ProfsTable from '../views/ProfsTable.vue'
import UserLogin from '../views/UserLogin.vue'
import Message from '../views/Message.vue'
import UserLogout from '../views/UserLogout.vue'
import VoteForm from '../views/VoteForm.vue'
import Home from '../views/Home.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      component: Home
    },
    {
      path: '/login',
      component: UserLogin
    },
    {
      path: '/message/:id?',
      component: Message
    },
    {
      path: '/input',
      component: VoteForm
    }, 
    {
      path: '/results',
      component: ProfsTable
    }, 
    {
      path: '/logout',
      component: UserLogout
    },
  
  ]
})

export default router
