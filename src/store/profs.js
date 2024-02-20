import { defineStore } from 'pinia'
import { useUserStore } from './user'

export const useProfsStore = defineStore({
    id: 'profs',
    state: () => ({
        profs: [
            // {
            //"id":"1",
            //"name":"Peter Frampton",
            //"votes":"0",
            //"last_voted_at":"2024-02-19 02:06:58",            
            //}
        ]
    }),
    getters: {
        getProfs (state) {
            return state.profs;
        }, 
    }, 
    actions: {   
        async getProfsDB() {
			try {
				const response = await fetch('http://daw.deei.fct.ualg.pt/~a333330/api/profs.php')
				const data = await response.json()
                this.profs = data
                return true
			} 
			catch (error) {
				console.error(error)
                return false
			}
		},
    }
})
