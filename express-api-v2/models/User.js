import db from "../config/db.js";
import { DataTypes } from "sequelize";

const User = db.define("users",{
    username : {
        type: DataTypes.STRING,
        allowNull: false
    },
    email : {
        type: DataTypes.STRING,
        allowNull: false
    },
    password : {
        type: DataTypes.STRING,
        allowNull: false
    }
})

try {
    await db.sync();
    console.log("The table users was Created")
} catch (error) {
    console.error("Cannot Create Table: "+error)
}

export default User