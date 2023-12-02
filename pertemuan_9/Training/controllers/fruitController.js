const fruits = require('../models/data.js')

const index = () => {
    for (const fruit of fruits) {
        console.log(fruit)
    }
}

const store = (fruit) => {
    fruits.push(fruit)
    index();
}

module.exports = { index, store }