import jwt from "jsonwebtoken"

const auth = (req,res,next) => {
    // biisa menggunakan req.headers/req.get

    const authorization = req.get("Authorization")
    console.log(authorization)
    if(!authorization) {
        const response = {
            message:"Please Provide Token"
        }

        res.status(401).json(response)
    }
    const token = authorization && authorization.split(" ")[1];


    try {
        const decoded = jwt.verify(token,process.env.TOKEN_SECRET)
        req.user = decoded
        next();
    } catch (error) {
        const response = {
            message : "Authentication Failed"
        }
        res.status(401).json(response)
    }
}

export default auth