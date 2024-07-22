import { usePage } from '@inertiajs/vue3'
const page = usePage()
export const avatar = (guru = null) => {
    if (guru == null) {
        return page.props.auth.roles.includes('admin') ? '/img/user_l.png' : (page.props.auth.user.userable.jk == 'Laki-laki' ? '/img/user_l.png' : (page.props.auth.user.userable.agama == 'Islam' ? '/img/user_p_is.png' : '/img/user_p.png'))
    } else {
        if (guru.foto == null) {
            return (guru.jk == 'Laki-laki' ? '/img/user_l.png' : (guru.agama == 'Islam' ? '/img/user_p_is.png' : '/img/user_p.png'))
        } else {
            return guru.foto
        }
    }
}

export const fotoSiswa = (siswa) => {
    return siswa.jk == 'Laki-laki' ? '/img/siswa.png' : (siswa.agama == 'Islam' ? '/img/siswi-is.png' : '/img/siswi.png')
}

export default { avatar }