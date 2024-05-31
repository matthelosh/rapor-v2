export const  capitalize = (str) => {
       let arr =  str.split(" ")
       let res = ''
       arr.forEach(word => {
        res += word.charAt(0).toUpperCase() + word.substring(1).toLowerCase()+ " "
       })

       return res
    }

    
export default { capitalize }