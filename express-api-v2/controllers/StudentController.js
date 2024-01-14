import Student from "../models/Student.js";
import { Op } from "sequelize";
import { validationResult } from 'express-validator';
import Validation from '../validation/StudentsValidation.js';

class StudentController {
    async index(req,res){
        try {
            let filter = {}
            let where = {}
            // untuk filter menggunakan filter[nama],filter[jurusan]
            console.log(req.query.filter)
            if(req.query.filter != undefined){

                if(req.query.filter["nama"] != '' && req.query.filter["nama"] != undefined) {
                    where.nama = {
                        [Op.like]: '%' + req.query.filter["nama"] + '%'
                    }
                }
        
                if(req.query.filter["jurusan"] != ''&& req.query.filter["jurusan"] != undefined) {
                    where.jurusan = {
                        [Op.like]: '%' + req.query.filter["jurusan"] + '%'
                    }
                }
        
                console.log()
                if (Object.keys(where).length > 0){
                    filter.where = where
                }
            }
            let order
            let sortBy = "id"
            let orderBy = "ASC"
            // untuk sorting menggunakan sort dan order
            if(req.query.sort != undefined && req.query.sort != ''){
                let filterSort = req.query.sort.toLowerCase()
                if (!['name', 'jurusan', 'id'].includes(filterSort)) {
                    return res.status(500).json({ errors: "Sort can only be nama, jurusan and id" }); 
                }else{
                    sortBy = filterSort

                }
            }

            if(req.query.order != undefined && req.query.order != ''){
                let filterOrder = req.query.order.toLowerCase()
                if(!['asc','desc'].includes(filterOrder)){

                }else{
                    orderBy = req.query.order
                }
            }

            order = [sortBy,orderBy]
            filter.order = [order]
            console.log(filter)
            const students = await Student.findAll(filter)
            const data = {
                message : "Show All Data Students",
                data : students
            }
            return res.json(data)
        } catch (error) {
            const data = {
                message : "Error"
            }
            console.error("Error get Data User : "+error)
            return res.status(500).json({ errors: "Internal Server Error" });
        }
    }

    async store(req,res){
        try {
            await Promise.all(Validation.createValidation.map((validation) => validation.run(req)));
            const errors = validationResult(req);

            if (!errors.isEmpty()) {
                const formattedErrors = errors.array().map((error) => ({
                  [error.path]: error.msg
                }));
        
                return res.status(422).json({ errors: formattedErrors,message:"All fields are required" });
            }
                
            const { nama, nim, email, jurusan } = req.body;

            const checkNimStudent = await Student.findOne({
                where:{
                    nim : nim
                }
            })
    
            if(checkNimStudent != null){
                return res.status(400).json({errors : "Nim is available"})
            }


            const newStudent = await Student.create({
                nama : nama,
                nim : nim,
                email : email,
                jurusan : jurusan,
            });
    
            let data =  {
                message : "Student Created Successfullt",
                data : newStudent
            }
            res.status(201).json(data);
        } catch (error) {
            console.error("Error creating student:", error);
            res.status(500).json({ errors: "Internal Server Error" });
        }
    }

    async update(req,res){
        try {
            await Promise.all(Validation.updateValidation.map((validation) => validation.run(req)));
            const errors = validationResult(req);
            if (!errors.isEmpty()) {
                const formattedErrors = errors.array().map((error) => ({
                  [error.path]: error.msg
                }));
        
                return res.status(422).json({ errors: formattedErrors,message:"At least one field is required for update" });
            }
            const { id } = req.params;
            const { nama, nim, email, jurusan } = req.body;
    
            if (!nama && !nim && !email && !jurusan) {
                return res.status(400).json({ errors: "At least one field is required for update" });
            }
            
    
            const studentToUpdate = await Student.findByPk(id);
    
            if (studentToUpdate == null) {
                return res.status(404).json({ errors: "Student not found" });
            }
    
            const fieldsToUpdate = {
                nama: nama || studentToUpdate.name,
                nim: nim || studentToUpdate.nim,
                email: email || studentToUpdate.email,
                jurusan: jurusan || studentToUpdate.jurusan,
            };
    
            let data =  {
                message : "Student Updated Successfully",
                data : fieldsToUpdate
            }
            
            await studentToUpdate.update(fieldsToUpdate);
    
            res.status(200).json(data);
        } catch (error) {
            console.error("Error updating student:", error);
            res.status(500).json({ errors: "Internal Server Error" });
        }
    }

    async delete(req,res){
        try {
            await Promise.all(Validation.deleteValidation.map((validation) => validation.run(req)));
            const errors = validationResult(req);
            if (!errors.isEmpty()) {
                const formattedErrors = errors.array().map((error) => ({
                  [error.path]: error.msg
                }));
        
                return res.status(422).json({ errors: formattedErrors,message:"At least one field is required for update" });
            }
            const { id } = req.params;
            
            const studentToDelete = await Student.findByPk(id);
    
            if (studentToDelete == null) {
                return res.status(404).json({ error: "Student not found" });
            }
    
            let data =  {
                message : "Student deleted Successfully",
            }
    
            await studentToDelete.destroy();
    
            res.status(200).json(data);
        } catch (error) {
            console.error("Error deleting student:", error);
                res.status(500).json({ errors: "Internal Server Error" });
        }
    }

}

const controller = new StudentController()

export default controller