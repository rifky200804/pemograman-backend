// controllers/AuthController.js
import User from "../models/User.js";
import bcrypt from "bcrypt";
import jwt from "jsonwebtoken";
import { validationResult } from 'express-validator';
import Validation from '../validation/AuthValidation.js';

class AuthController {
  async register(req, res) {
    try {
        await Promise.all(Validation.registerValidation.map((validation) => validation.run(req)));
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            const formattedErrors = errors.array().map((error) => ({
              [error.path]: error.msg
            }));
    
            return res.status(422).json({ errors: formattedErrors,message:"All fields are required" });
        }

        const { username, email, password } = req.body;

        const hash = await bcrypt.hash(password, 10);
        console.log(hash);
        const newUser = await User.create({
            username: username,
            email: email,
            password: hash,
        });

        const response = {
            message: "User Created Successfully",
            data: newUser,
        };

        return res.status(201).json(response);
    } catch (error) {
      console.error("Error creating user:", error);
      res.status(500).json({ error: "Internal Server Error" });
    }
  }

  async login(req, res) {
    try {
        await Promise.all(Validation.loginValidation.map((validation) => validation.run(req)));
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            const formattedErrors = errors.array().map((error) => ({
              [error.path]: error.msg
            }));
    
            return res.status(422).json({ errors: formattedErrors,message:"Username and Password are required" });
        }
        
        const { username, password } = req.body;


        const user = await User.findOne({
            where: { username: username },
        });

        if (!user) {
            const response = {
            message: "Authentication Failed",
            };
            return res.status(401).json(response);
        }

        const match = await bcrypt.compare(password, user.password);
        if (!match) {
            const response = {
            message: "Authentication Failed",
            };
            return res.status(401).json(response);
        }

        const payload = {
            id: user.id,
            username: user.username,
        };

        const secret = process.env.TOKEN_SECRET;
        console.log(secret);
        const token = jwt.sign(payload, secret, { expiresIn: "24h" });

        const response = {
            message: "Login Success",
            data: {
            token: token,
            username: payload.username,
            },
        };
        return res.status(200).json(response);
    } catch (error) {
        console.error("Error during login:", error);
        res.status(500).json({ error: "Internal Server Error" });
    }
  }
}

const controller = new AuthController();

export default controller;
