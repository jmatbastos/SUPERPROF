<template>
	<div>
		<div class="logout">
			<h1 style="text-align: center;">Logout ?</h1>
			<p style="text-align: center;">
				<button @click="cancel()" style="border-color:orange; background: orange;">Cancel</button>	
				&nbsp;&nbsp;			
				<button @click="logout()">Logout</button>
			</p>
		</div>
	</div>
</template>

<script>


import { useUserStore } from '@/store/user'



export default {
	setup() {
		const userStore = useUserStore()

		return { userStore}
  	},
	data() {
		return {
			success: false,
			user: {},
		}
	},


	methods: {
		logout: function() {

			this.success = true
			
			if (this.userLoggedIn) {
				// User is signed in		
				// destroy session
				this.logoutUser(this.user.session_id)
			} else {
				// No user is signed in.
				this.$router.push('/')
			}

		},

		async logoutUser(session_id) {
			if ( await this.userStore.logoutUserDB(session_id) ) {
				// using local storage to pass information between views
				localStorage.setItem('message', 'Bye, many thanks for voting!');
                this.$router.push('/message/2')
			}
		},

		cancel: function(){
			this.$router.push('/results')
		},

		getUser() {
            this.user = this.userStore.getUser
		},

	},
   
	created: function () {
		if (this.userLoggedIn) {
			// User is signed in
			this.success = false
		} else {
			// No user is signed in.
			this.success = true		
		}
	},
	
	computed: {
		userLoggedIn: function () {
			this.getUser()
			for (var i in this.user) return true
			return false
		},
	}

}
</script>

<style scoped>
 .logout {
	margin: 0 auto;
	max-width: 400px;
	margin-top: 50px; 
	border-radius: 25px; 
	padding: 25px; 
	background-color: rgba(255, 255, 255, 0.3);
  }
</style>
