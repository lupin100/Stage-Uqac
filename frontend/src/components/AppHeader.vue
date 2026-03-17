<script setup>
import logo from '../assets/logo_uqac.svg'
</script>

<template>
  <header class="header">
     <div class="top-bar">
      <div class="logo-title">
         <img :src="logo" alt="Logo UQAC" class="logo" />
        <h1>Groupe de recherches en informatique (GRI)</h1>
      </div>

      <input type="text" placeholder="Rechercher..." class="search-bar" />
    </div>
    <nav>
      <ul class="nav-links">
        <li><a href="#">Accueil</a></li>
        <li><a href="#">Laboratoires</a></li>
        <li><a href="#">Membres</a></li>
        <li><a href="#">Thématiques</a></li>
        <li><a href="#">Publications</a></li>
        <li><a href="#">Projets</a></li>
        <li><a href="#">Évènements</a></li>
        <li><a href="#">Nouvelles</a></li>
        <li><a href="#">Nous joindre</a></li>
      </ul>
    </nav>
  </header>
</template>

<style scoped>
.header {
  background-color: #42b883;
  color: white;
  padding: 1rem 2rem;
  width: 100%;
  box-sizing: border-box;
}

h1 {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  font-size: 1.4rem;
  margin: 0;
  white-space: nowrap;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 1.5rem;
  margin: 0;
  padding: 0;
  justify-content: space-between;
}

.nav-links a {
  color: white;
  text-decoration: none;
  font-weight: bold;
}

.nav-links a:hover {
  text-decoration: underline;
}

.top-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap; /* responsive */
}

.logo {
  width: 140px;
  height: auto;
}

.logo-title {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.search-bar {
  padding: 0.4rem 0.8rem;
  border-radius: 6px;
  border: none;
  outline: none;
  font-size: 0.9rem;
  min-width: 180px;
}

.search-bar:focus {
  box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.4);
}


</style>