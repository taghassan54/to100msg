import { Router } from 'express'
import sessionsRoute from './routes/sessionsRoute.js'
import chatsRoute from './routes/chatsRoute.js'
import groupsRoute from './routes/groupsRoute.js'
import authRoute from './routes/auth.js'

import response from './response.js'

const router = Router()

router.use('/sessions', sessionsRoute)
router.use('/sessions/chats', chatsRoute)
router.use('/sessions/groups', groupsRoute)
router.use('/sessions/auth', authRoute)
router.all('*', (req, res) => {
   
    response(res, 404, false, 'The requested url cannot be found.')
})

export default router
