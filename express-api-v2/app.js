import  express  from "express"
import routes from "./routes/api.js"
import logger from "./middleware/logger.js"
// import auth from "./middlewaree/auth.js"
import dotenv from 'dotenv';
const app = express();

app.use(express.json())
app.use(express.urlencoded())

dotenv.config();

app.use(logger)
// app.use(auth)
app.use(routes)

const port = process.env.APP_PORT || 3000
app.listen(port,()=>{
    console.log("Server Berjalan di http://localhost:"+port)
})

