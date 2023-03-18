<script>
import Home from './components/Home.vue'
import Header from './components/Header.vue'
import Footer from './components/Footer.vue'

export default {
  name:'App',
  components: {
      Home,
      Header,
      Footer
  }
}

</script>

<template>
  <div class="bg_color">
    <Header />
    <Home />
    <Footer />
  </div>
</template>

<style scoped>
  .bg_color{
  

    background-image: linear-gradient(to left, rgba(0, 0, 0, 0), rgba(85, 85, 85, 0.5)), url('assets/images/home.jpg');
    background-size: cover;
    background-position: center;
    height: 100vh;
    opacity: 1;
  }
</style>
