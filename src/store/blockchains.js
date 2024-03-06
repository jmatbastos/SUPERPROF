import { defineStore } from 'pinia'
import { useUserStore } from './user'

export const useBlockchainsStore = defineStore({
    id: 'blockchains',
    state: () => ({
        blockchains: [
            // {
            //"id":"1",
            //"user_id":"1",
            //"prof_id":"1",
            //"voted_at":"2024-02-19 02:06:58",   
            //"blockchain":"d8578edf8458ce06fbc5bb76a58c5ca4",                       
            //}
        ]
    }),
    getters: {
        getBlockchains (state) {
            return state.blockchains;
        }, 
    }, 
    actions: {   
        async getBlockchainsDB() {
			try {
                const userStore = useUserStore()
				const response = await fetch(`http://all.deei.fct.ualg.pt/~a333330/api/blockchains.php`)
				const data = await response.json()
                this.blockchains = data
                return true
			} 
			catch (error) {
				console.error(error)
                return false
			}
		},
        async addBlockchainDB(vote) {
			try {
                // vote={
                //    prof_id: 1
                //}
                const userStore = useUserStore()                
				const response = await fetch(`http://all.deei.fct.ualg.pt/~a333330/api/blockchains.php?session_id=${userStore.user.session_id}`, {
					method: 'POST',
					body: JSON.stringify(vote),
					headers: { 'Content-type': 'application/json; charset=UTF-8' },
				})
				const data = await response.json()
                this.blockchains = [...this.blockchains, data]
                return true
			} 
			catch (error) {
				console.error(error)
                return false
			}
		},
    }
})
