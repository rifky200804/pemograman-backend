import  express  from "express"
const router = express.Router()

import auth from "../middleware/auth.js"
import StudentController from '../controllers/StudentController.js'
router.get('/students',auth,StudentController.index)
router.post('/students',auth,StudentController.store)
router.put('/students/:id',auth,StudentController.update)
router.delete('/students/:id',auth,StudentController.delete)

import AuthController from '../controllers/AuthController.js'
router.post('/register',AuthController.register)
router.post('/login',AuthController.login)

export default router;