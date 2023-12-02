const {index,store} = require('./controllers/fruitController.js')

const main = () => {
    index()
    console.log("// end index")
    store("Apple")
}

main()