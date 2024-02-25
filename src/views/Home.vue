<template >

    <div class="small-container">
        <div style="margin-top: 100px; 	margin-bottom:100px;text-align: center">
            <h1 >Bem-vindo ao SuperPROF !</h1>	
            <button @click="Login()">Escolhe o teu SuperPROF !</button>
        </div>

    </div>


</template>

<script>


export default {
	methods: {
		Login() {
			this.$router.push('/login')
		}		
	},

}
</script>

<style scoped>
	button {
		margin: 0 0.5rem 0 0;
	}
</style>




