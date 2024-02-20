import { defineStore } from 'pinia'


export const useUserStore = defineStore({
    id: 'user',
    state: () => ({
        user: { 
            //"id":"1",
            //"password_digest":"827ccb0eea8a706c4c34a16891f84e7b",
            //"voted":"1", 
            //"voted_at":"2024-02-19 02:06:58", 			
            //"session_id":"s47fcd7q4f2tm6rhdgfubn53ov",
            },
    }),
    getters: {
        getUser (state) {
            return state.user
        }, 
    },
    actions: {  
		updateUser(){
			this.user.voted='1'
		},
        async loginUserDB(user) {
			try {
				const response = await fetch(`http://daw.deei.fct.ualg.pt/~a333330/api/users.php?password=${user.password}`)
				const data = await response.json()
				if ( data == null) {
					alert('Error: Wrong credentials')
					return false
                }
                else {
					console.log('received data:', data)
                    this.user = data
                    return true
                }
			} 
			catch (error) {
				console.error(error)
				alert('Error: Database connection failed')	
				return false			
			}
		},
        async logoutUserDB() {
			try {
				const response = await fetch(`http://daw.deei.fct.ualg.pt/~a333330/api/users.php?session_id=${this.user.session_id}`)
				const data = await response.json()
                console.log('received data:',data)
                this.user = {}
                return true
			} 
			catch (error) {
				console.error(error)
                return false
			}
		},                
    }          

})