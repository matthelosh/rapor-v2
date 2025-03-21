export const  capitalize = (str) => {
       let arr =  str.split(" ")
       let res = ''
       arr.forEach((word,index) => {
        res += word.charAt(0).toUpperCase() + word.substring(1).toLowerCase()+ " "
        // res += [...word[0].toUpperCase(), word.slice(1).toLowerCase()].join("")
       })

       return res
    }

export const namaSekolah = (str) => {
    let arr =  str.split(" ")
       let res = ''
       arr.forEach((word,index) => {
        res += index === 0 ? word + " " : word.charAt(0).toUpperCase() + word.substring(1).toLowerCase()+ " "
       })

       return res
}


    
export default { capitalize }