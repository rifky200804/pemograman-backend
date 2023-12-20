let models = require('../models/students')
class StudentController{
    index(req,res){
        // res.send("Menampilkan semua Students")
        let response = {
            "message" : "Get All Students",
            "data" : models,
         }
        
        res.json(response)
    }

    store(req,res){
        try {
            
            let { name } = req.body
            let getIndexID = models.length + 1
            let student = {"id":getIndexID,"name":name}
            models.push(student)
            let response = {
                "message" : `Add Data Students ${name}`,
                "data" : models,
             }
            
            res.json(response)
        } catch (error) {
            console.log(error)
            res.json({"error":"Failed To Add Data"})
        }
    }

    update(req,res){
        try {
            const { id } = req.params
            let { name } = req.body
            for (let index = 0; index < models.length; index++) {
                if(models[index].id == id){
                    models[index].name = name
                }
            }
            let response = {
                "message" : `Updated Data Students id ${id}`,
                "data" : models,
             }
            
            res.json(response)
        } catch (error) {
            console.log(error)
            res.json({"error":"Failed To Updated Data"})
        }
    }

    destroy(req,res){
        try {
            const { id } = req.params
            for (let index = 0; index < models.length; index++) {
                if(models[index].id == id){
                    models.splice(index,1)
                }
            }
            let response = {
                "message" : "Success Deleted Data"
             }
            res.json(response)
        } catch (error) {
            console.log(error)
            res.json({"error":"Failed To Deleted Data"})
        }
    }
}

const controllers = new StudentController()

module.exports = controllers