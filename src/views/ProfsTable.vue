<template >
<div class="small-container">
	<div v-if="!userLoggedIn">
		<h1 style="text-align: center">Welcome to the SuperProf App !</h1>	
	</div>
	<div v-if="showProfs" style="opacity:0.5;background-color: white;border-radius: 15px;">
		<p v-if="userLoggedIn" style="float:right;">Logout <router-link to="/logout">here</router-link>&nbsp;&nbsp;</p>
		<h1 style="text-align: center">Profs</h1>
		<table>
			<thead>
				<tr>
				<th>ID</th>									
				<th>Name</th>
				<th>Votes</th>
				<th>Last voted</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="prof in profs" :key="prof.id">	
					<td>{{prof.id}}</td>			
					<td>{{prof.name}}</td>
					<td>{{prof.votes}}</td>
					<td>{{prof.last_voted_at}}</td>			
				</tr>
			</tbody>
		</table>
		<b>&nbsp;&nbsp;<a href="/blockchains" @click.prevent="toggleProfs()">Show blockchains</a></b>
		<p v-if="!userLoggedIn" style="float:right;"><router-link to="/login"><button>Login to vote!</button></router-link></p>	
		<p v-else  style="float:right;"><button @click="goToVote()">Cast your Vote !</button></p>
	</div>
	<div v-else style="opacity:0.5;background-color: white;border-radius: 15px;">
		<h1 style="text-align: center">Blockchains</h1>
		
		<table>
			<thead>
				<tr>				
				<th>User ID</th>
				<th>Prof ID</th>
				<th>Date</th>
				<th>Blockchain</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="blockchain in blockchains" :key="blockchain.id">				
					<td>{{blockchain.user_id}}</td>
					<td>{{blockchain.prof_id}}</td>
					<td>{{blockchain.voted_at}}</td>
					<a href=""><td @click.prevent="showModal(blockchain.id)">{{blockchain.blockchain}}</td></a>		
				</tr>
			</tbody>
		</table>
		<b>&nbsp;&nbsp;<a href="/" @click.prevent="toggleProfs()">Show Profs</a></b>
	</div>
	<Modal
				v-show="isModalVisible"
				@close="closeModal"
				>
				<template v-slot:body>
					{{modalContents}}
				</template>

	</Modal>		
</div> 
</template>

<script>
import { useProfsStore } from '@/store/profs'
import { useUserStore } from '@/store/user'
import { useBlockchainsStore } from '@/store/blockchains'
import Modal from '@/components/Modal.vue';

export default {

	setup() {
		const userStore = useUserStore()
		const profsStore = useProfsStore()
		const blockchainsStore = useBlockchainsStore()

		return { userStore, profsStore,  blockchainsStore}
  	},
	components: {
      Modal,
    },
	
	data() {
		return {
			profs: [],
			blockchains: [],			
			user: {},
			showProfs: true,
			isModalVisible: false,
			modalContents: 'This is a new modal body.'
		}
	},
	created() {
			this.getProfs()
			this.getBlockchains()
	},
	methods: {
		showModal(id) {
			const previous_blockchain =  this.blockchains.filter((blk) => blk.id == (id-1))
			var old_blockchain = ''
			if (previous_blockchain.length != 0)
				old_blockchain = previous_blockchain[0].blockchain
			const present_blockchain = this.blockchains.filter((blk) => blk.id == id)
			this.modalContents = 'md5('+ old_blockchain+present_blockchain[0].user_id+present_blockchain[0].prof_id+present_blockchain[0].voted_at+')'
        	this.isModalVisible = true;
      	},
      	closeModal() {
        	this.isModalVisible = false;
      	},
		toggleProfs(){
			this.showProfs = !this.showProfs
		},
		async getProfs() {
			if ( await this.profsStore.getProfsDB() ) {
				//if get profs success
				this.profs = this.profsStore.getProfs
			}
		},
		async getBlockchains() {
			if ( await this.blockchainsStore.getBlockchainsDB() ) {
				//if get lockchains success
				this.blockchains = this.blockchainsStore.getBlockchains
			}
		},
		getUser() {
            this.user = this.userStore.user
		},
		goToVote() {
			this.$router.push('/input')
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
	button {
		margin: 0 0.5rem 0 0;
	}
</style>




