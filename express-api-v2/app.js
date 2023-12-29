import  express  from "express"
import routes from "./routes/api.js"
import dotenv from 'dotenv';
const app = express();

app.use(express.json())
app.use(express.urlencoded())

dotenv.config();

app.use(routes)

const port = process.env.APP_PORT || 3000
app.listen(port,()=>{
    console.log("Server Berjalan di http://localhost:"+port)
})

