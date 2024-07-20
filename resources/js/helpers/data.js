export const dataAgama = (datas) => {
    let items = []
    const agamas = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'konghuchu']
    for (let a = 0; a < agamas.length; a++) {
        items.push(datas.filter(data => data.agama == agamas[a]).length)
    }

    return items
}
export const dataJk = (datas) => {
    let items = []
    const jks = ['Laki-laki', 'Perempuan']
    for (let a = 0; a < jks.length; a++) {
        items.push(datas.filter(data => data.jk == jks[a]).length)
    }

    return items
}

export default { dataAgama }