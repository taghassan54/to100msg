import {Router} from "express";
import jwt from "jsonwebtoken";

const router = Router()

router.post('/login', (req, res) => {
    const { username, appid } = req.body;
    const token = jwt.sign({ username: username,appid:appid }, process["env"]["APP_KEY"], {
        expiresIn: '1h',
    });
    res.status(200).json({ message: 'Protected route accessed' ,token:token});
});

export default router