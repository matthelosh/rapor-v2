import axios from "axios";
import { usePage } from "@inertiajs/vue3";
const page = usePage();
let userDetail = null;

// axios
//     .get(page.props.appUrl + "/userdetail")
//     .then((res) => (userDetail = res.data.userdetail))
//     .catch((err) => console.log(err));
// export const avatar = (user = null) => {
//     if (!userDetail) {
//         return ["superadmin", "admin", "admin_tp", "org", "ops"].includes(
//             page.props.auth.roles[0],
//         )
//             ? "/img/user_l.png"
//             : userDetail.jk == "Laki-laki"
//               ? "/img/user_l.png"
//               : userDetail.agama == "Islam"
//                 ? "/img/user_p_is.png"
//                 : "/img/user_p.png";
//     } else {
//         if (guru.foto == null) {
//             return guru.jk == "Laki-laki"
//                 ? "/img/user_l.png"
//                 : guru.agama == "Islam"
//                   ? "/img/user_p_is.png"
//                   : "/img/user_p.png";
//         } else {
//             return guru.foto;
//         }
//     }
// };

export const fotoGuru = (guru = null) => {
    if (guru) {
        return (
            guru?.foto ??
            (guru.jk == "Laki-laki"
                ? "/img/user_l.png"
                : guru.agama == "Islam"
                  ? "/img/user_p_is.png"
                  : "/img/user_p.png")
        );
    } else {
        return "/img/user_l.png";
    }
};
export const fotoSiswa = (siswa) => {
    return siswa.jk == "Laki-laki"
        ? "/img/siswa.png"
        : siswa.agama == "Islam"
          ? "/img/siswi-is.png"
          : "/img/siswi.png";
};

export default { fotoSiswa };
