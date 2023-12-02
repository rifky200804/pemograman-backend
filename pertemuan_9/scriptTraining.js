// const name = "Muhammad Rifky Syiahbudin"
// const age = 19
// const major = "Teknik Informatika"

// const isMarried = false
const x = null
const y = undefined

// console.log(name,age,major)
// console.log(typeof age)
// console.log(typeof name)
// console.log(typeof x)
// console.log(typeof y)
// console.log(typeof isMarried)

// console.log("nama saya " + name )
// console.log(`Nama saya ${name}`)


// if else
const nilai = 90

if (nilai > 90) {
    grade = "A"
}else if(nilai >80){
    grade = "B"
}else {
    grade = "C"
}

// console.log(`Nilai Anda : ${grade}`)

// if ternary  operator
// age > 20 ? console.log("Sudah dewasa") : console.log("Belum dewasa")

for (let i = 0; i < 11; i++) {
    // console.log(`perulangan ke: ${i}`)
}


function cariTahunLahir(umur) {
    return 2023 - umur
}

const tahunLahir = cariTahunLahir(19)
// console.log(`tahun lahir : ${tahunLahir}`)

// menghitung lauas lingkaran

function calcAreaOfCircle(radius){
    const PHI = 3.14
    let area = PHI * radius * radius
    return area
}
// console.log(calcAreaOfCircle(5))

// array
fruits = ["Banana", "Apple","Orange"]

for (const fruit of fruits) {
    // console.log(fruit)
}

// Object

const user = {
    name : "Muhammad Rifky Syiahbuidin",
    address : "Depok",
    age : 19,
    isMarried : false
}
console.log(user.address)

// Destructing
const {name, address, age, isMarried} = user

console.log(`Status Menikah : ${age}`)

const family = ["Mikel","Hannah","Jonas","Martha"]

const [husband,wife,firstChild,secondChild] = family

console.log(husband,wife,firstChild,secondChild)