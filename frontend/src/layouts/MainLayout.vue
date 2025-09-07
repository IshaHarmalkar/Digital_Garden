<template>
  <q-layout view="lHh Lpr lFf">
    <!-- Header -->
    <q-header elevated>
      <q-toolbar>
        <q-btn flat dense round icon="menu" @click="leftDrawerOpen = !leftDrawerOpen" />
        <q-toolbar-title>Mood Tracker</q-toolbar-title>
      </q-toolbar>
    </q-header>

    <!-- Drawer -->
    <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
      <q-list>
        <q-item v-for="link in links" :key="link.title" clickable v-ripple :to="link.link">
          <q-item-section avatar>
            <q-icon :name="link.icon" />
          </q-item-section>
          <q-item-section>
            <q-item-label>{{ link.title }}</q-item-label>
            <q-item-label caption>{{ link.caption }}</q-item-label>
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
        {
          title: 'Home',
          caption: 'Dashboard',
          icon: 'home',
          link: '/home',
        },
        {
          title: 'Log Mood',
          caption: 'Track your mood',
          icon: 'edit',
          link: '/log',
        },
        {
          title: 'Reflect',
          caption: 'Journal & Gratitude',
          icon: 'menu_book',
          link: '/reflect',
        },
        {
          title: 'Today',
          caption: 'Daily overview',
          icon: 'today',
          link: '/today',
        },
        {
          title: 'Summary',
          caption: 'Trends & Reports',
          icon: 'bar_chart',
          link: '/summary',
        },
      ],
    }
  },
}
</script>
