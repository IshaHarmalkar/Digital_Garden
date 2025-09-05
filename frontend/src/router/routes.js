const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', redirect: '/log' }, // default route
      { path: 'log', component: () => import('pages/MoodLogPage.vue') },
      // { path: 'today', component: () => import('pages/TodayPage.vue') },
      // { path: 'summary', component: () => import('pages/SummaryPage.vue') },
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
]

export default routes
