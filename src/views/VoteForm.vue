<template>
<div class="small-container">
   <div v-if="!userLoggedIn" class="logout" style="margin-top:300px;margin-bottom:20px;padding-top:10px;background-color: rgba(255, 255, 255, 0.8);border-radius: 15px;">
			<h3 style="text-align: center;">Not logged in </h3>
			<p style="text-align: center;"><button @click="login()">Login first</button></p>
  </div>
  <div v-if="userLoggedIn && user.voted=='1'" style="margin-top:300px;margin-bottom:20px;padding-top:10px;background-color: rgba(255, 255, 255, 0.8);border-radius: 15px;">
	<h3 style="text-align: center;">Olá, já votaste em {{user.voted_at}} </h3>
	<p style="text-align: center;"><button @click="close()">Fechar</button></p>
  </div>
  <div v-if="userLoggedIn && user.voted!='1'" style="margin-top:20px;margin-bottom:20px;padding-top:10px;background-color: rgba(255, 255, 255, 0.8);border-radius: 15px;">
	<h1 style="text-align: center">Escolhe o teu SuperPROF !</h1>
	<form @submit.prevent="handleSubmit">


			<table>
				<tr v-for="prof in profs" :key="prof.id">			
					<td><input style="border: 0px;width: 100%;height: 1.5em;" type="radio" :value="prof.id" v-model="vote.prof_id"></td>
					<td><label>{{prof.name}}</label></td>
		
				</tr>
			</table>

		<p v-if="error && submitting" class="error-message">
			❗Escolhe um Prof
		</p>
		
		<p style="float:left;"><button @click="cancel()" style="border-color: orange;background: orange;">Cancelar</button></p>
		<p style="float:right;"><button type="submit">Votar</button></p>

	</form>
  </div>
</div>
</template>

<script>
import { useProfsStore } from '@/store/profs'
import { useUserStore } from '@/store/user'
import { useBlockchainsStore } from '@/store/blockchains'


export default {  

	setup() {
		const userStore = useUserStore()
		const profsStore = useProfsStore()
		const blockchainsStore = useBlockchainsStore()

		return { userStore, profsStore, blockchainsStore}
  	},

	data() {
      return {
		submitting: false,
		error: false,
        vote: {
          prof_id: '',
        },
		user: {},
		profs: [],
      }
    },

	created: function () {
		this.getUser()
		this.getProfs()
	},
	
	methods: {
		handleSubmit() {
			this.submitting = true

			if (this.vote.prof_id=='') {
				this.error = true
				return
			}
			if (confirm(`Vais escolher para SuperPROF ${this.getProfByID(this.vote.prof_id)[0].name}.\nA votação é final!\nContinuar?`)) {				
				this.blockchain(this.vote)
            } 
            else {
                const txt = "You pressed Cancel!"
                console.log(txt);
            }

		},
		async blockchain(vote) {
			if ( await this.blockchainsStore.addBlockchainDB(vote) ){
				//update user voted
				this.userStore.updateUser()
				localStorage.setItem('message', `Sucesso, votaste para SuperPROF ${this.getProfByID(this.vote.prof_id)[0].name}`)	
				this.$router.push('/message/3')	
			}
		},
		login() {
			this.$router.push('/login')
		},
		cancel() {
			localStorage.setItem('message', 'Até já, volta rápido para votares!');
            this.$router.push('/message/2')
		},
		close() {
			localStorage.setItem('message', 'Aqui estão os resultados atualizados da votação!');
            this.$router.push('/message/3')
		},
		getUser() {
            this.user = this.userStore.getUser
		},
		async getProfs() {
			if ( await this.profsStore.getProfsDB() ) {
				//if get profs success
				this.profs = this.profsStore.getProfs
			}
		},
		getProfByID(id) {
            return this.profs.filter((prof) => prof.id === id)
        },
	},
	
	computed: {
		userLoggedIn: function () {
			this.getUser()
			for (var i in this.user) return true
			return false
		},
 
	},
}
</script>

<style scoped>
  form {
    margin-bottom: 2rem;
  }

  [class*='-message'] {
    font-weight: 500;
  }

 .logout {
	margin: 0 auto;
	max-width: 400px;
  }

  .error-message {
    color: #d33c40;
  }

  .success-message {
    color: #32a95d;
  }
</style>
