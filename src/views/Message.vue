<template>
	<div class="logout">
			<h1 class="success-message" style="text-align: center;">{{message}}</h1>
			<div class="color" style="text-align: center;">
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
        setMessage() {
			this.message = localStorage.getItem('message')				
			var timeleft = 2;
			var downloadTimer = setInterval(() => {
				if(timeleft <= 0){
					clearInterval(downloadTimer)
					console.log(this.$route.params.id)
					if (this.$route.params.id == 1)						
						this.$router.push('/input')
					if (this.$route.params.id == 2)					
						this.$router.push('/')
					if (this.$route.params.id == 3)	
						this.$router.push('/results')									
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
	margin-top: 200px;
	}

	.success-message {
	color: #32a95d;
	}
	.color{
		position: relative;
		background-color: #f4a261;
		height: 20px;
		border-radius: 25px;
		animation: progres 3s linear;    
	}
	@keyframes progres{
		0%{
		width: 100%;
		}
		25%{
			width: 75%;
		}
		50%{
			width: 50%;
		}
		75%{
			width: 25%;
		}
		100%{
			width: 0%;
		}
	}

</style>
