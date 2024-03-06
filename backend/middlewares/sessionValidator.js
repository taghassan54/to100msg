import { isSessionExists } from '../whatsapp.js'
import response from './../response.js'
import jwt  from 'jsonwebtoken';
const validate = (req, res, next) => {
    
        const token = req.header('Authorization');
    if (!token) return res.status(401).json({error: 'Access denied'});
    console.log("token",token)
    const decoded = jwt.verify(token, process["env"]["APP_KEY"]);
    if (!decoded) return res.status(401).json({error: 'Access denied'});
    console.log("decoded",decoded)
    
    const sessionId = req.query.id ?? req.params.id

    if (!isSessionExists(sessionId)) {
        return response(res, 404, false, 'Session not found.')
    }

    res.locals.sessionId = sessionId
    next()
}

export default validate
