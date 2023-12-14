const showDownloadResult = (result) => {
    console.log("Success Download")
    console.log(`Dwnload Result : ${result}`)
}
const processcingDownload =  (result) => {
    return new Promise((resolve) => {
        setTimeout(() => {
            resolve("Download Processing ...")
        }, 1000);
    })
}

const download = () => {
    return new Promise((resolve) => {
        setTimeout(() => {
        const result = "windows-10.exe";
        resolve(result);
        }, 3000);
    }) 
}
// run
const main = async () => {
    console.log(await processcingDownload())
    showDownloadResult(await download())
}

main()