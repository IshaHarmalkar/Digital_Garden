<template>
  <q-layout view="lHh Lpr lFf">
    <!-- Header -->

    <!--
 <q-header>
      <q-toolbar>
        
        <q-btn
          v-if="$q.screen.lt.md"
          flat
          dense
          round
          icon="menu"
          @click="leftDrawerOpen = !leftDrawerOpen"
        />
        <q-toolbar-title>Mood Tracker</q-toolbar-title>
      </q-toolbar>
    </q-header>

     -->
    <!-- Drawer -->
    <q-drawer
      v-model="leftDrawerOpen"
      bordered
      class="drawer"
      :width="150"
      show-if-above
      behavior="desktop"
    >
      <q-list>
        <!-- logo -->
        <!-- <q-item clickable v-ripple>
          <q-item-section side>
            <q-item-label class="text-white plain-cursive text-h3">Curated</q-item-label>
          </q-item-section>
        </q-item> -->

        <!-- Profile section -->
        <q-item class="profile q-pa-md flex flex-center column">
          <q-avatar size="80px">
            <img src="https://i.pinimg.com/736x/0f/13/53/0f1353d0a3cf38d7b157136d32d6de34.jpg" />
          </q-avatar>
        </q-item>

        <q-item v-for="link in links" :key="link.title" clickable v-ripple :to="link.link">
          <q-item-section avatar>
            <q-icon :name="link.icon" />
          </q-item-section>
          <q-item-section side>
            <q-item-label class="text-white">{{ link.title }}</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <!-- Page Container -->
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
export default {
  name: 'MainLayout',
  data() {
    return {
      leftDrawerOpen: false,
      links: [
        { title: 'Home', caption: 'Dashboard', icon: 'home', link: '/home' },
        { title: 'Mood', caption: 'Track your mood', icon: 'edit', link: '/log' },
        { title: 'Journal', caption: 'Journal & Gratitude', icon: 'menu_book', link: '/reflect' },
        // { title: 'Calendar', caption: 'Calendar View', icon: 'calendar_month', link: '/calendar' },
        { title: 'Today', caption: 'Daily overview', icon: 'today', link: '/today' },
        { title: 'Summary', caption: 'Trends & Reports', icon: 'bar_chart', link: '/summary' },
        { title: 'Feedback', caption: 'Feedback', icon: 'assistant', link: '/feedback' },
      ],
    }
  },
}
</script>
<style>
.drawer {
  background-color: #fbc0de;
  color: white;
}
</style>
