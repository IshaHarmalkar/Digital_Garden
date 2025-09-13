const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', redirect: '/log' }, // default route
      { path: 'home', component: () => import('pages/DashBoard.vue') },
      { path: 'log', component: () => import('pages/MoodLogPage.vue') },
      { path: 'reflect', component: () => import('pages/ReflectionPage.vue') },
      { path: 'calendar', component: () => import('pages/CalendarPage.vue') },
      { path: 'feedback', component: () => import('pages/FeedbackPage.vue') },
      {
        path: '/newsletter/:id/feedback',
        component: () => import('pages/FeedbackPage.vue'),
        name: 'newsletter-feedback',
      },
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
