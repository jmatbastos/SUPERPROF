<template>

	<div id="login-form" style="margin-top: 200px; margin-bottom: 200px; border-radius: 25px; padding: 25px; background-color: rgba(255, 255, 255, 0.8);">
		<h1 style="text-align: center">Login</h1>
		<form @submit.prevent="handleSubmit">		
			<label>Password</label>
			<input
				type="password"
				:class="{ 'has-error': submitting && invalidPassword }"
				v-model="user.password"
				v-autofocus
				@keypress="clearStatus"	
			/>
			<p v-if="error && submitting" class="error-message">
				❗Tens que entrar a tua password
			</p>
			<br>
			<button @click="cancel()" style="border-color: orange;background: orange;">Cancelar</button>	
			<p style="float:right;"><button type="submit">Login</button></p>
		</form>
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
        user: {
			password: '',
        },
		submitting: false,
		error: false,
      }
    },
	
	methods: {
		handleSubmit: function () {
			this.submitting = true
			this.clearStatus()

			if (this.invalidPassword) {
				this.error = true		
				return
			}

			this.loginUser(this.user)					
			
			
		},
		clearStatus: function () {
			this.error = false
		},

		async loginUser(user) {
			if (this.user.password == 'SuperProf') {
					localStorage.setItem('message', 'Bem-vindo, aqui estão os resultados atuais da votação!')				
					this.$router.push('/message/3')
			}
			else if ( await this.userStore.loginUserDB(user) ) {
				//login valid	
					localStorage.setItem('message','Bem-vindo, já podes votar!')				
					this.$router.push('/message/1')
				

			}				
			else {
				//login failed				
				this.error = true
				this.submitting = false							
			}			
						
		},
		cancel() {
			localStorage.setItem('message', 'Até logo, volta em breve para votares!')				
			this.$router.push('/message/2')
		},
	},
	
	computed: {
		invalidPassword: function () {
			if (this.user.password === '' ) return true
			else return false
		},		


	},
	directives: {
		autofocus: {
			inserted(el) {
				el.focus()
			}
		}
	},
	created: function () {
		this.submitting = false
		this.error = false	
	}
}
</script>

<style scoped>
 #login-form {
	margin: 0 auto;
	max-width: 400px;
  }
  form {
    margin-bottom: 2rem;
  }

  [class*='-message'] {
    font-weight: 500;
  }

  .error-message {
    color: #d33c40;
  }

  .success-message {
    color: #32a95d;
  }
</style>
