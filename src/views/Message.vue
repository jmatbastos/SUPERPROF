<template>
	<div class="logout">
			<h1 class="success-message" style="text-align: center;">{{message}}</h1>
			<div style="text-align: center;">
				<h5 class="circle">
				{{countdown}}	
				</h5>
			</div>

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
	.circle {
		padding-top: 12px; 
		color: white; 
		display: inline-block;
		text-align: center; 
		height: 50px;width: 50px;
		border-radius: 50%;
		background: blue;
	}	

</style>
