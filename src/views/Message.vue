<template>
	<div class="logout">
			<h1 class="success-message" style="text-align: center;">{{message}}</h1>
			<p style="text-align: center;">
				<button @click="goToTable()" style="background: blue;">{{countdown}}</button>	
			</p>
	</div>
</template>

<script>

import { useUserStore } from '@/store/user'



export default {
	setup() {
		const userStore = useUserStore()
		return { userStore }
  	},

    data() {
        return {
            message: '',
			countdown: 3
        }
	},
	mounted() {
		this.setMessage()
	},


	methods: {
		goToTable() {
			this.$router.push('/')
		},
        setMessage() {
			this.message = localStorage.getItem('message')	
			
			var timeleft = 2;
			var downloadTimer = setInterval(() => {
				if(timeleft <= 0){
					clearInterval(downloadTimer)
					this.$router.push('/')
				}
			timeleft -= 1
			this.countdown -= 1
			}, 1000)
		},


	},
   

}
</script>

<style scoped>
	.logout {
	margin: 0 auto;
	max-width: 800px;
	}

	.success-message {
	color: #32a95d;
	}

	.error-message {
	color: #d33c40;
	}

</style>
